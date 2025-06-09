<?php
App::uses('DboSource', 'Model/Datasource');
App::uses('AppUtil', 'Lib');  // ✅ Importante

App::uses('CakeSession', 'Model/Datasource'); // Por si no lo tenías

if (!class_exists('AppUtil')) {
    require_once APP . 'Lib' . DS . 'AppUtil.php';
}


/**
 * Driver Oracle compatible con PHP 8.1+ y CakePHP 2.x
 */
class Oracle extends DboSource
{
    protected $_transactionNesting = 0;
    protected $_transactionStarted = false;
    protected $doRollback = false;

    public function log($msg, $type = LOG_ERR, $scope = null)
    {
        $user = CakeSession::read('app.auth.usuario') ?? '(no-user)';
        return parent::log($user . '#' . $msg, $type);
    }

    protected function _execute($sql, $params = [], $prepareOptions = [])
    {
        $this->log($sql, 'sql_info');
        $res = parent::_execute($sql);

        if (!$res) {
            $this->log('Error: ' . $this->lastError(), 'error');
            $this->log(AppUtil::printStackTrace(), 'error_trace');
            $this->log('Error al ejecutar la SQL [' . $sql . '] => ' . $this->lastError(), 'sql_error');
        }

        return $res;
    }

    public function nestedTransactionSupported(): bool
    {
        return false;
    }

    public function begin(): bool
    {
        if ($this->_transactionNesting === 0) {
            $this->doRollback = false;
            if (!parent::begin()) {
                return false;
            }
        }
        $this->_transactionNesting++;
        $this->log('BEGIN (' . $this->_transactionNesting . ')', 'debug');
        return true;
    }

    public function rollback(): bool
    {
        $this->log('ROLLBACK (' . $this->_transactionNesting . ')', 'debug');
        $this->_transactionNesting--;
        $this->doRollback = true;
        return $this->_commitOrRollback();
    }

    public function commit(): bool
    {
        $this->log('COMMIT (' . $this->_transactionNesting . ')', 'debug');
        $this->_transactionNesting--;
        return $this->_commitOrRollback();
    }

    protected function _commitOrRollback(): bool
    {
        if ($this->_transactionNesting === 0) {
            $this->_transactionStarted = false;
            if ($this->doRollback) {
                $this->log('Haciendo rollback', 'debug');
                return parent::rollback();
            } else {
                $this->log('Haciendo commit', 'debug');
                return parent::commit();
            }
        }
        return true;
    }
}
