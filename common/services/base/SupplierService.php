<?php


namespace addons\Finance\common\services\base;


use addons\Finance\common\models\base\Supplier;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

class SupplierService extends Service
{
    public function getDropDown()
    {
        $list = Supplier::find()
            ->where(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['=','status',StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::map($list,'id', 'title');
    }
}