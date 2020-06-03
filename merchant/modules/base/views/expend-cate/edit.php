<?php
$this->title = "编辑支出类别";
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><?= $this->title;?></h4>
</div>
<?= $this->render( '_form',[
    'model' => $model,
    'storeId' =>$storeId,
    'store' =>$store
] )?>
