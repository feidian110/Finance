<?php
namespace addons\Finance\store\controllers;

use addons\Crm\common\models\finance\Unit;
use common\traits\StoreCurd;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

class UnitController extends BaseController
{
    use StoreCurd;

    public $modelClass = Unit::class;


    public function actionIndex()
    {
        $query = Unit::find()
            ->orderBy('sort asc, created_at asc')
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
        $model = new Unit();
        $model->store_id = Yii::$app->services->store->getId();
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('计量单位添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax( $this->action->id,[
            'model' => $model
        ] );
    }

    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id',0);
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if( Yii::$app->request->isPost ){
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('计量单位更新成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax( $this->action->id,[
            'model' => $model
        ] );
    }
}