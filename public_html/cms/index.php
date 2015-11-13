<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
ini_set('session.cookie_httponly', 1);

/**
 * HABILITA ERROS, somente para debug!
 */
error_reporting(E_ALL ^E_NOTICE);

// Diretorio raiz
$path_array = explode('/', dirname(__FILE__));
array_pop($path_array);
define('SERVERPATH', implode('/', $path_array).'/');

// Diretorio do CodeIgniter
define('CIPATH', implode('/', $path_array).'/../CodeIgniter/');

// Name of the CodeIgniter "system folder"
define('SYSDIR', 'system');

// Path to the CodeIgniter system folder
define('BASEPATH', CIPATH.SYSDIR.'/');

// Path to the Application
define('APPPATH', CIPATH.'applications/cms/backend/');

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
define('EXT', '.php');

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Database Profile name (see Application database config file)
define('DB','default');

require_once BASEPATH.'core/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */
