<?php
/*
 * Created on 2020.01.15.
 *
 *
 */
// include site constans
require_once ("consts.php");
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
header( 'Content-Type: text/html; charset=UTF-8');
header('X-XSS-Protection: 1');

// autoload modules
require_once(rootPath."autoload.php");
// create session handler
$session = new session();
// include common functions
require_once (modelPath."common.php");
