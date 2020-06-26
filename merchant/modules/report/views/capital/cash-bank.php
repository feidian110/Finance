<?php
$this->title = "现金银行报表";
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
                        <th class="text-center">账户编号</th>
                        <th class="text-center" width="200">账户名称</th>
                        <th class="text-center" width="150">单据日期</th>
                        <th class="text-center">单据编号</th>
                        <th class="text-center" width="120px">业务类型</th>
                        <th class="text-center" width="120px">收入</th>
                        <th class="text-center" width="120px">支出</th>
                        <th class="text-center" width="120px">账户余额</th>
                        <th class="text-center" width="300">往来单位</th>
                        <th class="text-center" width="120">收/付款人</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $model['list'] as $item ):?>
                        <tr>
                            <td class="text-center"><?= $item['code'] ?? "";?></td>
                            <td class="text-center"><?= $item['title'] ?? "";?></td>
                            <td class="text-center"><?= $item['billDate'] ?? "";?></td>
                            <td class="text-center"><?= $item['billNo'] ?? "";?></td>
                            <td class="text-center"><?= $item['transType'] ?? "";?></td>
                            <td class="text-right"><?= $item['income']==0 ? "" : $item['income'];?></td>
                            <td class="text-right"><?= $item['expend'] == 0 ? "" : $item['expend'];?></td>
                            <td class="text-right"><?= $item['balance'] == 0 ? "" : $item['balance'];?></td>
                            <td><?=$item['contacts'];?></td>
                            <td class="text-center"><?= $item['owner'] ?? "";?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tfoot style="background: rgb(240,240,240)">
                    <tr>
                        <td colspan="4" class="text-center"><label class="direct-chat-name">合计：</label></td>
                        <td></td>
                        <td class="text-right"><label class="direct-chat-name">0.00</label></td>
                        <td class="text-right"><label class="direct-chat-name">0.00</label></td>
                        <td class="text-right"><label class="direct-chat-name">0.00</label></td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
