<?php
abstract class BBDatabaseTestCase extends PHPUnit\DbUnit\TestCase
{
    static private $pdo = null;
    private $conn = NULL;

    protected $_seedFilesPath = NULL;
    protected $_initialSeedFile = 'initial.xml';

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( 'mysql:dbname='.TEST_DB_NAME.';host=127.0.0.1', TEST_DB_USER, TEST_DB_PASSWORD );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, TEST_DB_NAME);
        }

        return $this->conn;
    }

    /**
     * Returns the seed files folder path
     *
     * @return string
     */
    public function getSeedFilesPath()
    {
        if ($this->_seedFilesPath == NULL) {
            $this->_seedFilesPath = TEST_PATH_TESTS.'/fixtures';
        }

        return rtrim($this->_seedFilesPath, '/') . '/';
    }

    /**
     * Retrieve from flat XML files data used to populate the database
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createFlatXmlDataSet($this->getSeedFilesPath() . $this->_initialSeedFile);
    }
}