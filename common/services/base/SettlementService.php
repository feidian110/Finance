<?php


namespace addons\Finance\common\services\base;


use addons\Finance\common\enums\FinanceCateEnum;
use addons\Finance\common\models\base\Category;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

class SettlementService extends Service
{
    public function getDropDown()
    {
        $list = $this->getNormalSettlement();
        return ArrayHelper::map($list,'id','title');
    }

    public function getNormalSettlement()
    {
        $model = Category::find()
            ->where(['merchant_id'=>$this->getMerchantId(),'type'=>FinanceCateEnum::METHOD])
            ->andWhere(['>=','status',StatusEnum::DISABLED ])
            ->asArray()->all();
        return $model ?? "";
    }
}