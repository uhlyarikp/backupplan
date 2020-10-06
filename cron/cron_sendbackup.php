<?php
/*
 * Created on 2015.02.04.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../config/consts.php");
include_once(modelPath."common.php");
include_once(modelPath.'mailsender.php');
$mailsender = new mailsender();
$res = $mailsender->sendBackups();
exit;
?>
