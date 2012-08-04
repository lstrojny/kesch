<?php
namespace Kesch;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Kesch\Storage\StorageInterface
     */
    private $storage;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var bool
     */
    private $success = false;

    public function setUp()
    {
        $this->storage = $this
            ->getMockBuilder('\Kesch\Storage\StorageInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cache = new Cache($this->storage);
    }

    public function testLoadingSingleKey()
    {
        $this->storage
            ->expects($this->once())
            ->method('load')
            ->with('key')
            ->will($this->returnValue(new Result(true, 'foo')));

        $result = $this->cache->load('key');
        $this->onSuccess($result);
    }

    public function testLoadingSingleKeyAndPassingCallback()
    {
        $this->storage
            ->expects($this->once())
            ->method('load')
            ->with('key')
            ->will($this->returnValue(new Result(true, 'foo')));

        $this->assertSame('onSuccess', $this->cache->load('key', array($this, 'onSuccess')));
        $this->assertTrue($this->success);
    }

    public function testPassingInvalidCallable()
    {
        $this->setExpectedException(
            'Kesch\Exception\InvalidArgumentException',
            'Kesch\Cache::load() expected parameter 2 to be valid callback, string given'
        );

        $this->cache->load('key', 'notCallable');
    }

    public function testSavingSingleKey()
    {
        $this->storage
            ->expects($this->once())
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', 'value'));
    }

    public function testSavingSingleKeyByCallable()
    {
        $this->storage
            ->expects($this->once())
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', array($this, 'getValue')));
    }

    public function testSavingSingleKeyWithSpecificLifetime()
    {
        $this->storage
            ->expects($this->once())
            ->method('save')
            ->with('key', 'value', 100)
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', 'value', 100));
    }

    public function testSavingSingleKeyByCallableWithSpecificLifetime()
    {
        $this->storage
            ->expects($this->once())
            ->method('save')
            ->with('key', 'value', 100)
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', array($this, 'getValue'), 100));
    }

    public function onSuccess(Result $result)
    {
        $this->assertTrue($result->isHit());
        $this->assertSame('foo', $result->getValue());
        $this->success = true;

        return 'onSuccess';
    }

    public function getValue($key)
    {
        $this->assertSame('key', $key);
        return 'value';
    }
}
