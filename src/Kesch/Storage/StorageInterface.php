<?php
namespace Kesch\Storage;

interface StorageInterface
{
    public function load($key);

    public function save($key, $value, $ttl = null);
}
