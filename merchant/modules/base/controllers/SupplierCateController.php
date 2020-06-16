<?php
namespace addons\Finance\merchant\modules\base\controllers;

use addons\Finance\common\enums\FinanceCateEnum;
use addons\Finance\common\models\base\Category;
use addons\Finance\merchant\controllers\BaseController;
use common\traits\MerchantCurd;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

class SupplierCateController extends BaseController
{
    use MerchantCurd;

    public $modelClass = Category::class;

    public $type =FinanceCateEnum::SUPPLIER;

    public function actionIndex()
    {
        $query = Category::find()
            ->orderBy('sort asc, created_at asc')
            ->andWhere(['type' => $this->type])
            ->andWhere($this->getStoreId() ? ['store_id'=>$this->getStoreId()] : [])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Category();
        $model -> type = $this->type;
        $model->store_id = $this->getStoreId() ? Yii::$app->services->store->getId() : "";

        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('供应商类别添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'store' => Yii::$app->storeService->store->getDropDown(),
            'cateDropDownList' => Yii::$app->financeService->cate->getDropDownForEdit($this->type),
        ]);
    }

    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id',null);
        $model = Category::findOne(['id' => $id,'merchant_id' =>Yii::$app->services->merchant->getId(),'store_id' =>Yii::$app->services->store->getId(),'type'=>$this->type]);
        if( $model == null ){
            throw new NotFoundHttpException('The page you requested does not exist！');
        }
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('商品类别更新成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax( $this->action->id,[
            'model' => $model,
            'cateDropDownList' => Category::getDropDownForEdit($this->type),
        ] );
    }
}