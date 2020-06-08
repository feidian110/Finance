<?php


namespace addons\Finance\merapi\controllers;


use addons\Crm\common\models\contract\Contract;
use merapi\controllers\OnAuthController;
use Yii;

class OrderController extends OnAuthController
{
    public $modelClass = Contract::class;

    protected $authOptional = ['index'];

    public function actionIndex()
    {
        $id = Yii::$app->request->get('id');
        $customer = Contract::find()->select('id,title,act_time,slot,act_place,nature_id')
            ->where(['id'=>$id])
            ->orderBy(['act_time'=>SORT_DESC])
            ->asArray()->all();
        return $customer;
    }
}