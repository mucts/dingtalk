<?php


namespace MuCTS\DingTalk\Messages;


use MuCTS\DingTalk\Contracts\Message;
use MuCTS\DingTalk\Exceptions\MessageException;

class MarkdownMessage implements Message
{
    private $title;
    private $text;

    public function getMsgType(): string
    {
        return 'markdown';
    }

    /**
     * 首屏会话透出的展示内容
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title): MarkdownMessage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 首屏会话透出的展示内容
     *
     * @return string
     * @throws MessageException
     */
    protected function getTitle(): string
    {
        if (!$this->title) {
            throw new MessageException('首屏会话透出的展示内容【title】不能为空');
        }
        return $this->title;
    }

    /**
     * markdown格式的消息
     *
     * @param string $text
     * @return $this
     */
    public function text(string $text): MarkdownMessage
    {
        $this->text = $text;
        return $this;
    }

    /**
     * 添加 markdown 格式的消息
     *
     * @param string $key
     * @param string|null $value
     * @return $this
     */
    public function addText(string $key, ?string $value = null): MarkdownMessage
    {
        if ($value) {
            $key = sprintf('%s：%s', $key, $value);
        }
        $this->text .= $key . PHP_EOL . PHP_EOL;
        return $this;
    }

    /**
     * markdown格式的消息
     *
     * @return string
     * @throws MessageException
     */
    protected function getText(): string
    {
        if (!$this->text) {
            throw new MessageException('markdown格式的消息【text】不能为空');
        }
        return $this->text;
    }

    /**
     * markdown格式的消息体
     *
     * @return string[]
     * @throws MessageException
     */
    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'text'  => $this->getText()
        ];
    }
}