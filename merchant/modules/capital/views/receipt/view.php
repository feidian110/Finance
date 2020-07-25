<?php

use addons\Crm\common\enums\NatureEnum;
use addons\Finance\common\enums\AuditStatusEnum;
use addons\Finance\common\enums\ReasonEnum;
use common\helpers\Html;

$this->title = "查看";
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
            <div style="width: 75%; float: left">
                <h3 style="font-size: 18px; font-weight: bold"><?=$model['merchant']['title'].'-'.$model['store']['title'].'收款票据';?></h3>
                <h5>票据日期：<?=$model['receipt_date'];?></h5>
            </div>
            <div style="width: 25%; float: left; line-height: 50px">
                <h5>No.：<?=$model['sn'];?></h5>
            </div>
        </div>
        <div id="form1">
            <table style="border-top: 1px solid black ; border-bottom: 1px solid black">
                <tr>
                    <td>客户信息</td>
                    <td>
                        <table>
                            <tr>
                                <td>兹收到</td>
                                <td colspan="4"><?php if( $model['order']['nature_id'] == NatureEnum::BIRTHDAY ){
                                        echo '寿星：'. $model['groom_name'] ;
                                    }elseif ( $model['order']['nature_id'] == NatureEnum::MARRY || $model['order']['nature_id'] == NatureEnum::JOINT ){
                                        echo '新郎：'.$model['order']['groom_name'] .'&'.'新娘：'.$model['order']['bride_name'];
                                    }elseif ($model['order']['nature_id'] == NatureEnum::BACK){
                                        echo '新娘：'.$model['order']['bride_name'] .'&'. '新郎：'.$model['order']['groom_name'];
                                    }?></td>
                            </tr>
                            <tr>
                                <td>收款摘由</td>
                                <td colspan="4"><?= $model['order']['title'].'-活动承办' .ReasonEnum::getValue($model['receipt_reason']);?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>收款信息</td>
                    <td>
                        <table>
                            <?php foreach ( $model['profile'] as $item ):?>
                                <tr>
                                    <td>收款方式</td>
                                    <td><?=$item['method']['title'];?></td>
                                    <td>人民币:<?=Yii::$app->financeService->base->num_to_rmb($item['price']);?></td>
                                    <td>￥:<?=$item['price'];?>元</td>
                                </tr>
                            <?php endforeach;?>
                            <tr>
                                <td></td>
                                <td class="text-right">合计：</td>
                                <td>人民币：<?=Yii::$app->financeService->base->num_to_rmb($model['receipt_price']);?></td>
                                <td>￥:<?=$model['receipt_price'];?>元</td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td></td>
                    <td>会计：</td>
                    <td></td>
                    <td>审核人：<?=$model['auditor']['realname'];?></td>
                    <td></td>
                    <td>收款人：<?=$model['payee']['realname'];?></td>
                    <td></td>
                    <td>制单人：<?=$model['creator']['realname'];?></td>
                </tr>
            </table>
        </div>
        <div style="height: 60px; line-height: 60px; text-align: center;">
            <?= Html::a($model['audit_status'] == AuditStatusEnum::ENABLED ? "<i class='fa fa-fw fa-question-circle'> 反审核" : "</i><i class='fa fa-fw fa-check'></i> 审核",['audit','id'=>$model['id'],'status'=> $model['audit_status']== AuditStatusEnum::ENABLED ? AuditStatusEnum::DISABLED : AuditStatusEnum::ENABLED],['class'=> 'btn btn-warning']);?>
            <?= Html::a('<i class="fa fa-fw fa-print"></i> 打印','javascript:prn1_preview()',['class'=> 'btn btn-info']);?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>
</div>





<?php
$js = <<<JS
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
        LODOP.ADD_PRINT_BARCODE(40,662,140,25,"Code39","{$model['sn']}");
        LODOP.ADD_PRINT_TEXT(54,334,182,25,"票据日期："+ '{$model['receipt_date']}');
        LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
        
        LODOP.ADD_PRINT_TEXT(40,624,49,32,"No.");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",15);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(317,42,368,20,"打印时间："+(new Date()).toLocaleDateString()+" "+(new Date()).toLocaleTimeString());
        LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
        LODOP.ADD_PRINT_HTM(80,50,810,200,strFormHtml);
        LODOP.SET_PRINT_PAGESIZE(0,2410,933.3,"A4");
		LODOP.SET_PREVIEW_WINDOW(0,0,0,960,470,"");

	};	
JS;
$this->registerJs($js);
?>
