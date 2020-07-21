<?php


namespace addons\Finance\common\enums;


use common\enums\BaseEnum;

class BillTypeEnum extends BaseEnum
{
    const BAL = 'BAL';
    const SALE = 'SALE';
    const WORKS = 'WORKS';
    const INCOME = 'INCOME';
    const EXPEND = 'EXPEND';
    const OTHER_INCOME = 'OTHER_INCOME';
    const OTHER_EXPEND = 'OTHER_EXPEND';
    const CPR = 'CPR';
    const PPC = 'PPC';
    const RPC = 'RPC';
    const RTR = 'RTR';
    const CTC = 'CTC';

    public static function getMap(): array
    {
        return [
            self::BAL => '期初余额',
            self::SALE => '销售合同',
            self::WORKS => '执行工单',
            self::INCOME => '收款单',
            self::EXPEND => '付款单',
            self::OTHER_INCOME => '其他收款单',
            self::OTHER_EXPEND => '其他付款单',
            self::CPR => '预收冲应收',
            self::PPC => '预付冲应付',
            self::RPC => '应收冲应付',
            self::RTR => '应收转应收',
            self::CTC => '应付转应付',
        ];
    }
}