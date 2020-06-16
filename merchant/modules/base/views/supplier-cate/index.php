<?php
use common\helpers\Html;
use common\helpers\Url;
use jianyan\treegrid\TreeGrid;

$this->title = "供应商类别";
$this->params['breadcrumbs'][] = ['label' => "财务管理"];
$this->params['breadcrumbs'][] = ['label' => "供应商管理"];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class=""><a href="<?= Url::to(['customer-cate/index']);?>"> 客户类别</a></li>
                <li class="active"><a href="<?= Url::to(['supplier-cate/index']);?>"> 供应商类别</a></li>
                <li class=""><a href="<?= Url::to(['product-cate/index']);?>"> 商品类别</a></li>
                <li class=""><a href="<?= Url::to(['income-cate/index']);?>"> 收入类别</a></li>
                <li class=""><a href="<?= Url::to(['expend-cate/index']);?>"> 支出类别</a></li>
                <li class=""><a href="<?= Url::to(['settlement-method/index']);?>"> 结算方式</a></li>
                <li class=""><a href="<?= Url::to(['account-cate/index']);?>"> 结算账户类别</a></li>
                <li class="pull-right">
                    <?= Html::create(['create'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= TreeGrid::widget([
                        'dataProvider' => $dataProvider,
                        'headerRowOptions' => ['style'=>'background: #F0F0F0'],
                        'keyColumnName' => 'id',
                        'parentColumnName' => 'pid',
                        'parentRootValue' => '0', //first parentId value
                        'pluginOptions' => [
                            'initialState' => 'collapsed',
                        ],
                        'options' => ['class' => 'table table-hover'],
                        'columns' => [

                            [
                                'attribute' => 'title',
                                'format' => 'raw',
                                'headerOptions' => ['class'=>'text-center'],
                                'value' => function ($model, $key, $index, $column){
                                    $str = Html::tag('span', $model->title, [
                                        'class' => 'm-l-sm'
                                    ]);
                                    $str .= Html::a(' <i class="icon ion-android-add-circle"></i>', ['create', 'pid' => $model['id']], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                    return $str;
                                }
                            ],

                            [
                                'attribute' => 'remark',
                                'headerOptions' => ['class' => 'col-md-6 text-center'],
                            ],
                            [
                                'attribute' => 'sort',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1 text-center'],
                                'contentOptions' => ['class'=>'text-center'],
                                'value' => function ($model, $key, $index, $column){
                                    return  Html::sort($model->sort);
                                }
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['class'=>'text-center'],
                                'contentOptions' => ['class'=>'text-center'],
                                'template'=> '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['edit','id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::delete(['delete','id' => $model->id]);
                                    },
                                ],
                            ],
                        ]
                    ]); ?>
                    <td width="30"></td>
                </div>
            </div>
        </div>
    </div>
</div>
