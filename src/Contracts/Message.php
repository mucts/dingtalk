<?php


namespace MuCTS\DingTalk\Contracts;


interface Message
{
    public function getMsgType(): string;

    public function toArray(): array;
}