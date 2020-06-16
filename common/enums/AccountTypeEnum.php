<?php


namespace addons\Finance\common\enums;


use common\enums\BaseEnum;

class AccountTypeEnum extends BaseEnum
{
    const PERSONAL = 1;
    const PUBLIC = 2;




    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::PERSONAL => '个人账户',
            self::PUBLIC => '对公账户',


        ];
    }
}