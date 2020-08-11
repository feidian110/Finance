<?php

use addons\Crm\common\enums\NatureEnum;
use addons\Finance\common\enums\AuditStatusEnum;
use addons\Finance\common\enums\ReasonEnum;
use common\helpers\Html;

$this->title = "付款单查看";
?>
<style id="style1">
    table{
        width: 100%;
    }
    tr{
        height: 27px;
    }
    td{
        padding-left: 10px;
        height: 20px;
        font-size: 13px;
        font-family: 黑体;
    }

</style>
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <div style="width: 100%; text-align: center;">
            <h3 style="font-size: 18px; font-weight: bold"><?=$model['merchant']['title'].'-'.$model['store']['title'].'付款票据';?></h3>
            <table>
                <tr>
                    <td colspan="2" style="text-align: left; width: 30%">供应商：<?=$model['supplier']['title'];?></td>
                    <td colspan="2" style="text-align: left; width: 30%">单据日期：<?= $model['bill_date'];?></td>
                    <td colspan="2" style="text-align: left; width: 30%">单据编号：<?=$model['sn'];?></td>
                </tr>
            </table>
        </div>
        <?php if( $model['audit_status'] == AuditStatusEnum::ENABLED ):?>
            <div class="has-audit"></div>
        <?php endif;?>
        <div id="form1">
            <table style="border-top: 1px solid black ; border-bottom: 1px solid black;">
                <tr>
                    <td colspan="6" style="text-align: center;">结算信息</td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table id="detail" class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th width="50">序号</th>
                                <th width="150">结算账户</th>
                                <th width="100">付款金额</th>
                                <th width="100">结算方式</th>
                                <th width="150">结算号</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($model['detail'] as $item):?>
                            <tr>
                                <td></td>
                                <td style="text-align: left"><?= $item['account']['title'];?></td>
                                <td>￥<?=$item['amount'];?></td>
                                <td><?=$item['method']['title'];?></td>
                                <td><?=$item['settlement_no'];?></td>
                                <td><?=$item['remark'];?></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="height: 30px">
                    <td colspan="2" style="width: 30%">申请人:<?=$model['creator']['realname'];?></td>
                    <td colspan="2" style="width: 30%">付款(合计)金额:￥<?= $model['amount'];?></td>
                    <td colspan="2" style="width: 30%;">金额大写(人民币):<?=Yii::$app->financeService->base->num_to_rmb($model['amount']);?></td>
                </tr>
                <tr style="height: 40px;">
                    <td colspan="2">审核状态:</td>
                    <td colspan="2">审核时间:</td>
                    <td colspan="2">创建日期:<?= Yii::$app->formatter->asDatetime($model['created_at']);?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td></td>
                    <td>会计：</td>
                    <td></td>
                    <td>审核人：<?=$model['auditor']['realname'] ?? "";?></td>
                    <td></td>
                    <td>付款人：<?=$model['payer']['realname'] ?? "";?></td>
                    <td></td>
                    <td>制单人：<?=$model['creator']['realname'];?></td>
                    <td></td>
                    <td>领款人签字：</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <div style="height: 60px; line-height: 60px; text-align: center;">
            <?= Html::a($model['audit_status'] == AuditStatusEnum::ENABLED ? "<i class='fa fa-fw fa-question-circle'></i> 反审核" : "<i class='fa fa-fw fa-check'></i> 审核",['audit','id'=>$model['id'],'status'=> $model['audit_status']== AuditStatusEnum::ENABLED ? AuditStatusEnum::DISABLED : AuditStatusEnum::ENABLED],['class'=> 'btn btn-warning']);?>
            <?= Html::a('<i class="fa fa-fw fa-print"></i> 打印','javascript:prn1_preview()',['class'=> 'btn btn-info']);?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>
</div>





<?php
$js = <<<JS

  var len = $('#detail tr').length;
        for(var i = 1;i<len;i++){
            $('#detail tr:eq('+i+') td:first').text(i);
        }
    var LODOP; //声明为全局变量 
    function prn1_preview() {
		CreateOneFormPage();
		LODOP.PREVIEW();	
	};
    function CreateOneFormPage(){
        var title = "{$model['merchant']['title']}"+"【"+"{$model['store']['title']}"+"】"+"收款票据";
		LODOP=getLodop();
		var strBodyStyle="<style>"+document.getElementById("style1").innerHTML+"</style>";
		var strFormHtml=strBodyStyle+"<body>"+document.getElementById("form1").innerHTML+"</body>";
		LODOP.PRINT_INITA(0,-4,916,354,title);
		LODOP.ADD_PRINT_TEXT(21,174,454,36,title);
		LODOP.SET_PRINT_STYLEA(0,"FontName","楷体");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
        LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
       
        LODOP.ADD_PRINT_HTM(80,50,810,200,strFormHtml);
        LODOP.SET_PRINT_PAGESIZE(0,2410,933.3,"A4");
		LODOP.SET_PREVIEW_WINDOW(0,0,0,960,470,"");

	};	
JS;
$this->registerJs($js);
?>
