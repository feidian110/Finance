<?php


namespace addons\Finance\common\enums;


use common\enums\BaseEnum;

class FinanceTypeEnum extends BaseEnum
{
    const RECEIPT = 1;
    const PAYMENT = 2;
    const OTHER_RECEIPT = 3;
    const OTHER_PAYMENT = 4;
    const TRANSFER = 5;
    const PAY = 6;



    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::RECEIPT => '收款',
            self::PAYMENT => '付款',
            self::OTHER_RECEIPT => '其他收款',
            self::OTHER_PAYMENT => '其他付款',
            self::TRANSFER => '转账',
            self::PAY => '付款',
        ];
    }
}