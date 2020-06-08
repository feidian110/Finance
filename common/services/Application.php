<?php
namespace addons\Finance\common\services;

use common\components\Service;

/**
 * Class Application
 * @package addons\Finance\common\services
 * @property \addons\Finance\common\services\base\BaseService $base 基础
 * @property \addons\Finance\common\services\base\CateService $cate  分类
 * @property \addons\Finance\common\services\base\AccountService $account 结算账户
 * @property \addons\Finance\common\services\base\SettlementService $settlement 计算方式
 * @property \addons\Finance\common\services\report\InvoiceService $invoice 票据
 *
 */

class Application extends Service
{
    public $childService = [
        'base' => 'addons\Finance\common\services\base\BaseService',
        'cate' => 'addons\Finance\common\services\base\CateService',
        'account' => 'addons\Finance\common\services\base\AccountService',
        'settlement' => 'addons\Finance\common\services\base\SettlementService',
        'invoice' => 'addons\Finance\common\services\report\InvoiceService'
    ];
}