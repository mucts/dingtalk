<?php


namespace MuCTS\DingTalk\Messages;

use MuCTS\DingTalk\Exceptions\MessageException;

class SingleActionCardMessage extends MarkdownMessage
{
    protected $singleTitle;
    protected $singleURL;
    protected $btnOrientation;

    public function getMsgType(): string
    {
        return 'actionCard';
    }

    /**
     * 单个按钮的标题。(设置此项和singleURL后btns无效)
     *
     * @param string $singleTitle
     * @return SingleActionCardMessage
     */
    public function singleTitle(string $singleTitle): SingleActionCardMessage
    {
        $this->singleTitle = $singleTitle;
        return $this;
    }

    /**
     * 单个按钮的标题。(设置此项和singleURL后btns无效)
     *
     * @return string
     * @throws MessageException
     */
    protected function getSingleTitle(): string
    {
        if (!$this->singleTitle) {
            throw new MessageException('单个按钮的标题【singleTitle】不能为空。');
        }
        return $this->singleTitle;
    }

    /**
     * 点击singleTitle按钮触发的URL
     *
     * @param string $singleURL
     * @return $this
     */
    public function singleURL(string $singleURL): SingleActionCardMessage
    {
        $this->singleURL = $singleURL;
        return $this;
    }

    /**
     * 点击singleTitle按钮触发的URL
     *
     * @return string
     * @throws MessageException
     */
    protected function getSingleURL(): string
    {
        if (!$this->singleURL) {
            throw new MessageException('点击singleTitle按钮触发的URL【singleURL】不能为空。');
        }
        return $this->singleTitle;
    }

    /**
     *
     * @param int $btnOrientation
     * @return $this
     */
    public function btnOrientation(int $btnOrientation): SingleActionCardMessage
    {
        $this->btnOrientation = $btnOrientation;
        return $this;
    }

    protected function getBtnOrientation(): int
    {
        return $this->btnOrientation ?: 0;
    }

    public function toArray(): array
    {
        return [
            'title'          => $this->getTitle(),
            'text'           => $this->getText(),
            'singleTitle'    => $this->getSingleTitle(),
            'singleURL'      => $this->getSingleURL(),
            'btnOrientation' => $this->getBtnOrientation()
        ];
    }
}