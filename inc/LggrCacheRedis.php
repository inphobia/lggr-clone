<?php
namespace Lggr;

/**
 * @brief Caching class for redis based cache.
 */
class LggrCacheRedis extends AbstractLggrCache
{

    // 5 minutes
    const MAXAGE = 300;

    const REDISHOST = 'redis';

    const REDISDB = 0;

    const REDISPFX = 'lggr_';

    private $r = null;

    private $host = self::REDISHOST;

    public function __construct($oConfig = NULL)
    {
        if (NULL != $oConfig) {
            $this->host = $oConfig->getCacheHost();
        }
        $this->r = new \Redis();
        $this->r->connect($this->host);
        $this->r->select(self::REDISDB);
    }

    // constructor
    public function __destruct()
    {
        $this->r->close();
    }

    // destructor
    public function store($key, $value)
    {
        $s = serialize($value);
        $this->r->setex(SELF::REDISPFX . $key, self::MAXAGE, $s);
    }

    // function
    public function retrieve($key)
    {
        $value = $this->r->get(SELF::REDISPFX . $key);
        if (false === $value) {
            return null;
        }
        return unserialize($value);
    }

    // function
    public function purge($key)
    {
        $this->r->delete(SELF::REDISPFX . $key);
    } // function
} // class
