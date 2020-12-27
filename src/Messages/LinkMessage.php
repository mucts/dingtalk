<?php


namespace MuCTS\DingTalk\Messages;


use MuCTS\DingTalk\Contracts\Message;
use MuCTS\DingTalk\Exceptions\MessageException;

class LinkMessage implements Message
{
    private $title;
    private $text;
    private $messageUrl;
    private $picUrl;

    /**
     * 消息类型，此时固定为：link
     *
     * @return string
     */
    public function getMsgType(): string
    {
        return 'link';
    }

    /**
     * 消息标题
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title): LinkMessage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 消息标题
     *
     * @return string
     * @throws MessageException
     */
    private function getTitle(): string
    {
        if (!$this->title) {
            throw new MessageException('消息标题不能空');
        }
        return $this->title;
    }

    /**
     * 消息内容。如果太长只会部分展示
     *
     * @param string $text
     * @return $this
     */
    public function text(string $text): LinkMessage
    {
        $this->text = $text;
        return $this;
    }

    /**
     * 消息标题
     *
     * @return string
     * @throws MessageException
     */
    private function getText(): string
    {
        if (!$this->text) {
            throw new MessageException('消息内容不能空');
        }
        return $this->text;
    }

    /**
     * 点击消息跳转的URL
     *
     * @param string $messageUrl
     * @return $this
     */
    public function messageUrl(string $messageUrl): LinkMessage
    {
        $this->messageUrl = $messageUrl;
        return $this;
    }

    /**
     * 点击消息跳转的URL
     *
     * @return string
     * @throws MessageException
     */
    private function getMessageUrl(): string
    {
        if (!$this->messageUrl) {
            throw new MessageException('点击消息跳转的URL不能为空');
        }
        return $this->messageUrl;
    }

    /**
     * 图片URL
     *
     * @param string $picUrl
     * @return $this
     */
    public function picUrl(string $picUrl): LinkMessage
    {
        $this->picUrl = $picUrl;
        return $this;
    }

    /**
     * 图片URL
     *
     * @return string
     */
    private function getPicUrl(): string
    {
        return $this->picUrl ?: '';
    }

    /**
     * Link类型消息内容
     *
     * @return string[]
     * @throws MessageException
     */
    public function toArray(): array
    {
        return [
            "text"       => $this->getText(),
            "title"      => $this->getTitle(),
            "picUrl"     => $this->getPicUrl(),
            "messageUrl" => $this->getMessageUrl()
        ];
    }
}