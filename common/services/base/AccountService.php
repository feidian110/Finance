<?php


namespace addons\Finance\common\services\base;


use addons\Finance\common\models\base\Account;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;

class AccountService extends Service
{
    public function authCode()
    {
        $model = Account::find()
            ->where([
                'merchant_id' => Yii::$app->services->merchant->getId(),
            ])->orderBy(['created_at' => SORT_DESC])->one();
        if( $model ){
            return $model->sn + 1;
        }
        return 1001;
    }

    public function getDropDown()
    {
        $list = $this->getNormalData();
        return ArrayHelper::map($list,'id','title');
    }

    /**
     * 获取当前商家正常的结算账户
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function getNormalData()
    {
        $model = Account::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->andWhere(['>=','status',StatusEnum::DISABLED ])
            ->asArray()->all();
        return $model ?? "";
    }
}