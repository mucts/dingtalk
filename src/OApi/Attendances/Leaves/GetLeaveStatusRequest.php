<?php


namespace MuCTS\DingTalk\OApi\Attendances\Leaves;


use MuCTS\DingTalk\Contracts\AccessTokenRequest;

class GetLeaveStatusRequest extends AccessTokenRequest
{
    /**
     * 结束时间，时间戳，支持最多180天的查询
     **/
    private $endTime;

    /**
     * 分页偏移，非负整数
     **/
    private $offset;

    /**
     * 分页大小，正整数，最大20
     **/
    private $size;

    /**
     * 开始时间 ，时间戳，支持最多180天的查询
     **/
    private $startTime;

    /**
     * 待查询用户id列表，支持最多100个用户的批量查询
     **/
    private $useridList;

    private $apiParas = array();

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setStartTime(int $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setUseridList(string $useridList): self
    {
        $this->useridList = $useridList;
        return $this;
    }

    public function getUseridList()
    {
        return $this->useridList;
    }

    protected function getRequestPath(): string
    {
        return '/topapi/attendance/getleavestatus';
    }

    protected function getMethod(): string
    {
        return self::METHOD_POST;
    }

    protected function getOptionType(): string
    {
        return self::OPTION_TYPE_JSON;
    }

    protected function getTimeout(): int
    {
        return 3;
    }

    protected function getRequestBody(): array
    {
        $params     = [
            'start_time' => $this->getStartTime(),
            'end_time'   => $this->getEndTime(),
            'offset'     => $this->getOffset(),
            'size'       => $this->getSize()
        ];
        $userIdList = $this->getUseridList();
        if ($userIdList) $params['userid_list'] = $userIdList;
        return $params;
    }
}