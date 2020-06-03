<?php
namespace addons\Finance\store\controllers;

use addons\Crm\common\models\finance\Category;
use addons\Crm\common\models\finance\Supplier;
use addons\Crm\common\models\finance\SupplierProfile;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\StoreCurd;
use Yii;

class SupplierController extends BaseController
{
    use StoreCurd;

    public $modelClass = SupplierProfile::class;

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
            ->andFilterWhere(['=', 'merchant_id', Yii::$app->user->identity->merchant_id])
            ->andFilterWhere(['=','store_id',Yii::$app->services->store->getId()])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new SupplierProfile();
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->create($data) ){
                return $this->message('供应商添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message('供应商添加失败！', $this->redirect(['index']), 'error');
        }
        return  $this->renderAjax( $this->action->id,[
            'model' => $model,
            'supplier' => Supplier::getNormalSupplierByAllow(),
            'category' => Category::getCategoryDropDown('supplier')
        ] );
    }

    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id',null);
        $model = $this->findModel($id);
        return $this->renderAjax( $this->action->id,[
            'model' => $model,
            'supplier' => Supplier::getNormalSupplierByAllow(),
            'category' => Category::getCategoryDropDown('supplier')
        ] );
    }
}