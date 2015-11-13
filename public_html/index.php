<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
ini_set('session.cookie_httponly', 1);

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
error_reporting(E_ALL ^E_NOTICE);
//error_reporting(0);

// Path para o www
define('SERVERPATH', dirname(__FILE__).'/');

// Path to the CodeIgniter folder
define('CIPATH', SERVERPATH.'../CodeIgniter/');

// Name of the CodeIgniter "system folder"
define('SYSDIR', 'system');

// Path to the CodeIgniter system folder
define('BASEPATH', CIPATH.SYSDIR.'/');

// Path to the Application
define('APPPATH', CIPATH.'applications/cms/frontend/');

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
define('EXT', '.php');

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Database Profile name (see Application database config file)
define('DB','default');

require_once BASEPATH.'core/CodeIgniter'.EXT;
?>
                            
