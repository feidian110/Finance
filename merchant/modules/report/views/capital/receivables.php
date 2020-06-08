<?php
use common\enums\BillTypeEnum;
USE common\enums\CustomerTypeEnum;

$this->title = Yii::t('app', 'Receivables Detail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Receivables Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title;?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead style="background: rgb(240,240,240)">
                    <tr>
                        <th class="text-center">编号</th>
                        <th class="text-center" width="300">客户</th>
                        <th class="text-center" width="150">单据日期</th>
                        <th class="text-center">单据编号</th>
                        <th class="text-center">业务类型</th>
                        <th class="text-center">增加应收款</th>
                        <th class="text-center">增加预收款</th>
                        <th class="text-center">应收款余额</th>
                        <th class="text-center" width="400">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $data['list'] as $item ):?>
                    <tr>
                        <td class="text-center"><?= $item['code'] ?? "";?></td>
                        <td class="text-center"><?= $item['customerTitle'] ?? "";?></td>
                        <td class="text-center"><?= $item['billDate'] ?? "";?></td>
                        <td class="text-center"><?= $item['billNo'] ?? "";?></td>
                        <td class="text-center"><?= $item['transType'] ?? "";?></td>
                        <td class="text-right"><?= $item['receivables'] ?? "";?></td>
                        <td class="text-right"><?= $item['advanceCharge'] ?? "";?></td>
                        <td class="text-right"><?= $item['receivableBalance'] ?? "";?></td>
                        <td><?= $item['remark'] ?? "";?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tfoot style="background: rgb(240,240,240)">
                    <tr>
                        <td colspan="4" class="text-center"><label class="direct-chat-name">合计：</label></td>
                        <td></td>
                        <td class="text-right"><label class="direct-chat-name"><?= $data['total']['receivables'] ?? "";?></label></td>
                        <td class="text-right"><label class="direct-chat-name"><?= $data['total']['income'] ?? "";?></label></td>
                        <td class="text-right"><label class="direct-chat-name"><?= $data['total']['balance'] ?? "";?></label></td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#">«</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">»</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

