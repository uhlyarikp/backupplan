<?php
/*
 * Created on 2020.10.05.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class backupPlanControl extends backupplan{

	public function defaultView(){
		$dbData = 0;
		$planData = 0;
		$dbData = $this->dbList();
		$excludeDb = array('mysql', 'information_schema','performance_schema');
		$this->loadView('backupplan.index', array('dbData'=>$dbData, 'planData' => $this->planData,'excludeDb'=>$excludeDb));
	}

	public function save(){
		if (!isset($_POST['_token']) || !$this->token->checkFormToken('planform',$_POST['_token'])){
			$this->loadView('error',array('errorMsg'=>$this->lbl_tokenerror));
	    }
		$formdata = $this->getFormdataFromPost('planform',$_POST);
		$f = fopen(datasPath.backupPlanFile, "w");
		fwrite($f, json_encode($formdata));
		fclose($f);
		Redirect(base_url().'backupplan/makebackupfiles');
	}// end public function defaultView(){

	public function makeBackupFiles(){
		$this->createBackups();
		Redirect(base_url().'backupplan');
	}// end public function createBackup(){
}
?>
