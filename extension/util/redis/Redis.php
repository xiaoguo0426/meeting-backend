<?php


namespace util\redis;


use think\facade\Config;

class Redis
{
    /**
     * @var \Redis
     */
    protected \Redis $connection;

    /**
     * @var array
     */
    protected array $config = [
        'host' => 'localhost',
        'port' => 6379,
        'auth' => null,
        'db' => 0,
        'timeout' => 0.0,
        'cluster' => [
            'enable' => false,
            'name' => null,
            'seeds' => [],
            'read_timeout' => 0.0,
            'persistent' => false,
        ],
        'sentinel' => [
            'enable' => false,
            'master_name' => '',
            'nodes' => [],
            'persistent' => '',
            'read_timeout' => 0,
        ],
        'options' => [],
    ];

    /**
     * Current redis database.
     * @var null|int
     */
    protected ?int $database;

    /**
     * @throws ConnectionException
     */
    public function __construct(string $pool = 'default')
    {
        $config = Config::get('redis.' . $pool);

        $this->config = array_replace_recursive($this->config, $config);
        var_dump($this->config);
//        $this->reconnect();
    }

    /**
     * @throws \Throwable
     */
    public function __call($name, $arguments)
    {
        try {
            $result = $this->connection->{$name}(...$arguments);
        } catch (\Throwable $exception) {
            $result = $this->retry($name, $arguments, $exception);
        }

        return $result;
    }

    /**
     * @throws ConnectionException
     */
    public function reconnect(): bool
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $auth = $this->config['auth'];
        $db = $this->config['db'];
        $timeout = $this->config['timeout'];
        $cluster = $this->config['cluster']['enable'] ?? false;
        $sentinel = $this->config['sentinel']['enable'] ?? false;

        $redis = null;
        switch (true) {
            case $cluster:
                $redis = $this->createRedisCluster();
                break;
            case $sentinel:
                $redis = $this->createRedisSentinel();
                break;
            default:
                $redis = $this->createRedis($host, $port, $timeout);
                break;
        }

        $options = $this->config['options'] ?? [];

        foreach ($options as $name => $value) {
            // The name is int, value is string.
            $redis->setOption($name, $value);
        }

        if ($redis instanceof \Redis && isset($auth) && $auth !== '') {
            $redis->auth($auth);
        }

        $database = $this->database ?? $db;
        if ($database > 0) {
            $redis->select($database);
        }

        $this->connection = $redis;

        return true;
    }

    public function close(): bool
    {
        unset($this->connection);

        return true;
    }

    public function release(): void
    {
        if ($this->database && $this->database != $this->config['db']) {
            // Select the origin db after execute select.
            $this->connection->select($this->config['db']);
            $this->database = null;
        }

        $this->connection->close();
    }

    public function setDatabase(?int $database): void
    {
        $this->database = $database;
    }

    /**
     * @throws ConnectionException
     */
    protected function createRedisCluster(): \RedisCluster
    {
        try {
            $paramaters = [];
            $paramaters[] = $this->config['cluster']['name'] ?? null;
            $paramaters[] = $this->config['cluster']['seeds'] ?? [];
            $paramaters[] = $this->config['timeout'] ?? 0.0;
            $paramaters[] = $this->config['cluster']['read_timeout'] ?? 0.0;
            $paramaters[] = $this->config['cluster']['persistent'] ?? false;
            if (isset($this->config['auth'])) {
                $paramaters[] = $this->config['auth'];
            }

            $redis = new \RedisCluster(...$paramaters);
        } catch (\Throwable $e) {
            throw new ConnectionException('Connection reconnect failed ' . $e->getMessage());
        }

        return $redis;
    }

    /**
     * @throws \Throwable
     */
    protected function retry($name, $arguments, \Throwable $exception)
    {

        try {
            $this->reconnect();
            $result = $this->connection->{$name}(...$arguments);
        } catch (\Throwable $exception) {
            throw $exception;
        }

        return $result;
    }

    /**
     * @throws ConnectionException
     */
    protected function createRedisSentinel(): \Redis
    {
        try {
            $nodes = $this->config['sentinel']['nodes'] ?? [];
            $timeout = $this->config['timeout'] ?? 0;
            $persistent = $this->config['sentinel']['persistent'] ?? null;
            $retryInterval = $this->config['retry_interval'] ?? 0;
            $readTimeout = $this->config['sentinel']['read_timeout'] ?? 0;
            $masterName = $this->config['sentinel']['master_name'] ?? '';

            $host = '';
            $port = 0;
            foreach ($nodes as $node) {
                [$sentinelHost, $sentinelPort] = explode(':', $node);
                $sentinel = new \RedisSentinel(
                    $sentinelHost,
                    intval($sentinelPort),
                    $timeout,
                    $persistent,
                    $retryInterval,
                    $readTimeout
                );
                $masterInfo = $sentinel->getMasterAddrByName($masterName);
                if (is_array($masterInfo) && count($masterInfo) >= 2) {
                    [$host, $port] = $masterInfo;
                    break;
                }
            }
            $redis = $this->createRedis($host, $port, $timeout);
        } catch (\Throwable $e) {
            throw new ConnectionException('Connection reconnect failed ' . $e->getMessage());
        }

        return $redis;
    }

    /**
     * @param string $host
     * @param int $port
     * @param float $timeout
     * @throws ConnectionException
     * @return \Redis
     */
    protected function createRedis($host, $port, $timeout)
    {
        $redis = new \Redis();
        if (! $redis->connect((string) $host, (int) $port, $timeout)) {
            throw new ConnectionException('Connection reconnect failed.');
        }
        return $redis;
    }
}