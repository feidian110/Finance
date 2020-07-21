<?php


namespace addons\Finance\common\enums;


use common\enums\BaseEnum;

class AuditStatusEnum extends BaseEnum
{
    const SUCCESS = 2;
    const ENABLED = 1;
    const DISABLED = 0;


    public static function getMap(): array
    {
        return [
            self::ENABLED => '已审核',
            self::DISABLED => '待审核',
            self::SUCCESS => '已驳回',
        ];
    }
}