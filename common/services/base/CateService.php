<?php
namespace addons\Finance\common\services\base;

use addons\Finance\common\enums\FinanceCateEnum;
use addons\Finance\common\models\base\Category;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;

class CateService extends Service
{
    public function getAccountCate()
    {
        $model = Category::find()->select('id,title,type,merchant_id,store_id')
            ->where(['merchant_id' =>Yii::$app->services->merchant->getId()])
            ->andWhere(['type' => FinanceCateEnum::ACCOUNT])->andWhere(['>=','status',StatusEnum::DISABLED])->asArray()->all();
        return ArrayHelper::map($model,'id','title');
    }
}