<?php
class Dbo{

    private $_conn;
    private static $_instance; //The single instance
    public $state;
	public $pagingConfig;
    /*
    Get an instance of the Database
    @return Instance
    */
//    public static function getInstance($Connection)
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
//    private function __construct($Connection)
    private function __construct()
    {
		$this->pagingConfig = array();
		$this->pagingConfig['users'] = 30;
		$this->pagingConfig['stickers'] = 50;
		$this->pagingConfig['mystickes'] = 15;
		$this->pagingConfig['campaigns'] = 30;
		try {

	        $db_options = array(
	            /* important! use actual prepared statements (default: emulate prepared statements) */
	            PDO::ATTR_EMULATE_PREPARES => false
	            /* throw exceptions on errors (default: stay silent) */
	            , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	            /* fetch associative arrays (default: mixed arrays)    */
	            , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	            /* disable muli query statment */
				,PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
	        );
			$dbSettings = parse_ini_file(configPath . "db.ini");
			extract($dbSettings,EXTR_PREFIX_SAME, "sep");
			if (
			$dbHost != "" &&
			$dbName != "" &&
			$dbUser != "" &&
			$dbPassword != "" &&
			$dbCharset != ""
			){
				$this->_conn = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset", "$dbUser", "$dbPassword", $db_options);
				$this->state = 'connected';
			}else{
				$this->_conn = null;
				$this->state = null;
			}

		} catch (PDOException $e) {
		    $errorMessage = get_class($e).': '.$e->getMessage();
			writeErrorLog( __FILE__, __LINE__, $errorMessage );
			exit;
		}


//		$this->_conn = $Connection;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }

    // Get mysql pdo connection
    public function getConnection()
    {
        return $this->_conn;
    }

	function getSentQuery($stmt, $startPos = 'Sent SQL') {
		ob_start();
		$stmt->debugDumpParams();
		$str = ob_get_contents();
		ob_end_clean();
		preg_match('/'.$startPos.'\:\s*\[\d+\]\s+([^\:]+)/', $str, $out);
		if (isset($out[1])){
			return '"'.substr($out[1],0,-7).'"';
		}else{
			return '"'.$str.'"';
		}
	}

	function onError($file="", $line="", $function="", $Query, $exceptionMessage, $exceptionCode) {
		$errorCode = "N/A";
		$errorMsg = "<pre>There is some error</pre>";
		if (devModeOn){
			$errorMsg = "<pre>DEBUG MODE ON</pre>";
			$errorMsg .= "<pre>PDO error in ";
			$errorMsg .= "File: $file ";
			$errorMsg .= "Line: $line ";
			$errorMsg .= "Function: $function</pre>";
			$errorMsg .= "<pre>Sent Query: ".$this->getSentQuery($Query)."</pre>";
			$errorMsg .= "<pre>Exception code: ".$exceptionCode."</pre>";
			$errorMsg .= "<pre>Exception message: ".$exceptionMessage."</pre>";
		}else{
			$f = fopen(rootPath.'log/db_error.log','a');
			$errorLog = "[".date("Y-m-d H:i:s")."] ";
			$errorLog .= "PDO error in ";
			$errorLog .= "$file ";
			$errorLog .= "line: $line ";
			$errorLog .= "function: $function\r\n";
			$errorLog .= "Exception code: ".$exceptionCode."\r\n";
			$errorLog .= "Exception message: ".$exceptionMessage."\r\n";
			$errorLog .= "Sent Query:\r\n".$this->getSentQuery($Query)."\r\n";
			$errorLog .= "\r\n";
			fwrite($f,$errorLog);
			fclose($f);
		}
		echo $errorMsg;
		exit;
	} // end function onError


	function getPagingConfigForDatagrid( $resultPage ) {
		if ( isset( $this -> pagingConfig[$resultPage] ) ) {
			return $this -> pagingConfig[$resultPage];
		}
		else {
			return 20;
		}

	} // end function getPagingConfigForDatagrid

	function setPagingForDatagrid( $resultPerPage, $maxelements) {
		$startFrom = 0;
		$previousPage = "";
		$currentPage = 1;
		$nextPage = "";

		if (!is_numeric($resultPerPage) ){
			$rowsOnPage = $this->getPagingConfigForDatagrid( $resultPerPage );
		}else{
			$rowsOnPage = 20;
		}

		if ( $maxelements > $rowsOnPage ) {
			$totalPages = ( int ) ceil( $maxelements / $rowsOnPage );
		}else{
			$totalPages = 1;
		}


		if ( isset( $_GET['page'] ) ) {
			if ($_GET['page'] == 'all'){
				$currentPage = 'all';
				$startFrom = 0;
				$rowsOnPage = $maxelements;
			}else{
				if (intval($_GET['page'])>1){
					$currentPage = intval( $_GET['page'] );
					$previous = intval( $currentPage-1 );
				}
				if ($currentPage != $totalPages) $nextPage = intval($currentPage + 1);
				$startFrom = ( $currentPage - 1 ) * $rowsOnPage;
			}
		}


		return array('totalPages' => $totalPages, 'currentPage' => $currentPage,
					 'previousPage' => $previousPage, 'nextPage' => $nextPage, 'start'=>$startFrom, 'rows' => $rowsOnPage);
	}

    public function getDataCount($sql, $values = array())
    {
		$counterSql = str_replace(substr($sql, 0, stripos($sql,'from')), 'SELECT count(*) as c1 ', $sql);
        $Query = $this->_conn->prepare($counterSql);
        if (!$Query) return $this->_conn->errorInfo();

        if (count($values) != 0) {
            foreach ($values as $key => $value) {
                $Query->bindValue(':' . $key, $value);
            }
        }

		$Query->execute() or die(  $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query ));
        $data = $Query->fetchAll(PDO::FETCH_ASSOC);

        return $data[0]['c1'];
    }

    public function getData($sql, $values = array(), $orderBy = 'id', $orderDir = 'asc', $Start = 0, $RowCount = 10000000)
    {
		try
		{
	        $sql .= " ORDER BY $orderBy $orderDir LIMIT :start, :rowcount";
	        $Query = $this->_conn->prepare($sql);
	        if (!$Query) return $this->_conn->errorInfo();

	        if (count($values) != 0) {
	            foreach ($values as $key => $value) {
	                $Query->bindValue(':' . $key, $value);
	            }
	        }
	        $Query->bindValue(':start', $Start, PDO::PARAM_INT);
	        $Query->bindValue(':rowcount', $RowCount, PDO::PARAM_INT);
		    $Query->execute();
	        $data = $Query->fetchAll(PDO::FETCH_ASSOC);

	        if (count($data) == 0) $data = 0;
	        return $data;
		}
		catch(PDOException $e)
		{
			echo $e;
		    $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query, $e->getMessage(), $e->getCode() );
		}
    }

    function getDataSimple($sql, $values = array(), $orderBy = 'id', $orderDir = 'asc', $Start = 0, $RowCount = 1000000)
    {
		try{
	        $sql .= " ORDER BY $orderBy $orderDir LIMIT :start, :rowcount;";
	        $Query = $this->_conn->prepare($sql);
	        if (!$Query) return $this->_conn->errorInfo();

	        if (count($values) != 0) {
	            foreach ($values as $key => $value) {
	                $Query->bindValue(':' . $key, $value);
	            }
	        }
	        $Query->bindValue(':start', $Start, PDO::PARAM_INT);
	        $Query->bindValue(':rowcount', $RowCount, PDO::PARAM_INT);

			$Query->execute();
	        $data = $Query->fetchAll(PDO::FETCH_NUM);

	        if (count($data) == 0) $data = 0;
	        return $data;
		}catch (PDOException $e){
		    $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query, $e->getMessage(), $e->getCode() );
		}
    }

    function insertData($sql, $values = array())
    {
		try
		{
	        $Query = $this->_conn->prepare($sql);
	        if (count($values) != 0) {
	            foreach ($values as $key => $value) {
	                $Query->bindValue(':' . $key, $value);
	            }
	        }
		    $Query->execute();
	        return $this->_conn->lastInsertId();
		}
		catch(PDOException $e)
		{
		    $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query, $e->getMessage(), $e->getCode() );
		}
    }

    function massInsertData($sql, $insert_values = array())
    {
		try {
			$this->_conn->beginTransaction(); // also helps speed up your inserts.
			$Query = $this->_conn->prepare ($sql);
		    $Query->execute();
		} catch (PDOException $e){
			$this->_conn->rollBack();
		    $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query, $e->getMessage(), $e->getCode() );
		}

		$this->_conn->commit();
    }

    function updateData($sql, $values = array())
    {
		try
		{
	        $Query = $this->_conn->prepare($sql);
	        if (count($values) != 0) {
	            foreach ($values as $key => $value) {
	            	$Query->bindValue(':' . $key, $value);
	            }
	        }
	        $nrows = $Query->execute();
			return $nrows;
		}
		catch(PDOException $e)
		{
		    $this->OnError( __FILE__, __LINE__, __FUNCTION__, $Query, $e->getMessage(), $e->getCode() );
		}
    }

	function valueExist($tableName, $fieldName, $value){
		return $res = $this->getData('SELECT id from '._table($tableName).' WHERE '.trim($fieldName).'=:'.trim($fieldName).'',array('id'=>$value));
	}
}
