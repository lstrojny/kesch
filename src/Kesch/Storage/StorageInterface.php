<?php
namespace Kesch\Storage;

interface StorageInterface
{
    public function isValidKey($key);

    public function isValidValue($value);

    public function load($key);

    public function save($key, $value);

    public function delete($key);
}
