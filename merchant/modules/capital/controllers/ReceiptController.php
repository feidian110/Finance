<?php


namespace addons\Finance\merchant\modules\capital\controllers;


use addons\Finance\common\enums\AuditStatusEnum;
use addons\Finance\common\enums\BillTypeEnum;
use addons\Finance\common\enums\FinanceTypeEnum;
use addons\Finance\common\models\capital\Receipt;
use addons\Finance\common\models\report\Invoice;
use addons\Finance\merchant\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use Yii;

class ReceiptController extends BaseController
{
    use MerchantCurd;

    public $modelClass = Receipt::class;

    public $financeType = FinanceTypeEnum::RECEIPT;

    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [''], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['merchant_id'=>$this->getMerchantId()])
            ->andWhere($this->getStoreId() ? ['store_id'=>$this->getStoreId()] : [])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Receipt();
        if( Yii::$app->request->isPost ){
            // ajax 校验
            $this->activeFormValidate($model);
            $post = Yii::$app->request->post();
            $model->sn = Yii::$app->financeService->base->createSn($this->modelClass,$this->financeType);
            $model->receipt_price = array_sum(array_column($post['Receipt']['detail'], 'price'));

            if( $model->create($post) ){
                return $this->message('收据添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message("收款添加失败！", $this->redirect(['index']), 'error');
        }
        return $this->render( $this->action->id,[
            'model' =>$model,
            'sn' => Yii::$app->financeService->base->createSn($this->modelClass,$this->financeType),
            'customer' => Yii::$app->crmService->customer->getNormalDropdown(),
            'staff' => Yii::$app->financeService->base->getNormalStaff()
        ] );
    }

    public function actionView()
    {
        $id = Yii::$app->request->get('id',0);
        $model = $this->findModel($id);
        return $this->renderAjax( $this->action->id, [
            'model' => $model
        ] );
    }

    public function actionAudit()
    {
        $id = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');
        $model = $this->findModel($id);
        $result = $model::updateAll(['audit_status' =>$status,'status' => $status,'audit_time' => $status== AuditStatusEnum::ENABLED ? time() : null,'auditor_id' => Yii::$app->user->getId() ],['id' =>$id]);
        $res = Invoice::updateAll(['status' =>$status],['obj_id' =>$id, 'bill_type' => BillTypeEnum::INCOME]);
        if( $result && $res ){
            return $this->message('状态更新成功！', $this->redirect(['index']), 'success');
        }
        return $this->message("状态更新失败！", $this->redirect(['index']), 'error');
    }
}