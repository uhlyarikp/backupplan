<?php
require_once('token.php');
require_once('session.php');
class application
{
	use language;
    public $uri;
	public $dbo;
	public $token = "";

    function __construct($request, $dbo)
    {
        $this->uri = $request;
		foreach ($request as $name => $value){
			$this->{$name} = $value;
		}

        $this->dbo = $dbo;
        $this->token = new token();

    } // end function __construct($request)

    function getFormdataFromPost($formname, $postvars)
    {
        $formdata = array();
        foreach ($postvars as $name => $value) {
            if (false !== strpos($name, $formname . '_')) {
                $elements = explode($formname . "_", $name);
                if (is_array($elements) && count($elements) > 0) {
					if (is_array($value)){

						$formdata[$elements[1]] = xssSafeArray($value);
					}else{
						$value = xssSafe($value);
						if (checkSqlInjection($value))
						{
							$errorMsg = "Suspicious content in post. Field: ".$name.' Content: '.$value." ";
							$errorMsg .= "From IP: ".$_SERVER['REMOTE_ADDR']. ' '.get_ip_address();
							writeErrorLog(__FILE__, __LINE__, $errorMsg, false);
						}
	                    switch ($elements[1]){
	                    	case (preg_match('/email.*/', $elements[1]) ? true : false):
	                            $formdata[$elements[1]] = preg_replace("/\s+/", "", $value);
	                    	break;
	                    	case (preg_match('/password.*/', $elements[1]) ? true : false):
	                            $formdata[$elements[1]] = preg_replace("/\s+/", "", removeSqlInjection($value));
	                    	break;
	                    	default:
								$formdata[$elements[1]] = sqlSafe($value);
	                    	break;
	                    }
					}

                }
            }
        }
        return $formdata;
    }


    // end function getFormdataFromPost
    function loadModel()
    {
		if (!is_file(viewPath.'staticpages/'.$this->uri['model'].'.php')){
	        if ($this->uri['model'] == '') {
	            $this->defaultView();
	        } else {
	            $file = modelPath . $this->uri['model'] . ".php";
	            if (! file_exists($file)) {
	                $this->loadView('error',array("errorMsg"=>$this->lbl_themodeldoesnotexist));
	            } else {
	                require_once ($file);
	            }
	        }
		}

    }

    function loadController()
    {
		if (is_file(viewPath.'staticpages/'.$this->uri['model'].'.php')){
	            $this->loadView('staticpages/'.$this->uri['model']);
		}else{

	        if ($this->uri['controller'] == '') {
	            $this->defaultView();
	        } else {
	            $file = controllerPath .$this->uri['controller']. ".php";
	            if (!file_exists($file)) {
			        $this->loadView('error',array("errorMsg"=>$this->lbl_thecontrollerdoesnotexist));
	            }else{
	                require_once ($file);
	                $controller = new $this->uri['controller']($this->uri, $this->dbo);
	                $controller->dbo = $this->dbo;

	                if ($this->uri['action']==""){
						$controller -> defaultView();
	                }else if (method_exists($controller, $this->uri['action'])) {
	                    $result = $controller->{$this->uri['action']}($this->uri['var']);
						if (is_null($result) || !isset($result['viewName'])){
		                    $controller->loadView($this->uri['model'].'.'.$this->uri['action']);
						}else{
		                    $controller->loadView($result['viewName'], $result);
						}
	                }else{
				        $this->loadView('error',array("errorMsg"=>$this->lbl_theactiondoesnotexist));
	                }
	            }
	        }
		}
    } // end function loadController($className) {

    function loadView($viewName="", $params = "")
    {
		if (false !== strpos($viewName,'.')){
			$viewName = str_replace('.','/',$viewName);
		}

        if (is_array($params) && count($params) > 0)
            extract($params, EXTR_PREFIX_SAME, "sep");

		if ($viewName != "" && is_file(viewPath . "$viewName.php")){
	        require_once (viewPath . "$viewName.php");
		}else if ($this->uri['action']!="" &&
			is_file(viewPath . $this->uri['action'].".php")
		){
	        require_once (viewPath . $this->uri['action'].".php");
		}else if ($viewName == "" && $this->uri['action'] == ""){
	        require_once (viewPath . "index.php");
		}

		if ($viewName=="error" || $viewName=="info") exit;
    }

    // end function loadView($viewName, $params="")
    function defaultView()
    {
		$viewFile = viewPath .$this->uri['model']. '/index.php';
// view for static pages
		$viewFileWithLang = viewPath .$this->uri['model']. '/index_'.pageLang.'.php';

		if (is_file($viewFileWithLang)){
	        require_once ($viewFileWithLang);
		}else if (is_file($viewFile)){
	        require_once ($viewFile);
		}else{
			$this->loadView('error',array('errorMsg'=>$this->lbl_theviewdoesnotexist));
		}
    }

	function loadForm($formName, $vars = null){
        if (is_array($vars) && count($vars) > 0)
            extract($vars, EXTR_PREFIX_SAME, "sep");
		if (is_file(viewPath.$formName.'_'.pageLang.'.php')){
			include_once(viewPath.$formName.'_'.pageLang.'.php');
		}else{
			include_once(viewPath.$formName.'.php');
		}
	}

	function loadPaging($pagingArray){
        if (is_array($pagingArray) && count($pagingArray) > 0)
            extract($pagingArray, EXTR_PREFIX_SAME, "sep");
        require(viewPath . "pagination.php");
	}

	function loggedIn(){
		if (!isset($_SESSION['userdata'])) return false;
		if (!isset($_SESSION['userdata']['id'])) return false;
		if (isset($_SESSION['userdata']['id']) && !is_numeric( $_SESSION['userdata']['id'])) return false;
		if (!isset($_SESSION['userdata']['loggedin'])) return false;
		return $_SESSION['userdata']['loggedin'];
	}// end function loggedIn(){

	function isAdmin(){
		if (!isset($_SESSION['userdata']['admin'])) return false;
		return $_SESSION['userdata']['admin'];
	}// end function isAdmin(){

	function getCountryList(){
		$json = readFileContent(languagePath.'countries_'.pageLang.'.json');
		$array = json_decode($json,true);
		return $array;
	}
} // end class application
