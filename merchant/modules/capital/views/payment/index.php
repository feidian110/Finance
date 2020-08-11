<?php

use addons\Crm\common\enums\NatureEnum;
use addons\Crm\common\enums\SlotEnum;
use addons\Finance\common\enums\AuditStatusEnum;
use addons\Finance\common\enums\ReasonEnum;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = "付款单列表";
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

                            'attribute' => 'bill_date',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center','style'=> 'height: 50px'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->bill_date;
                            },
                        ],
                        [
                            'attribute' => 'sn',
                            'contentOptions' => ['class'=>'text-center'],
                            'headerOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'supplier.title',
                            'headerOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'amount',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                        ],
                        [
                            'header' => '付款人',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model['payer']['realname'] ? $model['payer']['realname'] : "";
                            },
                        ],
                        [
                            'attribute' => 'pay_status',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'value' => function( $model ){
                                return "";
                            }
                        ],

                        [
                            'attribute' => 'auditor_id',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'value' => function( $model ){
                                if( $model['auditor_id'] ){
                                    return $model['auditor']['realname'];
                                }

                            }
                        ],
                        [
                            'attribute' => 'audit_status',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function($model){
                                return Html::a(AuditStatusEnum::getValue($model['status']),['audit','id'=>$model['id'],'status' =>$model['status'] == AuditStatusEnum::DISABLED ? AuditStatusEnum::ENABLED : AuditStatusEnum::DISABLED],['class'=>$model['status'] == AuditStatusEnum::ENABLED ? 'green' : 'orange']);
                            }
                        ],
                        [
                            'attribute' => 'creator_id',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'value' => function( $model ){
                                return $model['creator']['realname'];
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'headerOptions' => ['class'=>'text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::a(AuditStatusEnum::getValue($model['status']),['audit','id'=>$model['id'],'status' =>$model['status'] == AuditStatusEnum::DISABLED ? AuditStatusEnum::ENABLED : AuditStatusEnum::DISABLED],['class'=>$model['status'] == AuditStatusEnum::ENABLED ? 'green' : 'orange']);
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
                            'headerOptions' => ['class'=>'text-center','style' => 'width: 350px'],
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
                            'template' => '{print} {view} {edit} {status} {destroy}',
                            'buttons' => [
                                'print' => function($url,$model){
                                    return Html::a('打印',['print', 'id' =>$model->id],['class' => 'btn btn-info btn-xs']);
                                },
                                'view' => function($url, $model ){
                                    return Html::a('查看',['view', 'id' => $model->id],['class'=>'btn btn-success btn-xs',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id],'编辑',['class'=>'btn btn-primary btn-xs']);
                                },
                                'status' => function ($url, $model, $key) {
                                    //  return Html::shelf($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id],'删除',['class'=>'btn btn-danger btn-xs']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>
