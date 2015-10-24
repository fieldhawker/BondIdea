<?php

class IdeasRepositoryTest extends PHPUnit_Extensions_Database_TestCase
{
    static private $pdo  = null;
    private        $conn = null;
    private        $obj;

    const ERR_MSG_NOT_INPUT_KEYWORD = '名前を入力してください';
    const ERR_MSG_NOT_RANGE_KEYWORD = 'キーワードは1 ～ 255 文字以内で入力してください';

    public function __construct()
    {

        $data_set = $this->createXMLDataSet(dirname(__FILE__) . '/../data/import_ideas.xml');

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($data_set);
        $this->getDatabaseTester()->onSetUp($data_set);

        $this->obj = new IdeasRepository(self::$pdo);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function makeRandStr($length = 8, $type = 1)
    {
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        static $chars2 = 'あいうえお';

        $targets = ($type == 1) ? $chars : $chars2;

        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= $targets[mt_rand(0, mb_strlen($targets) - 1)];
        }

        return $str;
    }

    /**
     * @return null|PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    final public function getConnection()
    {

        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                  $GLOBALS['DB_DSN'],
                  $GLOBALS['DB_USER'],
                  $GLOBALS['DB_PASSWD']);
            }
            $this->conn
              = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_ArrayDataSet
     */
    public function getDataSet()
    {
        $data = array();

        return $this->createArrayDataSet($data);
    }

    /**
     * @throws Exception
     * @throws MySQLException
     */
    public function testIdeasInsert()
    {
        $res = $this->obj->insert('');
        $this->assertTrue($res);

        $res = $this->obj->insert('keyword');
        $this->assertTrue($res);

    }

    /**
     * @expectedException        Exception
     */
    public function testIdeasInsertException()
    {
        $res = $this->obj->insert(null);
    }

    /**
     *
     */
    public function testKeywordSet()
    {
        $res = $this->obj->validInsert('');
        $this->assertContains(self::ERR_MSG_NOT_INPUT_KEYWORD, $res);

        $res = $this->obj->validInsert('keyword');
        $this->assertTrue(empty($res));
    }

    /**
     *
     */
    public function testKeywordRange()
    {
        $res = $this->obj->validInsert('');
        $this->assertContains(self::ERR_MSG_NOT_RANGE_KEYWORD, $res);

        $res = $this->obj->validInsert('k');
        $this->assertTrue(empty($res));

        $res = $this->obj->validInsert($this->makeRandStr(254));
        $this->assertTrue(empty($res));

        $res = $this->obj->validInsert($this->makeRandStr(255));
        $this->assertContains(self::ERR_MSG_NOT_RANGE_KEYWORD, $res);

        $res = $this->obj->validInsert($this->makeRandStr(254, 2));
        $this->assertTrue(empty($res));

        $res = $this->obj->validInsert($this->makeRandStr(255, 2));
        $this->assertContains(self::ERR_MSG_NOT_RANGE_KEYWORD, $res);
    }

}