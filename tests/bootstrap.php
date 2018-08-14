<?php
define('APPLICATION_ENV', 'testing');
define('TEST_PATH_TESTS', dirname(__FILE__));
require_once dirname(__FILE__) . '/../src/load.php';
$config = include dirname(__FILE__) . '/../src/config.php';

require_once dirname(__FILE__) . '/../src/vendor/autoload.php';
require_once dirname(__FILE__) . '/../src/rb.php';

define('TEST_DB_NAME', $config['db']['name']);
define('TEST_DB_USER', $config['db']['user']);
define('TEST_DB_PASSWORD', $config['db']['password']);
define('TEST_DB_HOST', $config['db']['host']);
define('TEST_DB_TYPE', $config['db']['type']);

// Add test libraries
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    TEST_PATH_TESTS . '/library',
    TEST_PATH_TESTS . '/includes',
    TEST_PATH_TESTS . '/includes/Vps',
)));

require_once 'SolusvmMock.php';
require_once 'BoxSessionMock.php';
require_once 'BBDatabaseTestCase.php';
require_once 'ApiTestCase.php';
require_once 'BBDbApiTestCase.php';
require_once 'BBModTestCase.php';
require_once 'BBTestCase.php';
require_once TEST_PATH_TESTS . '/includes/Payment/Adapter/Dummy.php';

$di = include SYSTEM_PATH_ROOT . '/di.php';

$di['translate']();

