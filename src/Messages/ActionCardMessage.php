<?php


namespace MuCTS\DingTalk\Messages;


use MuCTS\DingTalk\Exceptions\MessageException;

class ActionCardMessage extends MarkdownMessage
{
    protected $btnOrientation;
    protected $btns = [];

    /**
     * 0-按钮竖直排列，1-按钮横向排列
     *
     * @param int $btnOrientation
     * @return $this
     */
    public function btnOrientation(int $btnOrientation): ActionCardMessage
    {
        $this->btnOrientation = $btnOrientation;
        return $this;
    }

    /**
     * 0-按钮竖直排列，1-按钮横向排列
     *
     * @return int
     */
    protected function getBtnOrientation(): int
    {
        return $this->btnOrientation ?: 0;
    }

    /**
     * 添加按钮
     *
     * @param string $title
     * @param string $url
     * @return $this
     */
    public function addBtn(string $title, string $url): ActionCardMessage
    {
        array_push($this->btns, ['title' => $title, 'actionURL' => $url]);
        return $this;
    }

    /**
     * 按钮
     *
     * @param array $btns
     * @return $this
     */
    public function btns(array $btns): ActionCardMessage
    {
        $this->btns = $btns;
        return $this;
    }

    /**
     * 按钮
     *
     * @return array
     * @throws MessageException
     */
    public function getBtns(): array
    {
        if (empty($this->btns)) {
            throw new MessageException('按钮【btns】不能为空');
        }
        return $this->btns;
    }

    public function toArray(): array
    {
        return [
            'title'          => $this->getTitle(),
            'text'           => $this->getText(),
            'btnOrientation' => $this->getBtnOrientation(),
            'btns'           => $this->getBtns()
        ];
    }
}