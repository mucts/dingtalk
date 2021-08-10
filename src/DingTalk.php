<?php


namespace MuCTS\DingTalk;


use MuCTS\DingTalk\Callbacks\Cryptos\ErrorCode;
use MuCTS\DingTalk\Contracts\AccessTokenRequest;
use MuCTS\DingTalk\Contracts\Cache;
use MuCTS\DingTalk\Contracts\Request;
use MuCTS\DingTalk\Exceptions\AccessTokenException;
use MuCTS\DingTalk\Exceptions\ConfigException;
use MuCTS\DingTalk\OApi\Token;

class DingTalk
{
    protected static $configs;
    protected        $connection;
    protected        $cache;
    protected        $accessTokens = [];

    public function __construct(?string $connection = null, ?array $configs = null, ?Cache $cache = null)
    {
        $this->connection = $connection;
        $this->cache      = $cache;
        if ($configs) self::$configs = $configs;
        if (!self::$configs) self::$configs = require dirname(__DIR__) . '/config/dingtalk.php';
    }

    public function connection(?string $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    protected function getDefaultConnection(): ?string
    {
        return data_get(self::$configs, 'default');
    }

    /**
     * 获取配置信息
     *
     * @param string $connection
     * @return array
     * @throws ConfigException
     */
    protected function getConfig(string $connection): array
    {
        $config = data_get(self::$configs, 'connections.' . $connection, []);
        if (!is_array($config) || empty($config)) throw new ConfigException('配置信息不存在', ErrorCode::$IllegalAesKey);
        if (!array_key_exists('app_key', $config)) throw new ConfigException('app key不能为空', ErrorCode::$IllegalAesKey);
        if (!array_key_exists('app_secret', $config)) throw new ConfigException('app_secret 不能为空', ErrorCode::$IllegalAesKey);
        return $config;
    }

    /**
     * 获取access token
     *
     * @param false $reset
     * @return string|null
     * @throws AccessTokenException
     * @throws ConfigException
     * @throws Exceptions\DingTalkException
     * @throws Exceptions\ResponseException
     */
    private function getAccessToken($reset = false): ?string
    {
        $connection = $this->connection ?: $this->getDefaultConnection();
        if ($reset || !array_key_exists($connection, $this->accessTokens)) {
            $config                          = $this->getConfig($connection);
            $this->accessTokens[$connection] = (new Token($config['app_key'], $config['app_secret'], $this->cache, $reset))->request();
        }
        return $this->accessTokens[$connection];
    }

    /**
     * 接口请求
     *
     * @param Request $request
     * @param bool $reset
     * @return mixed
     * @throws AccessTokenException
     * @throws ConfigException
     * @throws Exceptions\DingTalkException
     * @throws Exceptions\ResponseException
     */
    public function request(Request $request, bool $reset = false)
    {
        if ($request instanceof AccessTokenRequest) {
            $accessToken = $this->getAccessToken($reset);
            $request->setAccessToken($accessToken);
        }
        try {
            return $request->request();
        } catch (AccessTokenException $e) {
            if (!$reset) {
                return $this->request($request, true);
            }
            throw $e;
        }
    }
}