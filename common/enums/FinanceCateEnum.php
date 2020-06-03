<?php
namespace addons\Finance\common\enums;

use common\enums\BaseEnum;

class FinanceCateEnum extends BaseEnum
{
    const ACCOUNT = 1;
    const METHOD = 2;
    const INCOME = 3;
    const EXPEND = 4 ;
    const DAY = 5;



    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ACCOUNT => '账户',
            self::METHOD => '结算',
            self::INCOME => '收入',
            self::EXPEND => '支出',
            self::DAY => '全天',

        ];
    }
}