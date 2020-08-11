<?php

$this->title = "应收款明细表";
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
                    <tr style="<?php if( $item['billNo'] == '小计：' ){echo "font-weight: bold;color: #000000";}?>">
                        <td class="text-center"><?= $item['code'] ?? "";?></td>
                        <td class="text-center"><?= $item['customerTitle'] ?? "";?></td>
                        <td class="text-center"><?= $item['billDate'] ?? "";?></td>
                        <td class="text-center"><?= $item['billNo'] ?? "";?></td>
                        <td class="text-center"><?= $item['transType'] ?? "";?></td>
                        <td class="text-right"><?= $item['receivables']==0 ? "" : $item['receivables'];?></td>
                        <td class="text-right" style="<?php if($item['advanceCharge']>0){echo "color: #228B22";}?>"><?= $item['advanceCharge'] == 0 ? "" : $item['advanceCharge'];?></td>
                        <td class="text-right" style="<?php if($item['receivableBalance']<0){echo "color: #B22222";}?>"><?= $item['receivableBalance'] == 0 ? "" : $item['receivableBalance'];?></td>
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

