<?php
namespace Kesch;

interface ResultInterface
{
    /**
     * Returns true if the result is a cache hit
     *
     * @return mixed
     */
    public function isHit();

    /**
     * Returns true if the result is a cache miss
     *
     * @return bool
     */
    public function isMiss();

    /**
     * Returns the cached value or throws an exception if it is a miss
     *
     * @throws \Kesch\Exception\CacheMissException
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the key of the cache result
     *
     * @return string
     */
    public function getKey();
}