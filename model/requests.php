<?php
/*
 * Created on 2020.08.22.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class requests {

	/**
	 * @param $url
	 * @return array['model','controller','method', 'paramsArray']
	 */
	function requestHandler($url){
		$url = str_replace(array(PATH,'\\\\'), array("",""), $url);

		if (($cutoff = strpos($url, '?')) !== false) {
		    $url = substr($url, 0, $cutoff);
		}

		$url = trim($url, '/');
        $request = array();
		$request['model'] = '';
		$request['controller'] = '';
		$request['action'] = '';
		$request['var'] = array();
		$getParams = "";

		if ($_SERVER['QUERY_STRING']!=''){
			parse_str($_SERVER['QUERY_STRING'], $getParams);
		}

		$uriTmp = preg_split('[\\/]', $url, - 1, PREG_SPLIT_NO_EMPTY);



		if (isset($uriTmp[0])) {
		    $request['model'] = $uriTmp[0];
		    $request['controller'] = $uriTmp[0].'Control';
		}

		if (isset($uriTmp[1])) {
		    $request['action'] = $uriTmp[1];
		}

		$request['var'] = $getParams;
		return $request;
	}

}
?>
