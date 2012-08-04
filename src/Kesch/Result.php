<?php
namespace Kesch;

class Result
{
    private $isHit;

    private $value;

    public function __construct($isHit, $value)
    {
        $this->isHit = $isHit;
        $this->value = $value;
    }

    public function isHit()
    {
        return $this->isHit;
    }

    public function getValue()
    {
        return $this->value;
    }
}