<?php
/*
 * Created on 2020.08.14.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require(rootPath.'vendor/autoload.php');
require_once('application.php');
require_once(modelPath.'common.php');

class mailsender extends application{
	protected $mailer;

	public function __construct(){
		$this->init();
	}

	private function init(){
		if (is_file(configPath . "email.ini")) {
			$mailSettings = parse_ini_file(configPath . "email.ini");
			foreach ($mailSettings as $key => $value){
				$this->{$key} = $value;
			}
		}else
		if (is_file(configPath . "email_settings.php")) {
			require(configPath . "email_settings.php");
			foreach ($mailSettings as $key => $value){
				$this->{$key} = $value;
			}
		}else{
			return false;
		}


		$this->mailer = new PHPMailer(true);
	    //Server settings
	    $this->mailer->SMTPDebug = false;               // Enable verbose debug output
	    if ($this->smtpIsSmtp)
	    	$this->mailer->isSMTP();                            // Send using SMTP
	    $this->mailer->Host       = $this->smtpHost;              // Set the SMTP server to send through
	    $this->mailer->SMTPAuth   = $this->smtpAuth;              // Enable SMTP authentication
	    $this->mailer->Username   = $this->smtpUser;              // SMTP username
	    $this->mailer->Password   = $this->smtpPassword;          // SMTP password
	    $this->mailer->SMTPSecure = $this->smtpEncryption;		// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $this->mailer->Port       = $this->smtpPort;              // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	    $this->mailer->CharSet    = $this->mailerCharset;         // UTF-8 mail
	    $this->mailer->isHTML((bool)$this->mailerIsHtml);               // HTML content
	    $this->mailer->From		  = $this->mailerFrom;      		// set sender
	    $this->mailer->FromName	  = $this->mailerFromName;        // set sender name
		return true;
	}// end private function init(){

	function sendBackups(){
//		$this->mailer->addAddress($this->backupAddress);
		$this->mailer->addAddress($this->backupAddressDev);
		$files = getDirContent(backupsPath);
		if ($files != 0){
			foreach ($files as $element){
				if ($element != 'plan.json'){
					$this->mailer->Body = $element." backup file";
					$this->mailer->Subject = $element." backup file";
					$this->mailer->addAttachment(backupsPath.$element);
				}
			}
			try{
				$this->mailer->Send();
			}catch (Exception $e) {
				echo $e->errorMessage();
			}
		}
	}// end function sendTempPassword($userdata, $tmpPwd, $templateData){

}// end class mailsender
?>
