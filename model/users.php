<?php
/*
 * Created on 2020.02.24.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('application.php');

class users extends application {
	function setUserPassword($password){
//		return hash_hmac(defaultHashMethod,$password,SALT);
		return md5($password);
	}

	function getUsersCount($where="", $values=array()){
		$sql = "SELECT count(*) as c1 " .
				"FROM members " ;
		if ($where != '') $sql .= "WHERE $where " ;
		$res = $this->dbo->getData($sql, $values);
		return $res[0]['c1'];

	}// end function getUserCount

	function getUsers($where="", $values=array(), $orderBy = "members.name", $orderDir = "asc", $form = 0, $rows = 1000000){
		$sql = "SELECT " .
				"members.* " .
				"FROM members ";
		if ($where != '') $sql .= "WHERE $where ";
		$res = $this->dbo->getData($sql, $values, $orderBy, $orderDir, $form, $rows);
		return $res;
	}// end function getUsers()

	function getUsersForDataTable($where="", $values=array(), $orderBy = "members.name", $orderDir = "asc", $form = 0, $rows = 1000000){
		$sql = "SELECT " .
				"members.* " .
				"FROM members ";
		if ($where != '') $sql .= " WHERE $where ";
		$res = $this->dbo->getDataSimple($sql, $values, $orderBy, $orderDir, $form, $rows);
		return $res;
	}// end function getUsersForDataTable()

	function getUsersByEmail($email){
		if ( isset($email) && $email!='' ){
			$sql = "Select * from members where email=:email;";
			$res = $this->dbo->getData($sql, array('email' => $email));
			return $res[0];
		}
	}

	function getUsersById($id){
		if ($this->dbo->valueExist('members','id',$id) == "") return 0;
		$res = $this->getUsers('members.id=:id',array('id'=>$id));
		return $res[0];
	}

	function setPreferedLang($lang, $membersID){
		$this->updateUsers(array('id'=>intval($membersID),'preferredlang'=>substr($lang,0,2)));
	}// end function setPreferedLang($lang, $userID){

	function passwordValidation($password, $password2){
		if ($password == "") return array('valid' => 0, 'errorMsg' => $this->lbl_passwordrequired);
		if ($password2 == "") return array('valid' => 0, 'errorMsg' => $this->lbl_passwordconfirmrequired);
		if ($password !== $password2) return array('valid' => 0, 'errorMsg' => $this->lbl_passwordsdontmatch );
		if (strlen($password) < pwdMinLength) return array('valid' => 0, 'errorMsg' => $this->lbl_passwordlengthmustbe);
	    if (!preg_match("/[0-9]+/", $password)) return array('valid' => 0, 'errorMsg' => $this->lbl_passwordnotcontainsnumber);
	    if (!preg_match("/[a-z]+/", $password)) return array('valid' => 0, 'errorMsg' => $this->lbl_passwordnotcontainslower);
	    if (!preg_match("/[A-Z]+/", $password)) return array('valid' => 0, 'errorMsg' => $this->lbl_passwordnotcontainsupper);
		if (!preg_match('/[`!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~]/', $password))return array('valid' => 0, 'errorMsg' => $this->lbl_passwordnotcontainspecial);
		return array('valid' => 1);
	}
}// end class users
