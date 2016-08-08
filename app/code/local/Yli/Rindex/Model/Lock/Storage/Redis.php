<?php 

class Yli_Rindex_Model_Lock_Storage_Redis implements Mage_Index_Model_Lock_Storage_Interface
{
    const LOCK_VALUE = 1;
    /**
     * @var Credis_Client
     */
    protected $_redis;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var $resource Mage_Core_Model_Resource */
        $db = Mage::getStoreConfig('system/redis/index_lock_db');
        $this->_redis = Mage::helper('redis')->init($db);
    }

    protected function _prepareLockName($name)
    {
        return $name;
    }

    /**
     * Set named lock
     *
     * @param string $lockName
     * @return int
     */
    public function setLock($lockName)
    {
        $lockName = $this->_prepareLockName($lockName);
        $ttl = Mage::getStoreConfig('system/redis/index_lock_ttl');
        return $this->_redis->set($lockName, self::LOCK_VALUE,array('nx', 'ex' => $ttl));
    }

    /**
     * Release named lock
     *
     * @param string $lockName
     * @return int|null
     */
    public function releaseLock($lockName)
    {
        $lockName = $this->_prepareLockName($lockName);
        return $this->_redis->del($lockName);
    }

    /**
     * Check whether the lock exists
     *
     * @param string $lockName
     * @return bool
     */
    public function isLockExists($lockName)
    {
        $lockName = $this->_prepareLockName($lockName);
        return $this->_redis->get($lockName);
    }
    
}