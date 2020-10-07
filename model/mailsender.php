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
		$planData = json_decode(readFileContent(datasPath.backupPlanFile),true);
		if (isset($planData['dbs']) && count($planData['dbs'])!= 0){
			foreach ($planData['dbs'] as $key=>$value){
				if (isset($planData['email'][$key]) && $planData['email'][$key]!='' ){
					if ( is_file(backupsPath.$key.'.sql.gz')){
						$this->mailer->clearAllRecipients();
						$this->mailer->addAddress($planData['email'][$key]);
						$this->mailer->Body = $key." backup file";
						$this->mailer->Subject = $key." backup file";
						$this->mailer->addAttachment(backupsPath.$key.'.sql.gz');
						try{
//							echo "<p>$key trying to send to: ".$planData['email'][$key]."</p>";
							if (!$this->mailer->Send()){
								writeErrorLog(__FILE__, __LINE__, 'Error mail db backup sending process', false);
//								echo "<p>Error on mail sending process</p>";
//								exit;
							}else{
								writeLog("Backup of '$key' database sent to: ".$planData['email'][$key]);

							}
						}catch (Exception $e) {
							writeErrorLog(__FILE__, __LINE__, $e->errorMessage(), false);
						}
					}else{
						//echo "<p>Backup file not found</p>";
					}
				}else{
					//echo "<p>'$key' database has empty email address.</p>";
				}
			}
		}else{
//			echo 'Empty database datas';
		}
		exit;
	}// end function sendTempPassword($userdata, $tmpPwd, $templateData){

}// end class mailsender
?>
