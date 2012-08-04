<?php
namespace Kesch;

interface CacheInterface
{
    /**
     * @param string $key
     * @param callable $onSuccess
     * @return Result|mixed
     */
    public function load($key, $onSuccess = null);

    /**
     * @param string $key
     * @param integer|string|object|array|callable $value
     * @param callable $onSuccess
     * @return bool
     */
    public function save($key, $value, $onSuccess = null);
}
