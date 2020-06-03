<?php

use yii\widgets\ActiveForm;
?>


<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'options' => ['class'=> 'form-horizontal'],
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
    ]
]);?>
    <div class="modal-body">
        <?= $form->field($model, 'sn')->input('number',['value' => $model->isNewRecord ? Yii::$app->financeService->account->authCode() :$model->sn]);?>
        <?php if( !$storeId ){echo $form->field($model,'store_id')->dropDownList($store);} ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'cate_id')->dropDownList($cate);?>
        <?= $form->field($model,'init_balance')->input('number',['value' => $model['init_balance'] ==0 ? "" : $model['init_balance']]);?>
        <?= $form->field($model, 'init_date')->widget(kartik\date\DatePicker::class, [
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
                'value' => date('Y-m-d',time())
            ]
        ]);?>

        <?= $form->field($model, 'remark')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>

<?php ActiveForm::end(); ?>