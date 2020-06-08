<?php

use addons\Finance\common\enums\AuditStatusEnum;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = "收款列表";
$this->params['breadcrumbs'][] = ['label' => "财务管理"];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['create'], '创建', [
                    ]) ?>
                </div>
            </div>

            <div class="box-footer clearfix">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-bordered table-hover'],
                    'headerRowOptions' => ['style'=>'background: #F0F0F0; height: 50px'],

                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                        ],

                        [

                            'attribute' => 'receipt_date',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center','style'=> 'height: 55px'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->receipt_date;
                            },
                        ],
                        [
                            'attribute' => 'customer.title',
                            'headerOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => '',
                            'headerOptions' => ['class'=>'text-center'],
                            'header' => "客户信息",
                            'value' => function ($model) {
                                return "";
                            },
                        ],

                        [
                            'attribute' => 'payment_id',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return "";
                            },
                        ],
                        [
                            'attribute' => 'receipt_price',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-right'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '￥'.(int)$model->receipt_price;
                            },
                        ],
                        [
                            'attribute' => 'direction',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                if( $model->direction ==0 ){
                                    return '自收';
                                }else{
                                    return '代收';
                                }
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'direction',
                                AuditStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                        ],
                        [
                            'attribute' => 'status',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                if( $model->status == 1 ){
                                    return Html::a(AuditStatusEnum::getValue($model->status),null,['class'=>'btn btn-success btn-xs']);
                                }else{
                                    return '<span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">'.AuditStatusEnum::getValue($model->status).'</span>';
                                }
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'status',
                                AuditStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                        ],

                        [
                            'attribute' => 'remark',
                            'headerOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->remark;
                            },
                        ],
                        [
                            'header' => "操作",
                            'headerOptions' => ['class'=>'text-center'],
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => [ 'class'=>'text-center','style'=>'vertical-align: middle'],
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [

                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id],'编辑',['class'=>'kt-badge kt-badge--brand kt-badge--inline']);
                                },
                                'status' => function ($url, $model, $key) {
                                    //  return Html::shelf($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    //  return Html::delete(['destroy', 'id' => $model->id],'删除',['class'=>'btn btn-danger btn-xs']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>
