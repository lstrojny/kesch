<?php
namespace Kesch;

use Kesch\Exception\CacheMissException;

class Result implements ResultInterface
{
    private $hit;

    private $key;

    private $value;

    /**
     * @param bool $hit
     * @param mixed $value
     */
    public function __construct($hit, $key, $value = null)
    {
        $this->hit = $hit;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * {@inheritdocs}
     */
    public function isHit()
    {
        return $this->hit;
    }

    /**
     * {@inheritdocs}
     */
    public function isMiss()
    {
        return !$this->hit;
    }

    /**
     * {@inheritdocs}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdocs}
     */
    public function getValue()
    {
        CacheMissException::assertIsHit($this->isHit(), $this->getKey(), __METHOD__);

        return $this->value;
    }
}