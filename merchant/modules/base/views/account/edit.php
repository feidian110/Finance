<?php



/* @var $this yii\web\View */
/* @var $model common\models\merchant\Unit */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Edit Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><?= $this->title; ?></h4>
    </div>
    <?= $this->render('_form',[
        'model' => $model,
        'cate' => $cate
    ])?>
</div>


