<?php


namespace MuCTS\DingTalk\Contracts;


abstract class AccessTokenRequest extends Request
{
    protected $accessToken;

    public static function create(): self
    {
        return new static();
    }

    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    protected function getRequestUrl(): string
    {
        $url = parent::getRequestUrl();
        if (strpos($url, '?') === false) $url .= '?';
        else $url .= '&';
        return $url . 'access_token=' . $this->accessToken;
    }
}