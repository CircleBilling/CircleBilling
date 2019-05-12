<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('SYSTEM_PATH_ROOT',      dirname(__FILE__));
define('SYSTEM_PATH_VENDOR',    SYSTEM_PATH_ROOT . '/vendor');
define('SYSTEM_PATH_LIBRARY',   SYSTEM_PATH_ROOT . '/library');
define('SYSTEM_PATH_THEMES',    SYSTEM_PATH_ROOT . '/themes');
define('SYSTEM_PATH_MODS',      SYSTEM_PATH_ROOT . '/modules');
define('SYSTEM_PATH_LANGS',     SYSTEM_PATH_ROOT . '/locale');
define('SYSTEM_PATH_UPLOADS',   SYSTEM_PATH_ROOT . '/uploads');
define('SYSTEM_PATH_DATA',   SYSTEM_PATH_ROOT . '/data');

function isSSL() {
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}


function handler_error($number, $message, $file, $line)
{
    if (E_RECOVERABLE_ERROR===$number) {
        handler_exception(new ErrorException($message, $number, 0, $file, $line));
    } else {
        error_log($number." ".$message." ".$file." ".$line);
    }
    return false;
}

function handler_exception(Throwable $e)
{
    if(APPLICATION_ENV == 'testing') {
        print $e->getMessage() . PHP_EOL;
        return ;
    }
    error_log($e->getMessage());

    if(defined('SYSTEM_MODE_API')) {
        $code = $e->getCode() ? $e->getCode() : 9998;
        $result = array('result'=>NULL, 'error'=>array('message'=>$e->getMessage(), 'code'=>$code));
        print json_encode($result);
        return false;
    }

    $page = "<!DOCTYPE html>
    <html lang=en>
    <meta charset=utf-8>
    <title>Error</title>
    <style>
    *{margin:0;padding:0}html,code{font:15px/22px arial,sans-serif}html{background:#fff;color:#222;padding:15px}body{margin:7% auto 0;min-height:180px;padding:30px 0 15px}* > body{padding-right:205px}p{margin:11px 0 22px;overflow:hidden}ins{color:#777;text-decoration:none}a img{border:0} em{font-weight:bold}@media screen and (max-width:772px){body{background:none;margin-top:0;max-width:none;padding-right:0}}pre{ width: 100%; overflow:auto; }
    </style>
    <a href=//www.boxbilling.com/ target='_blank'><img src='https://sites.google.com/site/boxbilling/_/rsrc/1308483006796/home/logo_boxbilling.png' alt='BoxBilling' style='height:60px'></a>
    ";
    $page = str_replace(PHP_EOL, "", $page);
    print $page;
    if($e->getCode()) {
        print sprintf('<p>Code: <em>%s</em></p>', $e->getCode());
    }
    print sprintf('<p>%s</p>', $e->getMessage());
    print sprintf('<p><a href="http://docs.circlebilling.com/en/latest/" target="_blank">Look for detailed error explanation</a></p>', urlencode($e->getMessage()));

    if(defined('SYSTEM_DEBUG') && SYSTEM_DEBUG) {
        print sprintf('<em>%s</em>', 'Set SYSTEM_DEBUG to FALSE, to hide the message below');
        print sprintf('<p>Class: "%s"</p>', get_class($e));
        print sprintf('<p>File: "%s"</p>', $e->getFile());
        print sprintf('<p>Line: "%s"</p>', $e->getLine());
        print sprintf('Trace: <pre>%s</pre>', $e->getTraceAsString());
    }
}

set_exception_handler("handler_exception");
set_error_handler('handler_error');

// multisite support. Load new config depending on current host
// if run from cli first param must be hostname
$configPath = SYSTEM_PATH_ROOT.'/config.php';
if((isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']) || (php_sapi_name() == 'cli' && isset($argv[1]) ) ) {
    if(php_sapi_name() == 'cli') {
        $host = $argv[1];
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }

    $predictConfigPath = SYSTEM_PATH_ROOT.'/config-'.$host.'.php';
    if(file_exists($predictConfigPath)) {
        $configPath = $predictConfigPath;
    }
}

// check if config is available
if(!file_exists($configPath) || 0 == filesize( $configPath )) {

    //try create empty config file
    @file_put_contents($configPath, '');

    $protocol = 'http';
    if(isSSL() === true) {
        $protocol .= 's';
    }
    $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
    $base_url .= preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
    $url = $base_url . 'install/index.php';
    $configFile = pathinfo($configPath, PATHINFO_BASENAME);
    $msg = sprintf("There doesn't seem to be a <em>$configFile</em> file or config.php file does not contain required configuration parameters. I need this before we can get started. Need more help? <a target='_blank' href='http://docs.boxbilling.com/en/latest/reference/installation.html'>We got it</a>. You can create a <em>$configFile</em> file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file.</p><p><a href='%s' class='button'>Continue with BoxBilling installation</a>", $url);
    throw new Exception($msg, 101);
}

$config = require_once $configPath;
require SYSTEM_PATH_VENDOR . '/autoload.php';

date_default_timezone_set($config['timezone']);

define('SYSTEM_DEBUG',          $config['debug']);
define('SYSTEM_URL',            $config['url']);
define('SYSTEM_SEF_URLS',       $config['sef_urls']);
define('SYSTEM_PATH_CACHE',     $config['path_data'] . '/cache');
define('SYSTEM_PATH_LOG',       $config['path_data'] . '/log');
define('SYSTEM_SSL',            (substr($config['url'], 0, 5) === 'https'));

if($config['sef_urls']) {
    define('SYSTEM_URL_API',    $config['url'] . 'api/');
} else {
    define('SYSTEM_URL_API',    $config['url'] . 'index.php?_url=/api/');
}

if($config['debug']) {
    error_reporting( E_ALL );
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting( E_RECOVERABLE_ERROR );
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

ini_set('log_errors', '1');
ini_set('html_errors', true);
ini_set('error_log', SYSTEM_PATH_LOG . '/php_error.log');

// Strip magic quotes from request data.
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    // Create lamba style unescaping function (for portability)
    $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
    $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
    $stripslashes_deep = function(&$value, $fn) {
        if (is_string($value)) {
            $value = ' . $unescape_function . ';
        } else if (is_array($value)) {
            foreach ($value as &$v) {
                $fn($v, $fn);
            }
        }
    };

    // Unescape data
    $stripslashes_deep($_POST, $stripslashes_deep);
    $stripslashes_deep($_GET, $stripslashes_deep);
    $stripslashes_deep($_COOKIE, $stripslashes_deep);
    $stripslashes_deep($_REQUEST, $stripslashes_deep);
}
