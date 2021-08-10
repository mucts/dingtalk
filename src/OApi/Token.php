<?php


namespace MuCTS\DingTalk\OApi;


use MuCTS\DingTalk\Contracts\Cache;
use MuCTS\DingTalk\Contracts\Request;

class Token extends Request
{
    private $appKey;
    private $appSecret;
    private $cache;
    private $reset;

    public function __construct(string $appKey, string $appSecret, ?Cache $cache = null, $reset = false)
    {
        $this->appKey    = $appKey;
        $this->appSecret = $appSecret;
        $this->cache     = $cache;
        $this->reset     = $reset;
    }


    protected function getRequestPath(): string
    {
        return '/gettoken';
    }

    protected function getMethod(): string
    {
        return self::METHOD_GET;
    }

    protected function getOptionType(): string
    {
        return self::OPTION_TYPE_QUERY;
    }

    protected function getTimeout(): int
    {
        return 3;
    }

    protected function getRequestBody(): array
    {
        return [
            'appkey'    => $this->appKey,
            'appsecret' => $this->appSecret
        ];
    }

    public function request()
    {
        $cacheKey = 'csd_dta_' . $this->appKey;
        if (!$this->reset && $this->cache && $this->cache->exists($cacheKey)) {
            return $this->cache->get($cacheKey);
        }
        $res         = parent::request();
        $accessToken = data_get($res, 'access_token');
        $expiresIn   = data_get($res, 'expires_in');
        if ($accessToken && $expiresIn) $this->cache->set($cacheKey, $accessToken, $expiresIn);
        return $accessToken;
    }
}