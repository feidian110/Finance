<?php

namespace addons\Finance\common\enums;

use common\enums\BaseEnum;

class ReasonEnum extends BaseEnum
{
    const PROJECT = 5;
    const FULL = 4;
    const BALANCE =  3;
    const THREE = 2;
    const SECONDARY = 1;
    const DEPOSIT = 0;


    public static function getMap(): array
    {
        return [
            self::DEPOSIT => '合同定金',
            self::SECONDARY => '合同二期款',
            self::THREE => '合同三期款',
            self::BALANCE => '合同尾款',
            self::FULL => '合同全款',
            self::PROJECT => '项目定金',
        ];
    }
}