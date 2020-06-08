<?php

namespace addons\Finance\merchant\controllers;

use common\enums\AppEnum;
use common\helpers\AddonHelper;
use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Finance\merchant\controllers
 */
class BaseController extends AddonsController
{
    protected $store_id;
    /**
    * @var string
    */
    public $layout = "@merchant/views/layouts/main";

    public function init()
    {
        // 注册资源
        AddonHelper::filePath();

        $bundles = Yii::$app->assetManager->bundles;
        foreach ($bundles as $bundle) {
            if (YII_DEBUG && isset($bundle->baseUrl)) {
                $path = Yii::getAlias('@root') . '/web' . $bundle->baseUrl;
                //  FileHelper::removeDirectory($path);
            }
        }

        parent::init();
    }

    public function getStoreId()
    {
        $role = Yii::$app->services->rbacAuthRole->getRole();

        if($role['pid'] == 0 && in_array(Yii::$app->id, [AppEnum::MERCHANT, AppEnum::BACKEND])){
            return null;
        }
        return Yii::$app->user->identity->store_id;
    }
}