<?php
use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
$this->title = Yii::t('app','Settlement Method');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Manager'), 'url' => 'javascript:(0);'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class=""><a href="<?= Url::to(['customer-cate/index']);?>"> 客户类别</a></li>
                <li class=""><a href="<?= Url::to(['supplier-cate/index']);?>"> 供应商类别</a></li>
                <li class=""><a href="<?= Url::to(['product-cate/index']);?>"> 商品类别</a></li>
                <li class=""><a href="<?= Url::to(['income-cate/index']);?>"> 收入类别</a></li>
                <li class=""><a href="<?= Url::to(['expend-cate/index']);?>"> 支出类别</a></li>
                <li class="active"><a href="<?= Url::to(['settlement-method/index']);?>"> 结算方式</a></li>
                <li><a href="<?= Url::to(['account-cate/index']);?>"> 结算账户类别</a></li>
                <li class="pull-right">
                    <?= Html::create(['create'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-bordered table-hover'],
                        'headerRowOptions' => ['style'=>'background: #F0F0F0'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['class'=>'text-center','width' =>80],
                                'contentOptions' => ['class'=>'text-center'],
                            ],
                            [
                                'attribute' => 'title',
                                'headerOptions' => ['class'=>'col-md-2 text-center'],
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'remark',
                                'headerOptions' => ['class'=>'text-center'],
                                'format' => 'raw',
                            ],
                            [
                                'header' => "操作",
                                'headerOptions' => ['class'=>'col-md-2 text-center'],
                                'contentOptions' => ['class'=>'text-center'],
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return false;
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                    <td width="30"></td>
                </div>
            </div>
        </div>
    </div>
</div>
