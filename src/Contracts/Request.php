<?php


namespace MuCTS\DingTalk\Contracts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;
use MuCTS\DingTalk\Callbacks\Cryptos\ErrorCode;
use MuCTS\DingTalk\Exceptions\AccessTokenException;
use MuCTS\DingTalk\Exceptions\DingTalkException;
use MuCTS\DingTalk\Exceptions\ResponseException;

abstract class Request
{
    protected $host = 'https://oapi.dingtalk.com/';

    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    const OPTION_TYPE_JSON        = 'json';
    const OPTION_TYPE_QUERY       = 'query';
    const OPTION_TYPE_FORM_PARAMS = 'form_params';

    /**
     * 请求接口路由
     *
     * @return string
     */
    abstract protected function getRequestPath(): string;

    /**
     * 请求方式 get/post
     * @return string
     */
    abstract protected function getMethod(): string;

    /**
     * 请求参数类型 query/json/form_params
     * @return string
     */
    abstract protected function getOptionType(): string;

    /**
     * 超时时间
     *
     * @return int
     */
    abstract protected function getTimeout(): int;

    abstract protected function getRequestBody(): array;

    /**
     * 请求地址
     *
     * @return string
     */
    protected function getRequestUrl(): string
    {
        return $this->host . ltrim($this->getRequestPath(), '/');
    }

    /**
     * 网络请求
     *
     * @return mixed
     * @throws AccessTokenException
     * @throws DingTalkException
     * @throws ResponseException
     */
    public function request()
    {
        $method     = $this->getMethod();
        $optionType = $this->getOptionType() ?: ($method == self::METHOD_GET ? self::OPTION_TYPE_QUERY : self::OPTION_TYPE_JSON);
        $client     = new Client(["timeout" => $this->getTimeout()]);
        try {
            $response = $client->request(
                $method,
                $this->getRequestUrl(),
                [
                    $optionType => $this->getRequestBody()
                ]
            );
        } catch (\Exception | GuzzleException $exception) {
            throw new DingTalkException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }

        $status  = $response->getStatusCode();
        $content = $response->getBody();
        if ($status != 200) {
            throw new ResponseException('Abnormal response', $status, $content);
        }
        $content = json_decode($content, true);
        $errCode = data_get($content, 'errcode', ErrorCode::$IllegalAesKey);
        if ($errCode != ErrorCode::$OK) {
            $subCode = data_get($content, 'sub_code', ErrorCode::$ValidateSignatureError);
            if ($subCode == ErrorCode::$IllegalAccessToken) {
                throw new AccessTokenException(data_get($content, 'sub_msg', 'Illegal Access Token'));
            }
            throw new ResponseException(data_get($content, 'errmsg', '请求超时或响应错误'));
        }
        return $content;
    }
}