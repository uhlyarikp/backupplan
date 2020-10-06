<?php
/*
 * Created on 2020.10.05.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('config/base.php');
$requests = new requests();

//$dbo = dbo::getInstance($Connection);
$dbo = dbo::getInstance();

define("pageLang", 'hu');

$request = $requests->requestHandler($_SERVER['REQUEST_URI']);

$application = new application($request, $dbo);
$application->token->setToken();

if (!$application->loggedIn() && $request['model']!='login'){
	Redirect(base_url().'login');
}
$application->loadModel($request);
$application->loadController();
?>

