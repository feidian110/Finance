<?php
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'col-sm-2 control-label text-right'],
        'template' => "{label}<div class='col-sm-9'>{input}{hint}{error}</div>",
    ]
]);?>
<div class="modal-body">
    <?= $form->field($model, 'store_id')->dropDownList($store) ?>
    <?= $form->field($model, 'pid')->dropDownList($cateDropDownList) ?>
    <?= $form->field($model, 'title')->textInput(); ?>
    <?= $form->field($model, 'remark')->textarea(['rows'=>5]);?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
