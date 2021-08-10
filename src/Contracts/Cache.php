<?php


namespace MuCTS\DingTalk\Contracts;


interface Cache
{
    public function set(string $cacheKey, string $accessToken, int $expiresIn): int;

    public function get(string $cacheKey): ?string;

    public function exists(string $cacheKey): bool;
}