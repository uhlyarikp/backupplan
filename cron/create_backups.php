<?php
/*
 * Created on 2020.10.05.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../config/consts.php");
include_once(modelPath."common.php");
include_once(modelPath.'backupplan.php');
$backupplan = new backupplan();
$res = $backupplan->createBackups();
exit;
?>
