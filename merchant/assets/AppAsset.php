<?php

namespace addons\Finance\merchant\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\Finance\merchant\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/Finance/merchant/resources/';

    public $css = [
        'css/finance-style.css'
    ];

    public $js = [
        'js/LodopFuncs.js',
        'js/finance.js'
    ];

    public $depends = [
    ];
}