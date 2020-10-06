<?php
/*
 * Created on 2020.09.01.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('language.php');
class token{
	use language;
	function __construct(){

	}

	function __clone(){

	}

	function generate(){
		return bin2hex(openssl_random_pseudo_bytes(128));
	}// end function generateToken(){

	function setToken($forceRefresh = 0){
		if ($forceRefresh == 1){
			$_SESSION['_token'] = generateToken();
			$_SESSION['_token_expire'] = time()+tokenExpireTime;
		}else
		if (!isset($_SESSION['_token'])){
			$_SESSION['_token'] = generateToken();
			$_SESSION['_token_expire'] = time()+tokenExpireTime;
		}else
		if ($this->expired()){
			$_SESSION['_token'] = generateToken();
			$_SESSION['_token_expire'] = time()+tokenExpireTime;
		}
	}// end function setToken(){

	function expired(){
		if (!isset($_SESSION['_token_expire'])){
			return true;
		}else if (isset($_SESSION['_token_expire']) && $_SESSION['_token_expire']<time() ){
			return true;
		}else{
			return false;
		}
	}// end function generateToken(){

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
		if ($this->expired()) return false;
		return hash_equals(csrfFormToken($str), $_token);
	}
} //end class token
?>
