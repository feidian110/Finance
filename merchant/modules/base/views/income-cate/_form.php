<?php
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'col-sm-3 control-label text-right'],
        'template' => "{label}<div class='col-sm-8'>{input}{hint}{error}</div>",
    ]
]);?>
<div class="modal-body">
    <?php if( !$storeId ){echo $form->field($model,'store_id')->dropDownList($store);} ?>
    <?= $form->field($model, 'title')->label('收入类别')->textInput() ?>
    <?= $form->field($model, 'remark')->textarea();?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end();?>
