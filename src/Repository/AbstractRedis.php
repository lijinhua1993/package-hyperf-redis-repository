<?php
declare(strict_types=1);

namespace Lijinhua\HyperfRedisRepository\Repository;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\RedisFactory;
use Psr\Container\ContainerInterface;

abstract class AbstractRedis
{
    protected string $prefix = 'rds';

    protected string $name = '';

    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var string
     */
    protected string $redisPoolName = 'default';

    /**
     * @param  \Psr\Container\ContainerInterface  $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->config = $container->get(ConfigInterface::class);

        $this->redisPoolName = $this->config->get('redis-repository.pool_name', 'default');
    }

    /**
     * 静态方法调用(获取子类实例)
     *
     * @return static
     */
    public static function getInstance()
    {
        return ApplicationContext::getContainer()->get(static::class);
    }

    /**
     * 获取 Redis 连接
     *
     * @return \Hyperf\Redis\RedisProxy
     */
    protected function redis()
    {
        return $this->container->get(RedisFactory::class)->get($this->redisPoolName);
    }

    /**
     * 获取缓存 KEY
     *
     * @param  string|array  $key
     * @return string
     */
    protected function getCacheKey($key = ''): string
    {
        $params = [$this->prefix, $this->name];
        if (is_array($key)) {
            $params = array_merge($params, $key);
        } else {
            $params[] = $key;
        }

        return $this->filter($params);
    }

    protected function filter(array $params = []): string
    {
        foreach ($params as $k => $param) {
            $params[$k] = trim((string) $param, ':');
        }

        return implode(':', array_filter($params));
    }
}
