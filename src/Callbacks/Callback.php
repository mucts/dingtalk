<?php


namespace MuCTS\DingTalk\Callbacks;


use MuCTS\DingTalk\Callbacks\Cryptos\DingCallbackCrypto;
use MuCTS\DingTalk\Callbacks\Cryptos\ErrorCode;
use MuCTS\DingTalk\Exceptions\ConfigException;
use MuCTS\DingTalk\Exceptions\Exception;

class Callback
{
    protected static $configs;
    protected        $connection;
    protected        $cryptos = [];

    public function __construct(?string $connection = null, ?array $configs = null)
    {
        $this->connection = $connection;
        if ($configs) self::$configs = $configs;
        if (!self::$configs) self::$configs = require dirname(__DIR__) . '/../config/dingtalk.php';
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
        if (!array_key_exists('callback_aes_key', $config)) throw new ConfigException('callback_aes_key 不能为空', ErrorCode::$IllegalAesKey);
        if (!array_key_exists('callback_token', $config)) throw new ConfigException('callback_token 不能为空', ErrorCode::$IllegalAesKey);
        return $config;
    }

    /**
     *
     * @return DingCallbackCrypto
     * @throws ConfigException
     */
    private function getCrypto(): DingCallbackCrypto
    {
        $connection = $this->connection ?: $this->getDefaultConnection();
        if (!array_key_exists($connection, $this->cryptos)) {
            $config                     = $this->getConfig($connection);
            $this->cryptos[$connection] = new DingCallbackCrypto($config['callback_token'], $config['callback_aes_key'], $config['app_key']);
        }
        return $this->cryptos[$connection];
    }

    /**
     * 解密消息内容
     *
     * @param string $signature
     * @param string $nonce
     * @param string $encrypt
     * @param int|null $timeStamp
     * @return array
     * @throws ConfigException
     * @throws Exception
     */
    public function decrypt(string $signature, string $nonce, string $encrypt, ?int $timeStamp = null):array
    {
        $message = $this->getCrypto()->getDecryptMsg($signature, $nonce, $encrypt, $timeStamp);
        $message = json_decode($message, true);
        return $message;
    }

    /**
     * 加密消息内容
     *
     * @param string $message
     * @return false|string
     * @throws ConfigException
     * @throws Exception
     */
    public function encrypt(string $message)
    {
        return $this->getCrypto()->getEncryptedMap($message);
    }
}