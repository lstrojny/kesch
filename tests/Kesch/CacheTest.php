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
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('load')
            ->with('key')
            ->will($this->returnValue(new Result(true, 'key', 'cached value')));

        $result = $this->cache->load('key');
        $this->onSuccess($result);
    }

    public function testLoadingSingleKeyAndPassingCallback()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('load')
            ->with('key')
            ->will($this->returnValue(new Result(true, 'key', 'cached value')));

        $this->assertSame('onSuccess', $this->cache->load('key', array($this, 'onSuccess')));
        $this->assertTrue($this->success);
    }

    public function testPassingInvalidCallable()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->setExpectedException(
            'Kesch\Exception\InvalidCallbackException',
            'Kesch\Cache::load() expected parameter 2 to be valid callback, string given'
        );

        $this->cache->load('key', 'notCallable');
    }

    public function testPassingInvalidKey()
    {
        $this->setExpectedException(
            'Kesch\Exception\InvalidKeyException',
            sprintf('Kesch\Cache::load() expected parameter 1 to be a valid key for %s, "key" given', get_class($this->storage))
        );
        $this->storage
            ->expects($this->once())
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(false));
        $this->cache->load('key');
    }

    public function testSavingSingleKey()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', 'value'));
    }

    public function testSavingSingleKeyByCallable()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));

        $this->assertTrue($this->cache->save('key', array($this, 'getValue')));
    }

    public function testSavingSingleKeyAndCallingSuccessCallback()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));

        $this->assertSame('saveSuccess', $this->cache->save('key', 'value', array($this, 'onSaveSuccess')));
    }

    public function testSavingSingleKeyWithoutSuccessDoesNotCallSuccessCallback()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(false));

        $this->assertFalse($this->cache->save('key', 'value', array($this, 'onSaveSuccess')));
    }

    public function testSavingSingleKeyAndPassingInvalidSuccessCallback()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));
        $this->setExpectedException(
            'Kesch\Exception\InvalidCallbackException',
            'Kesch\Cache::save() expected parameter 3 to be valid callback, string given'
        );

        $this->cache->save('key', 'value', 'invalidCallback');
    }

    public function testSavingSingleKeyAndThrowingExceptionInSuccessCallbackDeletesPreviouslySavedKey()
    {
        $this->storage
            ->expects($this->at(0))
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(1))
            ->method('save')
            ->with('key', 'value')
            ->will($this->returnValue(true));
        $this->storage
            ->expects($this->at(2))
            ->method('delete')
            ->with('key')
            ->will($this->returnValue(true));

        $result = $this->cache->save('key', 'value', function() {
            throw new \Exception('Exception in success callback');
        });
        $this->assertFalse($result);
    }

    public function testSavingKeysAsksForKeyValidityFirst()
    {
        $this->setExpectedException(
            'Kesch\Exception\InvalidKeyException',
            sprintf('Kesch\Cache::save() expected parameter 1 to be a valid key for %s, "key" given', get_class($this->storage))
        );
        $this->storage
            ->expects($this->once())
            ->method('isValidKey')
            ->with('key')
            ->will($this->returnValue(false));
        $this->cache->save('key', 'value');
    }

    public function onSuccess(Result $result)
    {
        $this->assertTrue($result->isHit());
        $this->assertSame('cached value', $result->getValue());
        $this->success = true;

        return 'onSuccess';
    }

    public function onSaveSuccess($key, $value)
    {
        $this->assertSame('key', $key);
        $this->assertSame('value', $value);

        return 'saveSuccess';
    }

    public function getValue($key)
    {
        $this->assertSame('key', $key);
        return 'value';
    }
}
