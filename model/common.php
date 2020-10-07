<?php
function base_url(){
	if (PATH!=''){
//	if ($_SERVER['SERVER_NAME']=='localhost'){
		$hostdir = explode('/',$_SERVER['REQUEST_URI']);
		$hostdir = '/'.$hostdir[1].'/';
		return sprintf(
	    	"%s://%s%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME'],
			$hostdir
		);
	}else{
		return sprintf(
	    	"%s://%s%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME'],'/'
		);
	}
}

function generateToken(){
	return bin2hex(openssl_random_pseudo_bytes(128));
}// end function generateToken(){

function setToken(){
	$_SESSION['_token'] = generateToken();
	$_SESSION['token_expire'] = time()+360;
}// end function setToken(){

function csrfFormToken($str = "", $secKey = ""){
	if ($secKey == ""){
		$secKey = $_SESSION['_token'];
	}
	return hash_hmac(defaultHashMethod, $str, $secKey);
}// end function csrfFormToken($str = "", $secKey = ""){

function csrfFormTokenInput($str = "", $secKey = ""){
	echo '<input type="hidden" name="_token" value="'.csrfFormToken($str, $secKey).'">';
}// end function csrfFormTokenInput($str = "", $secKey = ""){

function checkFormToken($str, $_token){
	return hash_equals(csrfFormToken($str), $_token);
}


function getDirContent($dirname, $fullPath = 0) {
	$results=0;
	if (is_dir($dirname)) {
		$results=array();
		$handle = opendir($dirname);
		while ($file = readdir($handle)) {
			if ($file!='.' && $file!='..'){
				if ($fullPath == '0'){
					$results[]=$file;
				}else{
					$results[]=str_replace('//','/',$dirname.'/'.$file);
				}
			}
		}
		closedir($handle);
	}
	return $results;
}

function getTabs($dirname) {
	$results=0;
	if (is_dir($dirname)) {
		$results=array();
		$handle = opendir($dirname);
		while ($file = readdir($handle)) {
			if ($file!='.' && $file!='..' && substr($file,0,4)=='tab-'){
				$results[]=$file;
			}
		}
		closedir($handle);
	}
	return $results;
}

function readAllDir($dir){
	$result = array();
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
	             RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
	    if ($file->isDir()){
	        $result[] = $file->getPathname();
	    }
	}
	unset($it);
	unset($files);
	return $result;
}

function readAllFromDir($dir){
	$result = array();
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
	             RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
	    if ($file->isDir()){
	     //   $result[] = $file->getPathname();
	    } else {
	        $result[] = $file->getPathname();
	    }
	}
	unset($it);
	unset($files);
	return $result;
}

function readFileContent( $file ) {
	if ( is_file( $file ) ) {
		$handle = fopen( $file, "rb");
		$contents = '';
		while ( !feof( $handle ) ) {
	  		$contents .= fread( $handle, 8192 );
		}
		fclose( $handle );
		return $contents;
	}
	return false;
}

function saveToFile($filename, $content, $xmlHeader = 'default', $rootNode = 'default'){
	$ext = getFileExtension($filename);
	if ($ext == 'xml'){
		if ($xmlHeader == "default"){
			$xmlHeader = '<?xml version="1.0" encoding="UTF-8" ?>';
		}

		if ($rootNode == "default"){
			$rootNodeOpen = "<Root>";
			$rootNodeClose = "</Root>";
		}else if ($rootNode == null){
			$rootNodeOpen = "";
			$rootNodeClose = "";
		}else{
			$rootNodeOpen = "<$rootNode>";
			$rootNodeClose = "</$rootNode>";
		}

		$path = ltrim(pathinfo($filename, PATHINFO_DIRNAME), '/')."/";
		$basename = pathinfo($filename, PATHINFO_BASENAME);
		if ($path == ""){

		}else if (!is_dir($path)) {
			if (!mkdir($path, 0755, true)) {
				die('Failed to create folders...');
			}
		}
		file_put_contents($path.$basename, $xmlHeader.$rootNodeOpen.$content.$rootNodeClose);
	}else{
		$path = ltrim(pathinfo($filename, PATHINFO_DIRNAME), '/')."/";
		$basename = pathinfo($filename, PATHINFO_BASENAME);

		if ($path == ""){

		}else if (!is_dir($path)) {
			if (!mkdir($path, 0755, true)) {
				die('Failed to create folders...');
			}
		}

		file_put_contents($path.$basename, $content);
	}
	return $path.$basename;
}// end function saveToFile($filename, $content, $xmlHeader = 'default', $rootNode = 'default'){

function Redirect( $link ) {

echo <<<REDIRCODE
<html>
<head>
	<script language='JavaScript'>
	<!--
		window.location = "$link";
	//-->
	</script>
</head>
<body>
</body>
</html>
REDIRCODE;
	exit();
} // end function Redirect

// file routines

function getFilenameWithoutExt($filename){
    $filename = basename( $filename );
    $pos = strrpos( $filename, '.' );
    if( $pos === false ){
        return $filename;
    }
    else{
        return substr( $filename, 0, $pos );
    }
}

function getFileExtension($filename)
{
	$tmp = explode('.',$filename);
	return strtolower($tmp[count($tmp)-1]);
}

function createValidFilename( $str ){
	$replace="_";
	$pattern="/([[:alnum:]_.-]*)/";

	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	$str = str_replace($a, $b, $str);

	return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
	array(' ', '-'), $str));

}

function isSupportedFileType($fileArray, $extensionsArray=array()){
	if (count($extensionsArray)==0){
		$allowedExtensions = array("pdf");
	}else{
		$allowedExtensions = $extensionsArray;
	}

	if ($fileArray['tmp_name'] != '') {
		$ext = getFileExtension($fileArray['name']);
    	if (!in_array($ext,$allowedExtensions)) {
      		return 0;
      	}else{
			return 1;
      	}
	}
}

function get_mime_type($file) {
	$mtype = false;
	if (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
	} elseif (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file);
	}
	return $mtype;
}

function uploadFile( $fileArray, $Directory, $supportedFileTypes = array(), $FILENAME = "", $overwrite=0){
	$filename = '';
	$Directory = str_replace('//','/',$Directory.'/');
	if ( 1 == isSupportedFileType($fileArray, $supportedFileTypes) ){
			$FileExt = getFileExtension($fileArray['name']);
			if ($FILENAME == ""){
				$filename=createValidFilename($fileArray['name']);
			}else{
				$filename = $FILENAME;
			}
			$justfilename = getFilenameWithoutExt($filename);
			$i=0;
			$counter = "";
			if ($overwrite==0){
				while (is_file($Directory.$justfilename.$counter.'.'.$FileExt)){
					$i++;
					$counter = '_'.$i;
				}
			}
			$filename = $justfilename.$counter.'.'.$FileExt;

			if (move_uploaded_file($fileArray['tmp_name'],$Directory.$filename)){
/*
				$oldmask=umask(0);
				$chmodded = chmod('./'.$Directory.'/'.$filename,0775);
				umask($oldmask);
*/
				return $filename;
			}else{
				return false;
			}
		}
}

function debugText($arr){
  echo '<div align="left"><p>DEBUG MODE</p><pre>'.htmlentities(print_r($arr, true)).'</pre></div>';
}

function get_ip_address(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

function _table($tableName){
	include_once(configPath.'tablenames.php');
	if (in_array($tableName,$tableNames)) return $tableName;
	return null;
}

function writeErrorLog( $file, $line, $errorMessage, $stop = true ){
	if (!is_dir(rootPath.'log')) mkdir(rootPath.'log',0755);
	$f = fopen(rootPath.'log/error.log','a');
	$errorLog = "[".date("Y-m-d H:i:s")."] ";
	$errorLog .= "Error in ";
	$errorLog .= "$file ";
	$errorLog .= "Line: $line; ";
	$errorLog .= $errorMessage."\r\n";
	fwrite($f,$errorLog);
	fclose($f);
	if (devModeOn){
		if ($stop){
			echo $errorMessage.'<br>';
			exit;
		}
	}else{
		if ($stop){
			echo "Sorry, there are some errors!";
			exit;
		}
	}
}

function writeLog( $message ){
	if (!is_dir(rootPath.'log')) mkdir(rootPath.'log',0755);
	$f = fopen(rootPath.'log/activity.log','a');
	$log = "[".date("Y-m-d H:i:s")."] ";
	$log .= $message."\r\n";
	fwrite($f,$log);
	fclose($f);
}


function xssSafe($str){
	require_once(rootPath.'vendor/autoload.php');
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$clean_html = $purifier->purify($str);
	return trim($clean_html);
}

function xssSafeArray($arr){
	require_once(rootPath.'vendor/autoload.php');
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);

	foreach ($arr as $key => $value){
		if (is_array($value)){
			$arr[$key] = xssSafeArray($value);
		}else{
			$arr[$key] = $purifier->purify($value);
		}
	}

	return $arr;

//	return trim($str);
//	return trim(strip_tags($str,'<table><thead><tbody><tfoot><tr><th><td><ul><li><ol><s><span><h1><h2><h3><h4><h5><h6><u><b><p><a><i><b><br><pre><strong>'));
}

function removeHex($str){
	$pattern = '/0x[0-9a-fA-F]{1,32}/i';
	return trim(preg_replace($pattern, ' ', $str));
}

function removeSpecialChars($str){
	$pattern = array('/[^\p{L}\p{N}\s]/u', '/\s/');
	return trim(preg_replace($pattern, ' ', $str));
}

function checkSqlInjection($str){
// hard
//	$pattern = '/(?:select |insert |update |delete |drop |alter |;|\-\-|\*|#|=)/mi';
// ligh
	$pattern = '/(?:;|\-\-|\*|#|=)/mi';
	return preg_match_all($pattern, $str, $matches);

}

function removeSqlInjection($str){
// hard
//	$pattern = '/(?:select |insert |update |delete |drop |alter |;|\-\-|\*|#|=)/mi';
// ligh way
	$pattern = '/(?:;|\-\-|\*|#|=)/mi';
	return preg_replace($pattern, ' ', $str);
}

function sqlSafe($str){
	$tmp = removeHex($str);
	$tmp = removeSqlInjection($tmp);
	return $tmp;
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

		//close the zip -- done!
		$zip->close();

		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}
