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

    public function getDropDownForEdit($type='',$id = '')
    {
        $list = Category::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
        return ArrayHelper::merge([0 => '顶级分类'], $data);
    }

    public function getDropDown($type='',$id = '')
    {
        $list = Category::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
        $models = ArrayHelper::itemsMerge($list);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

    }
}