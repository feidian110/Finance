<?php


namespace addons\Finance\store\controllers;


class WarehouseController extends BaseController
{
    public function actionIndex()
    {
        return $this->render( $this->action->id );
    }
}