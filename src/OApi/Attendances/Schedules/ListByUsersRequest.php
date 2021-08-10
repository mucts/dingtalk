<?php


namespace MuCTS\DingTalk\OApi\Attendances\Schedules;


use MuCTS\DingTalk\Contracts\AccessTokenRequest;

class ListByUsersRequest extends AccessTokenRequest
{
    /**
     * 起始日期
     **/
    private $fromDateTime;

    /**
     * 操作者userId
     **/
    private $opUserId;

    /**
     * 结束日期
     **/
    private $toDateTime;

    /**
     * 人员userIds
     **/
    private $userIds;

    public function setFromDateTime(int $fromDateTime): self
    {
        $this->fromDateTime = $fromDateTime;
        return $this;
    }

    public function getFromDateTime()
    {
        return $this->fromDateTime;
    }

    public function setOpUserId(string $opUserId): self
    {
        $this->opUserId = $opUserId;
        return $this;
    }

    public function getOpUserId()
    {
        return $this->opUserId;
    }

    public function setToDateTime(int $toDateTime): self
    {
        $this->toDateTime = $toDateTime;
        return $this;
    }

    public function getToDateTime()
    {
        return $this->toDateTime;
    }

    public function setUserIds(string $userIds): self
    {
        $this->userIds = $userIds;
        return $this;
    }

    public function getUserIds()
    {
        return $this->userIds;
    }

    protected function getRequestPath(): string
    {
        return '/topapi/attendance/schedule/listbyusers';
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
        return 5;
    }

    protected function getRequestBody(): array
    {
        $params  = [
            'op_user_id'     => $this->getOpUserId(),
            'from_date_time' => $this->getFromDateTime(),
            'to_date_time'   => $this->getToDateTime()
        ];
        $userIds = $this->getUserIds();
        if ($userIds) $params['userids'] = $this->getUserIds();
        return $params;
    }
}