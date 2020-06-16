<?php
namespace addons\Finance\merchant\modules\base\controllers;

use addons\Finance\common\enums\FinanceCateEnum;
use addons\Finance\common\models\base\Category;
use addons\Finance\common\models\base\Supplier;
use addons\Finance\merchant\controllers\BaseController;
use addons\Store\common\models\base\Inter;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;

use Yii;

class SupplierController extends BaseController
{
    use MerchantCurd;

    public $modelClass = Supplier::class;

    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['=', 'merchant_id', $this->getMerchantId()])
            ->andFilterWhere($this->getStoreId() ? ['=','store_id',Yii::$app->services->store->getId()] : [])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Supplier();

        if( Yii::$app->request->isPost ){
            $this->activeFormValidate($model);
            $data = Yii::$app->request->post();
            $supplier = Inter::findOne($data['Supplier']['inter_id']);
            $model->supplier_id = $supplier['supplier_id'] ?? null;
            if( $model->load($data) && $model->save() ){
                return $this->message('供应商添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message('供应商添加失败！', $this->redirect(['index']), 'error');
        }
        return  $this->renderAjax( $this->action->id,[
            'model' => $model,
            'store' => Yii::$app->storeService->store->getDropDown(),
            'supplier' => Yii::$app->storeService->inter->getSupplierDate(),
            'category' => Yii::$app->financeService->cate->getDropDown(FinanceCateEnum::SUPPLIER),
        ] );
    }

    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id',null);
        $model = $this->findModel($id);
        return $this->renderAjax( $this->action->id,[
            'model' => $model,
            'store' => Yii::$app->storeService->store->getDropDown(),
            'supplier' => Yii::$app->storeService->inter->getSupplierDate(),
            'category' => Yii::$app->financeService->cate->getDropDown(FinanceCateEnum::SUPPLIER),
        ] );
    }
}