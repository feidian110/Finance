<?php


namespace addons\Finance\merchant\modules\report\controllers;


use addons\Crm\common\enums\CrmTypeEnum;
use addons\Crm\common\enums\CustomerStatusEnum;
use addons\Crm\common\models\customer\Customer;
use addons\Finance\common\enums\BillTypeEnum;
use addons\Finance\merchant\controllers\BaseController;
use common\enums\StatusEnum;
use Yii;

class CapitalController extends BaseController
{
    public function actionReceivables()
    {

        $list1 = Customer::find()->select('id,title,merchant_id,receivables_balance')
            ->where(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andWhere( $this->getStoreId() ? ['store_id' => $this->getStoreId()] : [])
            ->andWhere(['>=','status',CustomerStatusEnum::DISABLED])
            ->asArray()->all();
        $sum1 = $sum2 = $sum3 = $sum4 = $sum5  = 0;

        $merchant_id = Yii::$app->services->merchant->getId();

        if( $this->getStoreId() ){
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,customer_id,sn,bill_date,bill_type,sum(amount_receivable) as amount_receivable,sum(income) as income from {{%addon_finance_invoice}} where merchant_id=".$merchant_id." and store_id=".$this->getStoreId()." and status=".StatusEnum::ENABLED."  group by customer_id,id with rollup" )
                ->queryAll();
        }else{
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,customer_id,sn,bill_date,bill_type,sum(amount_receivable) as amount_receivable,sum(income) as income from {{%addon_finance_invoice}} where merchant_id=".$merchant_id." and status=".StatusEnum::ENABLED."  group by customer_id,id with rollup" )
                ->queryAll();
        }



        foreach ( $list1 as $arr => $row ){
            $v[$arr]['code'] = $row['id'];
            $v[$arr]['customerTitle']     = $row['title'];
            $v[$arr]['customerCate']      = "";
            $v[$arr]['billDate'] = "";
            $v[$arr]['billNo']      = '期初余额';
            $v[$arr]['transType']    = "";
            $v[$arr]['receivables']      = "";
            $v[$arr]['advanceCharge'] = "";
            $v[$arr]['receivableBalance'] = $row['receivables_balance'];
            $v[$arr]['remark']      = '';

            foreach ( $list2 as $arr1 => $row1 ){
                $arr = time() + $arr1;
                if ($row['id'] == $row1['customer_id'] ) {
                    $sum1 += $a1 = $row1['amount_receivable']>0 ? abs($row1['amount_receivable']) : 0;
                    $sum2 += $a2 = $row1['income']>0 ? abs($row1['income']) : 0;
                    $a3 = $row['receivables_balance'] + $sum1 - $sum2;
                    $v[$arr]['code'] = $row1['id'] ? $row['id'] : "";
                    $v[$arr]['customerTitle']     = $row1['id'] ? $row['title'] : "";
                    $v[$arr]['customerCate']      = $row1['id'] ? "" : "";
                    $v[$arr]['billDate'] = $row1['id'] ? $row1['bill_date'] : "";
                    $v[$arr]['billNo'] = $row1['id'] ? $row1['sn'] : "小计：";
                    $v[$arr]['transType'] = $row1['id'] ? BillTypeEnum::getValue($row1['bill_type']) : "";
                    $v[$arr]['receivables']  = number_format($row1['amount_receivable'],2);
                    $v[$arr]['advanceCharge'] = number_format($row1['income'],2);
                    $v[$arr]['receivableBalance'] = $row1['id'] ? number_format($a3,2) : number_format($row['receivables_balance']+$row1['amount_receivable']-$row1['income'],2);
                    $v[$arr]['remark']      = '';
                }

            }

            $sum3 += $sum1;
            $sum4 += $sum2;
            $sum1 = $sum2 = 0;
        }

        $data['list'] = isset($v) ? array_values($v) :'';
        $data['total']['receivables'] = number_format(Yii::$app->financeService->invoice->getReceivablesSum(),2);
        $data['total']['income'] = number_format(Yii::$app->financeService->invoice->getAdvanceChargeSum(),2);
        $data['total']['balance'] = number_format(Yii::$app->financeService->invoice->getReceivableBalance(),2);


        return $this->render( $this->action->id,[
            'data' => $data
        ] );
    }
}