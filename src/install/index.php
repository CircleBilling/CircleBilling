<?php
/**
 * @return bool
 * @see http://stackoverflow.com/a/2886224/2728507
 */
function isSSL() {
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}

date_default_timezone_set('UTC');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', '1');
ini_set('error_log', dirname(__FILE__) . '/php_error.log');

$protocol = isSSL() ? 'https' : 'http';
$url = $protocol . "://" . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$current_url = pathinfo($url, PATHINFO_DIRNAME);
$root_url = str_replace('/install', '', $current_url).'/';

define('SYSTEM_URL',            $root_url);
define('SYSTEM_URL_INSTALL',    SYSTEM_URL.'install/');
define('SYSTEM_URL_ADMIN',      SYSTEM_URL.'index.php?_url=/admin');

define('SYSTEM_PATH_ROOT',      realpath(dirname(__FILE__).'/..'));
define('SYSTEM_PATH_LIBRARY',   SYSTEM_PATH_ROOT . '/library');
define('SYSTEM_PATH_VENDOR',    SYSTEM_PATH_ROOT . '/vendor');
define('SYSTEM_PATH_THEMES',    SYSTEM_PATH_ROOT . '/install');
define('SYSTEM_PATH_LICENSE',   SYSTEM_PATH_ROOT . '/LICENSE.txt');
define('SYSTEM_PATH_SQL',       SYSTEM_PATH_ROOT . '/install/structure.sql');
define('SYSTEM_PATH_SQL_DATA',  SYSTEM_PATH_ROOT . '/install/content.sql');
define('SYSTEM_PATH_INSTALL',   SYSTEM_PATH_ROOT . '/install');
define('SYSTEM_PATH_CONFIG',    SYSTEM_PATH_ROOT . '/config.php');
define('SYSTEM_PATH_CRON',      SYSTEM_PATH_ROOT . '/cron.php');
define('SYSTEM_PATH_LANGS',     SYSTEM_PATH_ROOT . '/locale');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    SYSTEM_PATH_LIBRARY,
    get_include_path(),
)));

require SYSTEM_PATH_VENDOR . '/autoload.php';

final class Installer
{
    private $session;

    /**
     * @var PDO
     */
    private $dbConnection;

    public function __construct()
    {
        require_once 'session.php';

        $this->session = new Session();
    }
    
    public function run($action)
    {
        switch ($action) {
            case 'check-db':

                $user = $_POST['db_user'];
                $host = $_POST['db_host'];
                $pass = $_POST['db_pass'];
                $name = $_POST['db_name'];
                if(!$this->canConnectToDatabase($host, $name, $user, $pass)) {
                    print 'Could not connect to database. Please check database details.';
                } else {
                    $this->session->set('db_host', $host);
                    $this->session->set('db_name', $name);
                    $this->session->set('db_user', $user);
                    $this->session->set('db_pass', $pass);
                    print 'ok';
                }
        
                break;

            case 'install':

                try {
                    //db
                    $user = $_POST['db_user'];
                    $host = $_POST['db_host'];
                    $pass = $_POST['db_pass'];
                    $name = $_POST['db_name'];
                    if(!$this->canConnectToDatabase($host, $name, $user, $pass)) {
                        throw new Exception('Could not connect to database or database does not exist');
                    } else {
                        $this->session->set('db_host', $host);
                        $this->session->set('db_name', $name);
                        $this->session->set('db_user', $user);
                        $this->session->set('db_pass', $pass);
                    }

                    // admin config
                    $admin_email = $_POST['admin_email'];
                    $admin_pass = $_POST['admin_pass'];
                    $admin_name = $_POST['admin_name'];
                    if(!$this->isValidAdmin($admin_email, $admin_pass, $admin_name)) {
                        throw new Exception('Administrators account is not valid');
                    } else {
                        $this->session->set('admin_email', $admin_email);
                        $this->session->set('admin_pass', $admin_pass);
                        $this->session->set('admin_name', $admin_name);
                    }

                    $this->makeInstall($this->session);
                    $this->generateEmailTemplates();

                    session_destroy();

                    print 'ok';
                } catch(Exception $e) {
                    print $e->getMessage();
                }
                
                break;

            case 'index':
            default:
                $this->session->set('agree', true);

                $se = new Box_Requirements();
                $options = $se->getOptions();
                $vars = array(
                    'tos'                       => $this->getTOSInfo(),

                    'folders'                   => $se->folders(),
                    'files'                     => $se->files(),
                    'os'                        => PHP_OS,
                    'os_ok'                     => true,
                    'php_ver'                   => $options['php']['version'],
                    'php_ver_req'               => $options['php']['min_version'],
                    'php_safe_mode'             => $options['php']['safe_mode'],
                    'php_ver_ok'                => $se->isPhpVersionOk(),
                    'extensions'                => $se->extensions(),
                    'all_ok'                    => $se->canInstall(),
                    'db_host'                   => $this->session->get('db_host'),
                    'db_name'                   => $this->session->get('db_name'),
                    'db_user'                   => $this->session->get('db_user'),
                    'db_pass'                   => $this->session->get('db_pass'),

                    'admin_email'               => $this->session->get('admin_email'),
                    'admin_pass'                => $this->session->get('admin_pass'),
                    'admin_name'                => $this->session->get('admin_name'),

                    'license'                   => $this->session->get('license'),
                    'agree'                     => $this->session->get('agree'),

                    'install_module_path'       => SYSTEM_PATH_INSTALL,
                    'cron_path'                 => SYSTEM_PATH_CRON,
                    'config_file_path'          => SYSTEM_PATH_CONFIG,
                    'live_site'                 => SYSTEM_URL,
                    'admin_site'                => SYSTEM_URL_ADMIN,
                    
                    'domain'                    => pathinfo(SYSTEM_URL, PATHINFO_BASENAME),
                );

                print $this->render('install.phtml', $vars);
                break;
        }
    }
    
    private function render($name, $vars = array())
    {
        $options = array(
            'paths'             => array(SYSTEM_PATH_THEMES),
            'debug'             => TRUE,
            'charset'           => 'utf-8',
            'optimizations'     => 1,
            'autoescape'        => TRUE,
            'auto_reload'       => TRUE,
            'cache'             => FALSE,
        );

        $loader = new Twig_Loader_Filesystem($options['paths']);
        $twig = new Twig_Environment($loader, $options);
        $twig->addExtension(new Twig_Extension_Optimizer());
        $twig->addGlobal('request', $_REQUEST);
        $twig->addGlobal('version', Box_Version::VERSION);

        return $twig->render($name, $vars);
    }

    /**
     * @deprecated function should be removed
     * @return string
     */
    private function getLicense()
    {
        return '';
    }

    public function getTOSInfo() {
        return 'please visit https://ciclebilling.com to read our terms of service';
    }

    /**
     * Connect to database
     *
     * @param $host
     * @param $db
     * @param $user
     * @param $pass
     * @return bool
     * @throws Exception
     */
    protected function DBConnect($host, $db, $user, $pass) {
        try {
            $pdoConnectionString = 'mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8';

            $this->dbConnection = new PDO($pdoConnectionString, $user, $pass);

            $errorInfo = $this->dbConnection->errorInfo();
            if($errorInfo[1] !== null) {
                return false;
            }
        } catch (PDOException $PDOException) {
            //TODO need cleaner error handling
            throw $PDOException;
        } catch (Exception $exception) {
            //TODO need cleaner error handling
            throw $exception;
        }

        return true;
    }

    /**
     * Test database connection
     *
     * @param $host
     * @param $db
     * @param $user
     * @param $pass
     * @return bool
     */
    private function canConnectToDatabase($host, $db, $user, $pass)
    {
        $dbStatus = false;

        try {
            $dbStatus = $this->DBConnect($host, $db, $user, $pass);
        } catch (Exception $exception) {
            return false;
        }

        return $dbStatus;
    }

    private function isValidAdmin($email, $pass, $name)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if(empty($pass)) {
            return false;
        }

        if(empty($name)) {
            return false;
        }

        return true;
    }

    /**
     * @deprecated function should me removed
     * @param $license
     * @return bool
     */
    private function isValidLicense($license)
    {
        return true;
    }


    private function checkConfig()
    {
        if(file_exists(SYSTEM_PATH_CONFIG) === false) {
            throw new Exception('Create configuration file config.php with content provided during installation.');
        }
    }
    
    private function makeInstall($ns)
    {
        $this->_isValidInstallData($ns);
        $this->_createConfigurationFile($ns);

        if($this->canConnectToDatabase($ns->get('db_host'), $ns->get('db_name'), $ns->get('db_user'), $ns->get('db_pass')) === false) {
            throw new Exception('Can`t connect to database.');
        }

        $databaseStructure = file_get_contents(SYSTEM_PATH_SQL);
        if($databaseStructure === false) {
            throw new Exception('Could not read structure.sql file');
        }

        $databaseContent = file_get_contents(SYSTEM_PATH_SQL_DATA);
        if($databaseContent === false) {
            throw new Exception('Could not read structure.sql file');
        }

        $sql = $databaseStructure . $databaseContent;

        $sql = preg_split('/\;[\r]*\n/ism', $sql);
        $sql = array_map('trim', $sql);

        $this->dbConnection->beginTransaction();

        try {
            foreach ($sql as $query) {
                if (trim($query) === false) {
                    continue;
                }

                $installStatement = $this->dbConnection->prepare($query);
                $installStatement->execute();

                $errorInfo = $this->dbConnection->errorInfo();
                if($errorInfo[1] !== null) {
                    throw new PDOException('Query error (' . $errorInfo[1] . '): ' . $errorInfo[2]);
                }
            }

            $passwordObject = new \Box_Password();
            $sql = "INSERT INTO admin (role, name, email, pass, protected, created_at, updated_at) VALUES('admin', :name, :email, :password, 1, NOW(), NOW())";

            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array(
                ':name' => $ns->get('admin_name'),
                ':email' => $ns->get('admin_email'),
                ':password' => $passwordObject->hashIt($ns->get('admin_pass')),
            ));

            $errorInfo = $this->dbConnection->errorInfo();
            if($errorInfo[1] !== null) {
                throw new PDOException('Query error (' . $errorInfo[1] . '): ' . $errorInfo[2]);
            }
        } catch (PDOException $PDOException) {
            $this->dbConnection->rollBack();
        } catch (Exception $exception) {
            $this->dbConnection->rollBack();
        }

        $this->dbConnection->commit();

        try {
            $this->_sendMail($ns);
        } catch (Exception $e) {
            //TODO need cleaner error handling
            throw $e;
        }

        return true;
    }

    private function _sendMail($ns)
    {
        $admin_name = $ns->get('admin_name');
        $admin_email = $ns->get('admin_email');
        $admin_pass = $ns->get('admin_pass');

        $content = 'Hi $admin_name, ' . PHP_EOL;
        $content .= 'You have successfully setup CircleBilling at ' . SYSTEM_URL . PHP_EOL;
        $content .= 'Access client area at: ' . SYSTEM_URL . PHP_EOL;
        $content .= 'Access admin area at: ' . SYSTEM_URL_ADMIN . ' with login details:' . PHP_EOL;
        $content .= 'Email: ' . $admin_email . PHP_EOL;
        $content .= 'Password: ' . $admin_pass . PHP_EOL;

        $content .= PHP_EOL;

        $content .= 'Read CircleBilling documentation to get started http://docs.ciclebilling.com/' . PHP_EOL;
        $content .= 'Thank You for using CircleBilling.' .PHP_EOL;

        $subject = sprintf('CircleBilling is ready at "%s"', SYSTEM_URL);

        @mail($admin_email, $subject, $content);
    }

    private function _createConfigurationFile($data)
    {
        $output = $this->_getConfigOutput($data);
        if(!@file_put_contents(SYSTEM_PATH_CONFIG, $output)) {
            throw new Exception('Configuration file is not writable or does not exists. Please create file '. SYSTEM_PATH_CONFIG. ' and make it writable', 101);
        }
    }

    private function _getConfigOutput($ns)
    {
        $data = array(
            'debug'     => FALSE,
            'license'   => $ns->get('license'),
            'salt'      => md5(uniqid()),
            'url'       => SYSTEM_URL,
            'admin_area_prefix' =>  '/admin',
            'sef_urls'  => FALSE,
            'timezone'  => 'UTC',
            'locale'    => 'en_US',
            'locale_date_format'    => '%A, %d %B %G',
            'locale_time_format'    => ' %T',
            'path_data'    => SYSTEM_PATH_ROOT . '/data',
            'path_logs'    => SYSTEM_PATH_ROOT . '/data/log/application.log',

            'log_to_db'  => true,

            'db'    => array(
                'type'  =>  'mysql',
                'host'  =>  $ns->get('db_host'),
                'name'  =>  $ns->get('db_name'),
                'user'  =>  $ns->get('db_user'),
                'password'  =>  $ns->get('db_pass'),
            ),

            'twig'   =>  array(
                'debug'         =>  true,
                'auto_reload'   =>  true,
                'cache'         =>  SYSTEM_PATH_ROOT . '/data/cache',
            ),

            'api'   =>  array(
                'require_referrer_header'   =>  false,
                'allowed_ips'       =>  array(),
                'rate_span'         =>  60 * 60,
                'rate_limit'        =>  1000,
            ),
        );
        $output = '<?php '.PHP_EOL;
        $output .= 'return '.var_export($data, true).';';
        return $output;
    }

    private function _getConfigOutputOld($ns)
    {
        $cf = PHP_EOL."/* %s */".PHP_EOL;
        $bf = "define('%s', %s);".PHP_EOL;
        $f = "define('%s', '%s');".PHP_EOL;

        $output = '<?php '.PHP_EOL;
        $output .= sprintf($cf, 'CircleBilling Configuration File');
        $output .= sprintf($cf, 'More information on this file at http://docs.circlebilling.com/');

        $output .= sprintf($cf, 'Define timezone');
        $output .= sprintf("date_default_timezone_set('%s');", 'UTC');
        
        $output .= sprintf($cf, 'Set default date format');
        $output .= sprintf($f, 'SYSTEM_DATE_FORMAT', 'l, d F Y');

        $output .= sprintf($cf, 'Database');
        $output .= sprintf($f, 'SYSTEM_DB_NAME', $ns->get('db_name'));
        $output .= sprintf($f, 'SYSTEM_DB_USER', $ns->get('db_user'));
        $output .= sprintf($f, 'SYSTEM_DB_PASSWORD', $ns->get('db_pass'));
        $output .= sprintf($f, 'SYSTEM_DB_HOST', $ns->get('db_host'));
        $output .= sprintf($f, 'SYSTEM_DB_TYPE', 'mysql');

        $output .= sprintf($cf, 'Live site URL with trailing slash');
        $output .= sprintf($f, 'SYSTEM_URL', SYSTEM_URL);
        
        $output .= sprintf($cf, 'CircleBilling license key');
        $output .= sprintf($f, 'SYSTEM_LICENSE', $ns->get('license'));

        $output .= sprintf($cf, 'Enable or disable warning messages');
        $output .= sprintf($bf, 'SYSTEM_DEBUG', 'TRUE');
        
        $output .= sprintf($cf, 'Enable or disable pretty urls. Please configure .htaccess before enabling this feature.');
        $output .= sprintf($bf, 'SYSTEM_SEF_URLS', 'FALSE');
        
        $output .= sprintf($cf, 'Default application locale');
        $output .= sprintf($bf, 'SYSTEM_LOCALE', "'en_US'");
        
        $output .= sprintf($cf, 'Translatable locale format');
        $output .= sprintf($bf, 'SYSTEM_LOCALE_DATE_FORMAT', "'%A, %d %B %G'");
        
        $output .= sprintf($cf, 'Translatable time format');
        $output .= sprintf($bf, 'SYSTEM_LOCALE_TIME_FORMAT', "' %T'");
        
        $output .= sprintf($cf, 'Default location to store application data. Must be protected from public.');
        $output .= sprintf($bf, 'SYSTEM_PATH_DATA', "dirname(__FILE__) . '/data'");
        
        return $output;
    }

    private function _isValidInstallData($ns)
    {
        if(!$this->canConnectToDatabase($ns->get('db_host'), $ns->get('db_name'), $ns->get('db_user'), $ns->get('db_pass'))) {
            throw new Exception('Can not connect to database');
        }

        if(!$this->isValidAdmin($ns->get('admin_email'), $ns->get('admin_pass'), $ns->get('admin_name'))) {
            throw new Exception('Administrators account is not valid');
        }
    }

    private function generateEmailTemplates()
    {
        define('SYSTEM_PATH_MODS',      SYSTEM_PATH_ROOT . '/modules');

        $emailService = new \Box\Mod\Email\Service();
        $di = $di = include SYSTEM_PATH_ROOT  . '/di.php';
        $di['translate']();
        $emailService->setDi($di);
        return $emailService->templateBatchGenerate();
    }
}

$action = isset($_GET['a']) ? $_GET['a'] : 'index';
$installer = new Installer();
$installer->run($action);
