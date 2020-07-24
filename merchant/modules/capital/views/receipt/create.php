<?php

use addons\Finance\common\enums\ReasonEnum;
use yii\widgets\ActiveForm;


$this->title = "添加收款单";


?>

<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'col-sm-2 control-label text-right'],
        'template' => "{label}<div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
    ]
]);?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title;?></h3>
                    <div class="box-tools">
                        单据编号：<?= $sn;?>&nbsp;&nbsp;
                    </div>
                </div>
                <hr>
                <div class="box-body">
                    <div class="row">

                        <div class="col-sm-6 border-right">
                            <?= $form->field( $model, 'receipt_date' )->widget(kartik\date\DatePicker::class, [
                                'language' => 'zh-CN',
                                'layout'=>'{picker}{input}',
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true, // 今日高亮
                                    'autoclose' => true, // 选择后自动关闭
                                    'todayBtn' => true, // 今日按钮显示
                                ],
                                'options'=>[
                                    'class' => 'form-control no_bor',
                                    'value' => $model->isNewRecord ? date( 'Y-m-d' ) : $model->receipt_date,
                                ]
                            ]);?>
                            <?= $form->field( $model, 'customer_id' )->dropDownList( $customer,['prompt'=>'请选择...'] )?>
                            <?= $form->field($model, 'receipt_reason')->dropDownList(ReasonEnum::getMap(),['prompt'=>'请选择...']);?>
                        </div>

                        <div class="col-sm-6">
                            <?= $form->field( $model, 'payee_id' )->dropDownList( $staff,['prompt'=>'请选择...'] );?>
                            <?= $form->field( $model, 'order_id' )->dropDownList( [],['prompt'=>'请选择...'] )?>
                            <div class="form-group field-receipt-receipt_date">
                                <label class="control-label col-sm-2 text-right">制单人</label>
                                <div class="col-sm-8">
                                    <label class="control-label"><?= Yii::$app->user->identity->realname;?></label>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <?= $form->field($model, 'detail',[
                        'labelOptions' => [ 'class' =>'col-sm-1 control-label' ],
                        'template' => '{label}<div class="col-sm-10">{input}</div>'
                    ])->widget(unclead\multipleinput\MultipleInput::class, [
                        'max' => 10,
                        'columns' => [
                            [
                                'name' => 'account_id',
                                'title' => '结算账户',
                                'type' => 'dropDownList',
                                'items' => Yii::$app->financeService->account->getDropDown(),
                            ],
                            [
                                'name'  => 'price',
                                'title' => '收款金额',
                                'type' => 'textInput',
                                'enableError' => false,
                                'options' => [
                                    'class' => 'input-priority'
                                ]
                            ],
                            [
                                'name' => 'method_id',
                                'title' => '结算方式',
                                'type' => 'dropDownList',
                                'items' => Yii::$app->financeService->settlement->getDropDown()
                            ],

                            [
                                'name'  => 'settlement',
                                'title' => '结算号',
                                //'enableError' => false,
                                'options' => [
                                    'class' => 'input-priority'
                                ]
                            ],
                            [
                                'name'  => 'remark',
                                'title' => '备注',
                                // 'enableError' => false,
                                'options' => [
                                    'class' => 'input-priority'
                                ]
                            ]
                        ]
                    ]);
                    ?>
                </div>

                <div class="box-footer">
                    <?= \common\helpers\Html::submitButton( '提交',['class' => 'btn btn-success'] );?>
                </div>

            </div>
        </div>
    </div>
<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>


