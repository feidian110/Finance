<?php

$this->title = "应付款明细表";
$this->params['breadcrumbs'][] = ['label' => "报表管理"];
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
                        <th class="text-center">编码</th>
                        <th class="text-center" width="300">供应商</th>
                        <th class="text-center" width="150">供应商类别</th>
                        <th class="text-center" width="150">单据日期</th>
                        <th class="text-center">单据编号</th>
                        <th class="text-center">业务类型</th>
                        <th class="text-center">增加应付款</th>
                        <th class="text-center">增加预付款</th>
                        <th class="text-center">应付款余额</th>
                        <th class="text-center" width="400">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $model['list'] as $item ):?>
                        <tr>
                            <td class="text-center"><?= $item['code'] ?? "";?></td>
                            <td class="text-center"><?= $item['title'] ?? "";?></td>
                            <td class="text-center"><?= $item['cate'] ?? "";?></td>
                            <td class="text-center"><?= $item['billDate'] ?? "";?></td>
                            <td class="text-center"><?= $item['billNo'] ?? "";?></td>
                            <td class="text-center"><?= $item['transType'] ?? "";?></td>
                            <td class="text-right"><?= $item['payable']==0 ? "" : $item['payable'];?></td>
                            <td class="text-right"><?= $item['advance'] == 0 ? "" : $item['advance'];?></td>
                            <td class="text-right"><?= $item['payableBalance'] == 0 ? "" : $item['payableBalance'];?></td>
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
        </div>
    </div>
</div>
