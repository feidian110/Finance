<?php
namespace addons\Finance\common\services;

use common\components\Service;

/**
 * Class Application
 * @package addons\Finance\common\services
 * @property \addons\Finance\common\services\base\CateService $cate  分类
 * @property \addons\Finance\common\services\base\AccountService $account 结算账户
 *
 */

class Application extends Service
{
    public $childService = [
        'cate' => 'addons\Finance\common\services\base\CateService',
        'account' => 'addons\Finance\common\services\base\AccountService'

    ];
}