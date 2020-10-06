<?php
/*
 * Created on 2020.08.24.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class settingsControl extends settings{
	public function defaultView(){
		if (!$this->loggedIn())
			$this->loadView('error',array('errorMsg'=>$this->lbl_needlogin));
		if (!$this->isadmin()) Redirect(base_url());

		$emailSettings = readFileContent(configPath.'email_settings.php');
		$dbSettings = readFileContent(configPath.'db_settings.php');

		$emailParams = parse_ini_file(configPath.'email.ini');
		$dbParams = parse_ini_file(configPath.'db.ini');

		$params = array_merge($emailParams, $dbParams);
		$this->loadView('settings.index',$params);
	}

	private function writeIniFile($array, $file){
	    $res = array();
	    foreach($array as $key => $val)
	    {
	        if(is_array($val)){
	            $res[] = "[$key]";
	            foreach ($val as $skey => $sval)
	            	$res[] = "$skey = ".(is_numeric($sval) ? $sval : "'".str_replace("'","\'",$sval)."'");
	        } else $res[] = "$key = ".(is_numeric($val) ? $val : "'".str_replace("'","\'",$val)."'");
	    }
	    $this->safeFileRewrite($file, implode("\r\n", $res));
	}

	private function safeFileRewrite($fileName, $dataToSave){
		if ($fp = fopen($fileName, 'w')){
	        $startTime = microtime(TRUE);
	        do{ $canWrite = flock($fp, LOCK_EX);
	           if(!$canWrite) usleep(round(rand(0, 100)*1000));
	        } while ((!$canWrite) && ((microtime(TRUE)-$startTime) < 5));

	        //file was locked so now we can store information
	        if ($canWrite){
	        	fwrite($fp, $dataToSave);
	            flock($fp, LOCK_UN);
	        }
	        fclose($fp);
	    }

	}

	protected function save(){
		if (!$this->loggedIn())
			$this->loadView('error',array('errorMsg'=>$this->lbl_needlogin));
		if (!$this->isadmin()) Redirect(base_url());
		$emailSettings = $this->getFormdataFromPost('email', $_POST);
		$dbSettings = $this->getFormdataFromPost('db', $_POST);
//		saveToFile(configPath.'email_settings.php',$emailSettings);
//		saveToFile(configPath.'db_settings.php',$dbSettings);
		$emailData = $this->getFormdataFromPost('emails', $_POST);
		$dbData = $this->getFormdataFromPost('dbs', $_POST);
		$this->writeIniFile($emailData, configPath.'email.ini');
		try {
			$dbSettings = parse_ini_file(configPath . "db.ini");
			extract($dbData,EXTR_PREFIX_SAME, "sep");
			$testConn = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset", "$dbUser", "$dbPassword");
			$testConn = null;
			$this->writeIniFile($dbData, configPath.'db.ini');
		} catch (PDOException $e) {
		    $errorMessage = get_class($e).': '.$e->getMessage();
			writeErrorLog( __FILE__, __LINE__, $errorMessage );
			exit;
		}

		Redirect(base_url().'admin/settings');
	}

	protected function writeSettingsValue($name){
		if (isset($$name)) {echo $$name;}else{echo $name;}
	}
}// end class settingsControl extends settings{
?>
