<?php

namespace addons\Finance\merchant\modules\base\controllers;

use addons\Crm\common\models\finance\Category;
use common\traits\StoreCurd;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

class CustomerCateController extends BaseController
{
    use StoreCurd;

    public $modelClass = Category::class;

    public $type ='customer';

    public function actionIndex()
    {
        $query = Category::find()
            ->orderBy('sort asc, created_at asc')
            ->andWhere(['type' => $this->type])
            ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId(),'store_id' => Yii::$app->services->store->getId()]);
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
        $model->store_id = Yii::$app->services->store->getId();

        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('商品类别添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'cateDropDownList' => Category::getDropDownForEdit($this->type),
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