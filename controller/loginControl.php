<?php
/*
 * Created on 2020.02.24.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class loginControl extends users {
	function login(){
		global $session;
		if (!isset($_POST['_token']) || !$this->token->checkFormToken('loginform',$_POST['_token'])){
			$this->loadView('error',array('errorMsg'=>$this->lbl_tokenerror));
	    }
		$formdata = $this->getFormdataFromPost('login', $_POST);
		unset($formdata['formid']);
		if ($formdata['loginname']=="")
		{
			return array('success'=>'0', "errorMsg"=>$this->lbl_loginnamerequired,"viewName"=>'login.index');
		}
		$rawPass = $formdata['password'];
		$formdata['password'] =  $this -> setUserPassword($formdata['password']);

		$res = $this->getUsers('members.loginname=:loginname', array('loginname'=>$formdata['loginname']));

		if ($res!=0){
			$res = $res[0];
			if ($res['password']===$formdata['password'] && $res['active']=='1'){
				$_SESSION['userdata'] = $res;
				unset($_SESSION['userdata']['password']);
				$_SESSION['userdata']['loggedin'] = 1;
				$_SESSION['adminroute'] = "";
				$session->regenerateId();
				$this->token->setToken(1);
				Redirect(base_url().'backupplan');
			}else{
				$this->loginFailed($formdata);
				return array('success'=>'0', "errorMsg"=>$this->lbl_loginfailed,"viewName"=>'login.index');
			}
		}else{
			$this->loginFailed($formdata);
			return array('success'=>'0', "errorMsg"=>$this->lbl_loginfailed,"viewName"=>'login.index');
		}
	}

	function loginFailed($formdata){
// regenerate token
		$this->token->setToken(1);

 // email is not in database
		if (0 == $res = $this->getUsers('members.loginname=:loginname', array('loginname'=>$formdata['loginname']))){

// password is invalid
		}else if (0 == $res = $this->getUsers('members.loginname=:loginname and members.password=:password', $formdata)){

// user is inactive
		}else if (0 == $res = $this->getUsers('members.loginname=:loginname and members.password=:password and active=1', $formdata)){

		}

	}

	function logout(){
		foreach ($_SESSION as $key => $value){
			unset($_SESSION[$key]);
		}
		Redirect(base_url());
	}
}
?>
