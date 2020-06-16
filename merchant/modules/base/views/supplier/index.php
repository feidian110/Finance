<?php
use common\enums\SortEnum;
use common\enums\StatusEnum;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = "供应商列表";
$this->params['breadcrumbs'][] = ['label' => "资料设置", 'url' => 'javascript:(0);'];
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
                        'data-target' => '#ajaxModalLg'
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> 提示：</h4>
                    <ul>
                        <li>企业可以自己新增供应商，也可联系企业管理员建立企业互联关系再添加；</li>
                        <li>只有建立企业互联关系，才可使用智能派单服务；</li>
                        <li>列表中供应商前方带有 “<i class="fa fa-fw fa-globe"></i>” 标志的，为已建立互联关系企业。无该标志的为自增供应商企业，无法使用本平台智能派单服务；</li>
                        <li>建立互联关系后，供应商在其后台即可查看收到的工单或者订单；</li>
                        <li>同一家供应商只能与同一家门店建立一条一对一互联关系；</li>
                    </ul>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-bordered table-hover'],
                    'headerRowOptions' => ['style'=>'background: #F0F0F0'],
                    'columns' => [
                        [
                            'attribute'=> 'inter_id',
                            'header' => '<i class="fa fa-fw fa-globe"></i>',
                            'headerOptions' => ['class'=>'text-center','style' => 'width:20px'],
                            'format' => 'raw',
                            'value' => function($model){
                                if( $model['inter_id'] ){
                                    return '<i class="fa fa-fw fa-globe green"></i>';
                                }
                                return "";
                            }
                        ],
                        [
                            'attribute'=> 'title',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute'=> 'category.title',
                            'header' => '账户类别',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                        ],
                        [
                            'attribute' => 'contact',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                        ],
                        [
                            'attribute' => 'mobile',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                        ],
                        [
                            'attribute'=> 'init_payable',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'contentOptions' => ['class'=>'text-right'],
                            'value' => function ($model){
                                if( $model['init_payable'] == 0 ){
                                    return "";
                                }
                                return '￥'.number_format($model['init_payable'],2);
                            }
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
                            'attribute'=> 'balance_date',
                            'headerOptions' => ['class'=>'col-md-1 text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                        ],

                        [
                            'attribute'=> 'remark',
                            'headerOptions' => ['class'=>'text-center'],
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
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'headerOptions' => ['class'=>'col-md-2 text-center'],
                            'contentOptions' => ['class'=>'text-center'],
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key){
                                    return Html::edit(['edit', 'id' => $model->id],'编辑',[
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg'
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
