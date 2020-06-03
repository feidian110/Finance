<?php


namespace addons\Finance\common\services\base;


use addons\Finance\common\models\base\Account;
use common\components\Service;
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
}