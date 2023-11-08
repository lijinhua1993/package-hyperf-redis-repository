# hyperf-redis-repository

适用于hyperf的redis存储库管理,统一分类存储redis

## 安装

```shell script
composer require lijinhua/hyperf-redis-repository
```

## 使用

```php
<?php

namespace App\Cache;

use Lijinhua\HyperfRedisRepository\Repository\HashRedis;

class UserCache extends HashRedis
{
    public string $name = 'user';
}

```

```php

\App\Cache\UserCache::getInstance()->add('uid-1000',json_encode(['age'=>20,'sex'=>'man']));

\App\Cache\UserCache::getInstance()->get('uid-1000');

\App\Cache\UserCache::getInstance()->rem('uid-1000');

\App\Cache\UserCache::getInstance()->delete();
```

