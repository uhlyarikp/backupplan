<?php
/*
 * Created on 2020.10.05.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('application.php');
require_once('token.php');

class backupPlan extends application{
	public $planData = [];
	public $dbExpUser;
	public $dbExpPassword;
	public $_listConn;

	public function __construct(){
		if (is_file(datasPath.backupPlanFile)){
			$this->planData = json_decode(readFileContent(datasPath.backupPlanFile),true);
		}
		$dbSettings = parse_ini_file(configPath . "db.ini");
		extract($dbSettings,EXTR_PREFIX_SAME, "dsep");

		$emailSettings = parse_ini_file(configPath . "email.ini");
		extract($emailSettings,EXTR_PREFIX_SAME, "esep");

		if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='localhost'){
			$this->dbExpUser = $dbExpUser = $dbExpUser_dev;
			$this->dbExpPassword = $dbExpPassword = $dbExpPassword_dev;
			$this->backupAddress = $backupAddressDev;
		}else{
			$this->dbExpUser = $dbExpUser;
			$this->dbExpPassword = $dbExpPassword;
			$this->backupAddress = $backupAddress;
		}
        $this->token = new token();

	}

	public function dbList(){
		try {
			$dbData = false;
	        $db_options = array(
	            /* important! use actual prepared statements (default: emulate prepared statements) */
	            PDO::ATTR_EMULATE_PREPARES => false
	            /* throw exceptions on errors (default: stay silent) */
	            , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            /* fetch associative arrays (default: mixed arrays)    */
	            , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM
	            /* disable muli query statment */
				,PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
	        );
			$dbSettings = parse_ini_file(configPath . "db.ini");
			extract($dbSettings,EXTR_PREFIX_SAME, "sep");

			if ($_SERVER['SERVER_NAME']=='localhost'){
				$dbExpUser = $dbExpUser_dev;
				$dbExpPassword = $dbExpPassword_dev;
			}
			if ( $dbExpUser != "" && $dbExpPassword != "" ){
				$this->_listConn = new PDO("mysql:host=$dbHost", "$dbExpUser", "$dbExpPassword", $db_options);
				$data = $this->_listConn->query( 'SHOW DATABASES');
				$dbData = array();
				while( ( $db = $data->fetchColumn( 0 ) ) !== false )
				{
					$dbData[] = $db;
				}
				return $dbData;
			}

		} catch (PDOException $e) {
		    $errorMessage = get_class($e).': '.$e->getMessage();
			writeErrorLog( __FILE__, __LINE__, $errorMessage, false );
			return false;
		}
		return $dbData;
	}



	public function createBackups(){
		if (count($this->planData)!=0){
			foreach ($this->planData['dbs'] as $key => $value){
//				$command = 'mysqldump --opt --user="'.$this->dbExpUser.'" --password="'.$this->dbExpPassword.'" '.$key.' | gzip >  /home/tonerkoz/backupplan.mobil-tartozek.hu/backups/'.$key.'.sql.gz';
				$command = 'mysqldump --opt --user="'.$this->dbExpUser.'" --password="'.$this->dbExpPassword.'" '.$key.' | gzip > '.backupsPath.$key.'.sql.gz';
//				$command = 'mysqldump --opt --user="'.$this->dbExpUser.'" --password="'.$this->dbExpPassword.'" '.$key.' > '.rootPath.'backups/'.$key.'.sql';
				$escaped_command = escapeshellcmd($command);
				exec($command);
			}
		}

		$files = getDirContent(backupsPath);
		foreach ($files as $element){
			if (create_zip(array(backupsPath.$element.'.sql'),backupsPath.$element.'.sql.zip',true))
				unlink(backupsPath.$element);
		}

	}// end public function createBackup(){

}
?>
