# A code kata to play around with cache interfaces

# Ideas
 - Sensible result handling (differentiating null from not found)
 - Tagging with expiration
 - Use Memcache, Redis, etc. protocols as heavily as possible
 - CSL: caching specific language: store, load, expire, write through, write back, hit, miss
 - Hashing strategies with mutiple storage backends
 - Check and set (Memcache)
 - Load/Save many
 - Storage directors:
   - Size based
   - Hash based
   - Value/key based


### Passing a success callback to Cache::load()

```php
<?php
$cache->load('key', function(Kesch\Result $result) {
    if ($result->isHit()) {

    }
});
```

### Returning a Result object

```php
<?php
$result = $cache->load('key');
if ($result->isMiss()) {
    // Regenerate
}
```

### Simply storing a value
```php
<?php
$cache->store('key', 'value');
```


### Storing a value by callback
```php
<?php
$cache->store('key', function() {

});
```

### Storing a value with a success callback for write-through caches
```php
<?php
$cache->store('key', 'value', function($key, $value) {
    // Update another storage
});
```

### Store key along with some tags
```php
<?php
$cache->store(new Kesch\Key('test', array('tag1', 'tag2')), 'value');
```

### Delete values with tags
```php
<?php
$cache->delete(new Kesch\Tags(array('tag1', 'tag2')));
```