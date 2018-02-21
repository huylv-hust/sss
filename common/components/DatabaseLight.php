<?php
namespace common\components;

use Yii;

class DatabaseLight
{

    private $_dbh = null;
    private static $_instances = null;
    private $_name;

    private function __construct()
    {
    }

    /**
     * @return DatabaseLight
     */
    public static function factory($name = 'default')
    {
        $instance = new self();
        $instance->_name = $name;
        return $instance;
    }

    /**
     * @return DatabaseLight
     */
    public static function singleton($name = 'default')
    {
        if (is_array(self::$_instances) == false) {
            self::$_instances = array();
        }
        if (isset(self::$_instances[$name]) == false) {
            self::$_instances[$name] = self::factory($name);
        }
        return self::$_instances[$name];
    }

    private function _connect()
    {
        if ($this->_dbh == null) {
            try {
                $this->_dbh = new \PDO(
                        Yii::$app->db->dsn,
                        Yii::$app->db->username,
                        Yii::$app->db->password
                );
            } catch (\PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    public function getAll($sql, $values = array())
    {
        $sth = $this->prepare($sql);
        $this->execute($sth, $values);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRow($sql, $values = array())
    {
        $sth = $this->prepare($sql);
        $this->execute($sth, $values);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function getOne($sql, $values = array())
    {
        $sth = $this->prepare($sql);
        $this->execute($sth, $values);
        $array = $sth->fetch(\PDO::FETCH_NUM);
        return is_array($array) ? $array[0] : false;
    }

    public function prepare($sql)
    {
        $this->_connect();
        $sth = $this->_dbh->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        if ($sth == false) {
            $error = $sth->errorInfo();
            throw new \Exception(
            $error[2] . ':' . $sql
            );
        }
        return $sth;
    }

    public function execute($sth, $values = array())
    {
        $sth->closeCursor();

        if (is_array($values) === false) {
            $values = array($values);
        }

        foreach ($values as $key => $value) {
            if (is_string($key) && substr($key, 0, 1) !== ':') {
                unset($values[$key]);
                $values[':' . $key] = $value;
            }
        }

        if ($sth->execute($values) === false) {
            $error = $sth->errorInfo();
            throw new \Exception(
            $error[2] . ':' . $sth->queryString
            );
        }

        return $sth->rowCount();
    }

    public function begin()
    {
        $this->_connect();
        $this->_dbh->beginTransaction();
    }

    public function commit()
    {
        $this->_dbh->commit();
    }

    public function rollback()
    {
        $this->_dbh->rollback();
    }

    public function exec($sql, $values = array())
    {
        $sth = $this->prepare($sql);
        return $this->execute($sth, $values);
    }

    public function close()
    {
        $this->_dbh = null;
    }

}
