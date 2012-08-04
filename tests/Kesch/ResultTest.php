<?php
namespace Kesch;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Result
     */
    private $missResult;

    /**
     * @var Result
     */
    private $hitResult;

    public function setUp()
    {
        $this->missResult = new Result(false, 'key');
        $this->hitResult = new Result(true, 'key', 'value');
    }

    public function testMissIsser()
    {
        $this->assertTrue($this->missResult->isMiss());
        $this->assertFalse($this->missResult->isHit());
    }

    public function testHitIsser()
    {
        $this->assertFalse($this->hitResult->isMiss());
        $this->assertTrue($this->hitResult->isHit());
    }

    public function testGetHitValueAndKey()
    {
        $this->assertSame('key', $this->hitResult->getKey());
        $this->assertSame('value', $this->hitResult->getValue());
    }

    public function testGetMissValueThrowsException()
    {
        $this->assertSame('key', $this->missResult->getKey());

        $this->setExpectedException(
            'Kesch\Exception\CacheMissException',
            'Kesch\Result::getValue() expected "key" to result in a cache hit, miss occured'
        );
        $this->missResult->getValue();
    }
}