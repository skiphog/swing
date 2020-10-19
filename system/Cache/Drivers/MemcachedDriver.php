<?php

namespace System\Cache\Drivers;

use Memcached;
use System\Cache\Interfaces\CacheDriverInterface;

/**
 * Class MemcachedDriver
 *
 * @package System
 */
class MemcachedDriver implements CacheDriverInterface
{
    /**
     * @var Memcached
     */
    protected $memcached;

    /**
     * MemcachedDriver constructor.
     */
    public function __construct()
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer('127.0.0.1', 11211);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->memcached->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $time
     *
     * @return bool
     */
    public function set(string $key, $value, int $time): bool
    {
        return $this->memcached->set($key, $value, $time);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        return $this->memcached->delete($key);
    }

    public function getMemcached(): Memcached
    {
        return $this->memcached;
    }
}
