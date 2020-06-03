<?php

use common\helpers\Html;
use common\enums\StatusEnum;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "结算账户";
$this->params['breadcrumbs'][] = ['label' => "账户管理", 'url' => 'javascript:(0);'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['create'],'添加',[
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal'
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'headerRowOptions' => ['style'=>'background: #F0F0F0'],
        'columns' => [

            [
                'attribute'=> 'sn',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
                'contentOptions' => ['class'=>'text-center'],
            ],
            [
                'attribute'=> 'title',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
            ],

            [
                'attribute'=> 'init_balance',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
                'contentOptions' => ['class'=>'text-right'],
                'value' => function ($model){
                    if( $model['init_balance'] == 0 ){
                        return "";
                    }
                    return '￥'.number_format($model['init_balance'],2);
                }
            ],
            [
                'attribute'=> 'init_date',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
                'contentOptions' => ['class'=>'text-center'],
            ],
            [
                'attribute'=> 'category.title',
                'header' => '账户类别',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
                'contentOptions' => ['class'=>'text-center'],
            ],
            [
                'attribute'=> 'current_balance',
                'headerOptions' => ['class'=>'col-md-1 text-center'],
                'contentOptions' => ['class'=>'text-right'],
                'value' => function ($model){
                    if( $model['current_balance'] == 0 ){
                        return "";
                    }
                    return '￥'.number_format($model['current_balance'],2);
                }
            ],
            [
                'attribute'=> 'status',
                'contentOptions' => ['class'=>'col-md-1 text-center'],
                'headerOptions' => ['class'=>'text-center'],
                'format' => 'raw',
                'value' => function($model){
                    return StatusEnum::getValue($model->status);
                }
            ],
            [
                'attribute'=> 'remark',
                'headerOptions' => ['class'=>'text-center'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['class'=>'col-md-2 text-center'],
                'contentOptions' => ['class'=>'text-center'],
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id],'编辑',[
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal'
                        ]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
