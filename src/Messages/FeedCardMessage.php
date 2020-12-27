<?php


namespace MuCTS\DingTalk\Messages;


use MuCTS\DingTalk\Contracts\Message;
use MuCTS\DingTalk\Exceptions\MessageException;

class FeedCardMessage implements Message
{
    protected $links = [];

    public function getMsgType(): string
    {
        return 'feedCard';
    }

    /**
     * 消息列表
     *
     * @param array $links
     * @return $this
     */
    public function links(array $links): FeedCardMessage
    {
        $this->links = $links;
        return $this;
    }

    /**
     * 添加link
     *
     * @param string $title 单条信息文本
     * @param string $messageUrl 点击单条信息到跳转链接
     * @param string $picUrl 单条信息后面图片的URL
     * @return $this
     */
    public function addLink(string $title, string $messageUrl, string $picUrl): FeedCardMessage
    {
        array_push($this->links, [
            'title'      => $title,
            'messageURL' => $messageUrl,
            'picURL'     => $picUrl
        ]);
        return $this;
    }

    /**
     * 消息列表
     *
     * @return array
     * @throws MessageException
     */
    protected function getLinks(): array
    {
        if (!$this->links) {
            throw new MessageException('消息列表[links]不能为空');
        }
        return $this->links;
    }

    /**
     * FeedCard类型消息
     *
     * @return array[]
     * @throws MessageException
     */
    public function toArray(): array
    {
        return [
            'links' => $this->getLinks()
        ];
    }
}