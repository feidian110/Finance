<?php
namespace addons\Finance\merchant\modules\base\controllers;

use addons\Finance\common\models\base\Account;
use addons\Finance\common\models\base\Category;
use addons\Finance\merchant\controllers\BaseController;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;

use common\traits\MerchantCurd;
use Yii;

class AccountController extends BaseController
{
    use MerchantCurd;

    public $modelClass = Account::class;

    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['=', 'merchant_id', $this->getMerchantId()])
            ->andFilterWhere($this->getStoreId() ? ['=','store_id',$this->getStoreId()] : [])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Account();
        // ajax 校验
        $this->activeFormValidate($model);
        if (Yii::$app->request->isPost) {

            if( $this->getStoreId() ){
                $this->store_id = $this->getStoreId();
            }
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('账户添加成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax( $this->action->id,[
            'model' => $model,
            'storeId' =>$this->getStoreId(),
            'store' => Yii::$app->storeService->store->getDropDown(),
            'cate' => Yii::$app->financeService->cate->getAccountCate()
        ] );
    }

    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $cate = new Category();
        // ajax 校验
        $this->activeFormValidate($model);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if( $model->load($data) && $model->save() ){
                return $this->message('账户更新成功！', $this->redirect(['index']), 'success');
            }
            return $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax( $this->action->id,[
            'model' => $model,
            'cate' => $cate->getNormalSettlementAccount()
        ] );
    }

}