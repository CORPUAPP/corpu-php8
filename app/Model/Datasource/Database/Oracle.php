<?php
App::uses('DboSource', 'Model/Datasource');

class Oracle extends DboSource
{
    protected $_transactionStarted = false;
    protected $_connection;
    protected $_statementId;
    protected $_lastQuery;
    protected $_lastError;
    protected $_connected = false;

    public function connect()
    {
        if ($this->connected) {
            return true;
        }

        $config = $this->config;
        $charset = isset($config['charset']) ? $config['charset'] : null;

        $this->_connection = oci_connect(
            $config['login'],
            $config['password'],
            $config['database'],
            $charset
        );

        if (!$this->_connection) {
            $this->_lastError = oci_error();
            $this->connected = false;
            return false;
        }

        $this->connected = true;
        return true;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function disconnect()
    {
        if ($this->_connection) {
            oci_close($this->_connection);
        }
        $this->connected = false;
        return true;
    }

    public function lastError($query = null)
    {
        $e = $query ? oci_error($query) : oci_error($this->_connection);
        return isset($e['message']) ? $e['message'] : null;
    }

    public function execute($sql, $params = array(), $options = array())
    {
        $this->_statementId = oci_parse($this->_connection, $sql);
        $this->_lastQuery = $sql;

        if (!$this->_statementId) {
            $this->_lastError = oci_error($this->_connection);
            return false;
        }

        $result = oci_execute($this->_statementId);
        if (!$result) {
            $this->_lastError = oci_error($this->_statementId);
            return false;
        }

        return $this->_statementId;
    }

    public function fetchRow($statementId = null)
    {
        if (!$statementId) {
            $statementId = $this->_statementId;
        }
        return oci_fetch_assoc($statementId);
    }

    public function fetchAll($sql, $params = array(), $model = null)
    {
        $stmt = $this->execute($sql, $params);
        if (!$stmt) {
            return false;
        }

        $results = [];
        while ($row = $this->fetchRow($stmt)) {
            $results[] = $row;
        }
        return $results;
    }

    public function begin()
    {
        $this->_transactionStarted = true;
        return true;
    }

    public function commit()
    {
        if ($this->_transactionStarted) {
            $result = oci_commit($this->_connection);
            $this->_transactionStarted = false;
            return $result;
        }
        return false;
    }

    public function rollback()
    {
        if ($this->_transactionStarted) {
            $result = oci_rollback($this->_connection);
            $this->_transactionStarted = false;
            return $result;
        }
        return false;
    }

    public function describe($model)
    {
        // Implementar si necesitas introspección de tablas
        return array();
    }

    public function value($data, $column = null, $safe = false)
    {
        // Escapar si hace falta
        return is_string($data) ? "'" . addslashes($data) . "'" : $data;
    }

    public function insertId($source = null)
    {
        return null; // Oracle usa secuencias, no auto_increment
    }

    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
}
