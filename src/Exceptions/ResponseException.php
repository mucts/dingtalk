<?php


namespace MuCTS\DingTalk\Exceptions;


use Throwable;

class ResponseException extends Exception
{
    protected $content;

    public function __construct($message = "", $code = 0, $content = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}