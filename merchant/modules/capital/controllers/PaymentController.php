<?php


namespace addons\Finance\merchant\modules\capital\controllers;


use addons\Finance\merchant\controllers\BaseController;

class PaymentController extends BaseController
{
    public function actionIndex()
    {
        return $this->render( $this->action->id );
    }
}