<?php
/*
 * Created on 2020.08.06.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
if (defined('pageLang')){
	if (is_file(languagePath.pageLang.'.php')){
		include_once(languagePath.pageLang.'.php');
	}else{
		include_once(languagePath.defaultLang.'.php');
	}
}else
{
	include_once(languagePath.defaultLang.'.php');
}

?>
