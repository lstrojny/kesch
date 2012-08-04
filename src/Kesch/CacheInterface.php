<?php
namespace Kesch;

interface CacheInterface
{
    public function get($key, $onSuccess = null, $onFailure = null);

    public function save($key, $value, $ttl = null);
}
