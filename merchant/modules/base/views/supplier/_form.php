<?php

use addons\Finance\common\enums\AccountTypeEnum;
use common\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'validationUrl' => $model->isNewRecord ? Url::to(['create']) : Url::to(['edit','id'=>$model['id']]),
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'options' => ['class' => 'form-group'],
        'labelOptions' => ['class' => 'col-sm-4 control-label text-right'],
        'template' => '{label}<div class="col-sm-7">{input}{hint}{error}</div>'
    ]
]);?>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 border-right">
            <?= $form->field($model, 'store_id')->dropDownList($store);?>
            <?= $form->field($model,'category_id')->dropDownList($category,['prompt'=>'请选择供应商类别...']);?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model,'inter_id')->dropDownList($supplier,['prompt'=>'请选择企业......']);?>
            <?= $form->field($model,'title')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 border-right">
            <?= $form->field($model, 'contact') ->textInput();?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'mobile')->input('number');?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 border-right">
            <?= $form->field($model,'init_payable')->input('number',['value'=>$model->isNewRecord ? 0 : $model->init_payable]);?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'init_payment')->input('number',['value' => $model->isNewRecord ? 0 :$model->init_payment]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 border-right">
            <?= $form->field($model, 'balance_date')->widget(kartik\date\DatePicker::class, [
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
                    'value' => $model->isNewRecord ? date('Y-m-d') : $model->balance_date
                ]
            ]);;?>
        </div>
        <div class="col-sm-6">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 border-right">
            <?= $form->field($model, 'bank_name')->textInput();?>
            <?= $form->field($model, 'account_name')->textInput()?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'account_type')->dropDownList(AccountTypeEnum::getMap());?>
            <?= $form->field($model,'bank_account')->input('number');?>
        </div>
    </div>

    <?= $form->field($model,'remark',[
        'labelOptions' => ['class' => 'col-sm-2 control-label text-right'],
        'template' =>'{label}<div class="col-sm-8">{input}</div>'
    ])->textarea();?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end();?>


