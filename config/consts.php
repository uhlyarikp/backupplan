<?php
/*
 * Created on 2020.10.05.
 *
 *  * CONSTANTS DECLARATIONS
 *
 */
//url http://localhost
$docRoot = realpath(str_replace("\\", "/", dirname(__FILE__)) . '/../') . '/';
// path in the $_SERVER['REQUEST_URI']
// THE PATH CANNOT BE THE SAME AS ONE OF THE MODULES OR METHODS NAMES OR PART OF THE MODULES OR METHODS NAMES!
// e.g.: /dyfm IS A VALID PATH NAME, /sticker ISN'T
define("PATH", "/backupplan/");
// root directory path
define("rootPath",$docRoot);
// backups directory path
define("backupsPath", rootPath . "backups/");
// json data files directory path
define("datasPath", rootPath . "datas/");
// json backup plan file name
define('backupPlanFile','plan.json');
// tmp directory path
define("tmpPath", rootPath . "tmp/");
// config files path
define("configPath", rootPath . "config/");
// model files path
define("modelPath", rootPath . "model/");
// controller files path
define("controllerPath", rootPath . "controller/");
// view files path
define("viewPath", rootPath . "view/");
// language files path
define("languagePath", rootPath . "lang/");
define("pwdMinLength", 8);
// accepted currencies
define("currenciesData", array("EUR","GBP","HUF","USD"));
// supported languages
define("languagesData", array("en","hu","de"));
// default page language
define("defaultLang", "hu");
// default hash method for generating token and password hashing
// DO NOT CHANGE THIS VALUE AFTER THE FIRST USER INSERTED
define("defaultHashMethod","sha512");
// SALT what you want for generating token and password hashing
// DO NOT CHANGE THIS VALUE AFTER THE FIRST USER INSERTED
define("SALT","Cu8on0u6z2QniZgrWKUwMTXEhlahxLtVzILRetje0bt3GNOWvI7rvxpUw067D66Uh6ygL0K6RUnYNH9L6WCfyUIGxlnCdok4b78IkFvnN11ZThSxexEhxOYiVkddncgs5nWmAX6XH8Fh");
// token expire time default value 3600 sec 1 hour
define('tokenExpireTime',3600);
// develpoer mode
// true: display error messages, false: save error log file
define("devModeOn",true);
// are cookies accepted?
define('acceptCookies',isset($_COOKIE["acceptCookies"]));
?>
