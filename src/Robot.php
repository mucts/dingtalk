<?php


namespace MuCTS\DingTalk;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\StreamInterface;
use MuCTS\DingTalk\Contracts\Message;
use MuCTS\DingTalk\Exceptions\ConfigException;
use MuCTS\DingTalk\Exceptions\DingTalkException;
use MuCTS\DingTalk\Exceptions\ResponseException;

class Robot
{
    private $configs;
    private $connection;
    private $timeout  = 5;
    private $atMobile = [];
    private $atAll    = true;

    public function __construct(?array $configs = null)
    {
        $configs       = $configs ?: require_once dirname(__DIR__) . '/config/robot.php';
        $this->configs = $configs;
    }

    public function connection(?string $name): Robot
    {
        $this->connection = $name ?: $this->getDefaultConnection();
        return $this;
    }

    public function atAll(bool $atAll = true): Robot
    {
        $this->atAll = $atAll;
        return $this;
    }

    public function atMobile(array $mobile): Robot
    {
        $this->atMobile = $mobile;
        $this->atAll(false);
        return $this;
    }

    public function addAtMobile(string $mobile): Robot
    {
        if (!in_array($mobile, $this->atMobile)) {
            array_push($this->atMobile, $mobile);
        }
        $this->atAll(false);
        return $this;
    }

    private function getHost(): string
    {
        return data_get($this->configs, 'host', "https://oapi.dingtalk.com/robot/send");
    }

    public function timeout(int $timeout): Robot
    {
        $this->timeout = $timeout;
        return $this;
    }

    private function getDefaultConnection(): string
    {
        return data_get($this->configs, 'default', 'default');
    }

    /**
     * @return array
     * @throws ConfigException
     */
    private function getConnection(): array
    {
        $connection = $this->connection ?: $this->getDefaultConnection();
        $config     = data_get($this->configs, 'connections.' . $connection, []);
        if (!is_array($config)) {
            throw new ConfigException('机器人配置不存在');
        }
        $access_token = data_get($config, 'access_token');
        if (!$access_token || !is_string($access_token)) {
            throw new ConfigException('机器人 AccessToken 不能为空');
        }

        $secret = data_get($config, 'secret');

        return [$access_token, $secret];
    }

    /**
     * 实例化请求
     *
     * @param array|null $configs
     * @return Robot
     */
    public static function create(?array $configs = null): Robot
    {
        return (new static($configs));
    }

    /**
     * 发送消息
     *
     * @param Message $message
     * @return StreamInterface
     * @throws ConfigException
     * @throws DingTalkException
     * @throws ResponseException
     */
    public function send(Message $message): StreamInterface
    {
        $client = new Client(["timeout" => $this->timeout]);
        try {
            $msgType  = $message->getMsgType();
            $response = $client->request('POST', $this->getRequestUrl(),
                [
                    'json' => [
                        'msgtype' => $msgType,
                        'at'      => [
                            "atMobiles" => $this->atMobile,
                            "isAtAll"   => $this->atAll,
                        ],
                        $msgType  => $message->toArray()
                    ]
                ]
            );
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        } catch (ConfigException $exception) {
            throw $exception;
        } catch (\Exception | GuzzleException $exception) {
            throw new DingTalkException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }

        $status  = $response->getStatusCode();
        $content = $response->getBody();
        if ($status != 200) {
            throw new ResponseException('Abnormal response', $status, $content);
        }
        return $content;
    }

    /**
     * 签名
     *
     * @param string|null $secret
     * @return string
     */
    private function sign(?string $secret): string
    {
        $ret = '';
        if ($secret) {
            $timestamp = millisecond_timestamp();
            $sign      = urlencode(base64_encode(hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true)));
            $ret       = sprintf('&sign=%s&timestamp=%s', $sign, $timestamp);
        }
        return $ret;
    }

    /**
     * 消息推送请求链接地址
     *
     * @return string
     * @throws ConfigException
     */
    private function getRequestUrl(): string
    {
        list($access_token, $secret) = $this->getConnection();
        $url = sprintf('%s?access_token=%s', rtrim($this->getHost()), $access_token);
        $url .= $this->sign($secret);
        return $url;
    }
}