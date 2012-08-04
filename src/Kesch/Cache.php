<?php
namespace Kesch;

use Kesch\Exception\InvalidCallbackException;
use Kesch\Exception\InvalidKeyException;
use Kesch\Storage\StorageInterface;
use Exception;

class Cache implements CacheInterface
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function load($key, $onSuccess = null)
    {
        InvalidKeyException::assertValidKeyForStorage($this->storage, $key, __METHOD__, 1);
        $result = $this->storage->load($key);

        if ($onSuccess !== null) {
            InvalidCallbackException::assertValidCallback($onSuccess, __METHOD__, 2);
            return call_user_func($onSuccess, $result);
        }
        return $result;
    }

    public function save($key, $value, $onSuccess = null)
    {
        InvalidKeyException::assertValidKeyForStorage($this->storage, $key, __METHOD__, 1);

        if (is_callable($value)) {
            $value = call_user_func($value, $key);
        }

        $result = $this->storage->save($key, $value);

        if ($result && $onSuccess !== null) {
            InvalidCallbackException::assertValidCallback($onSuccess, __METHOD__, 3);

            try {
                return call_user_func($onSuccess, $key, $value);
            } catch (Exception $e) {
                $this->storage->delete($key);
                return false;
            }
        }

        return $result;
    }
}
