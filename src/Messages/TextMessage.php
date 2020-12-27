<?php


namespace MuCTS\DingTalk\Messages;


use MuCTS\DingTalk\Contracts\Message;
use MuCTS\DingTalk\Exceptions\MessageException;

class TextMessage implements Message
{
    private $content;

    /**
     * 消息类型，此时固定为：text
     *
     * @return string
     */
    public function getMsgType(): string
    {
        return 'text';
    }

    public function content(string $content): TextMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 消息内容
     *
     * @return string
     * @throws MessageException
     */
    private function getContent(): string
    {
        if (!$this->content) {
            throw new MessageException('消息内容不能为空');
        }
        return $this->content;
    }

    /**
     * Text消息内容
     *
     * @return string[]
     * @throws MessageException
     */
    public function toArray(): array
    {
        return [
            'content' => $this->getContent()
        ];
    }
}