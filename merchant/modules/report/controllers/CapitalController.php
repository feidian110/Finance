<?php


namespace addons\Finance\merchant\modules\report\controllers;


use addons\Crm\common\enums\CrmTypeEnum;
use addons\Crm\common\enums\CustomerStatusEnum;
use addons\Crm\common\models\customer\Customer;
use addons\Finance\common\enums\BillTypeEnum;
use addons\Finance\common\models\base\Account;
use addons\Finance\common\models\base\Supplier;
use addons\Finance\common\models\report\Invoice;
use addons\Finance\merchant\controllers\BaseController;
use common\enums\StatusEnum;
use Yii;

class CapitalController extends BaseController
{
    /**
     * 现金银行报表
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionCashBank()
    {
        $list1 = Account::find()->select('id,sn,title,merchant_id,current_balance')
            ->where(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['>=','status',CustomerStatusEnum::DISABLED])
            ->asArray()->all();
        $sum1 = $sum2 = $sum3 = $sum4 = $sum5  = 0;
        $list2 = Yii::$app->getDb()
            ->createCommand("select id,account_id,sn,bill_date,bill_type,sum(amount_receivable) as amount_receivable,sum(income) as income,sum(expend) as expend from {{%addon_finance_invoice}} where merchant_id=".$this->getMerchantId()." and status=".StatusEnum::ENABLED." and (`bill_type` LIKE '%INCOME%' OR `bill_type` LIKE '%EXPEND%') group by account_id,id with rollup")
            ->queryAll();

        foreach ( $list1 as $arr => $row ){
            $v[$arr]['code'] = $row['sn'];
            $v[$arr]['title'] = $row['title'];
            $v[$arr]['billDate'] = "";
            $v[$arr]['billNo'] = "";
            $v[$arr]['transType'] = "期初余额";
            $v[$arr]['income'] = "";
            $v[$arr]['expend'] = "";
            $v[$arr]['balance'] = $row['current_balance'];
            $v[$arr]['contacts'] = "";
            $v[$arr]['owner'] = "";
            foreach ( $list2 as $arr1 => $row1 ){
                $arr = time() + $arr1;
                if ($row['id'] == $row1['account_id'] ) {
                    $sum1 += $a1 = $row1['income']>0 ? abs($row1['income']) : 0;
                    $sum2 += $a2 = $row1['expend']>0 ? abs($row1['expend']) : 0;
                    $a3 = $row['current_balance'] + $sum1 - $sum2;

                    $v[$arr]['code'] = $row1['id'] ? $row['sn'] : "";
                    $v[$arr]['title']     = $row1['id'] ? $row['title'] : "";
                    $v[$arr]['billDate'] = $row1['id'] ? $row1['bill_date'] : "";
                    $v[$arr]['billNo'] = $row1['id'] ? $row1['sn'] : "";
                    $v[$arr]['transType'] = $row1['id'] ? BillTypeEnum::getValue($row1['bill_type']) : "小计：";;
                    $v[$arr]['income'] = $row1['income'];
                    $v[$arr]['expend'] = $row1['expend'];
                    $v[$arr]['balance'] = $row1['id'] ? number_format($a3,2) : number_format($row['current_balance']+$row1['income']-$row1['expend'],2);
                    $v[$arr]['contacts'] = $row1['id'] ? Yii::$app->financeService->invoice->getContactByBillType($row1['bill_type'],$row1['id']) : "";
                    $v[$arr]['owner'] = $row1['id'] ? Yii::$app->financeService->invoice->getOwnerByBillType($row1['bill_type'],$row1['id']) : "";
                }
            }
            $sum3 += $sum1;
            $sum4 += $sum2;
            $sum1 = $sum2 = 0;
        }
        $data['list'] = isset($v) ? array_values($v) :'';

        return $this->render( $this->action->id,[
            'model' => $data
        ] );
    }

    /**
     * 应收款报表
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReceivables()
    {

        $list1 = Customer::find()->select('id,title,merchant_id,receivables_balance')
            ->where(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andWhere( $this->getStoreId() ? ['store_id' => $this->getStoreId()] : [])
            ->andWhere(['>=','status',CustomerStatusEnum::DISABLED])
            ->asArray()->all();
        $sum1 = $sum2 = $sum3 = $sum4 = $sum5  = 0;

        if( $this->getStoreId() ){
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,customer_id,sn,bill_date,bill_type,sum(amount_receivable) as amount_receivable,sum(income) as income from {{%addon_finance_invoice}} where merchant_id=".$this->getMerchantId()." and store_id=".$this->getStoreId()." and status=".StatusEnum::ENABLED."  group by customer_id,id with rollup" )
                ->queryAll();
        }else{
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,customer_id,sn,bill_date,bill_type,sum(amount_receivable) as amount_receivable,sum(income) as income from {{%addon_finance_invoice}} where merchant_id=".$this->getMerchantId()." and status=".StatusEnum::ENABLED."  group by customer_id,id with rollup" )
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

    /**
     * 应付账款报表
     * @return string
     */
    public function actionPayable()
    {
        $list1 = Supplier::find()
            ->where(['merchant_id' => $this->getMerchantId()])
            ->andWhere( $this->getStoreId() ? ['store_id' => $this->getStoreId()] : [])
            ->andWhere(['>=','status',CustomerStatusEnum::DISABLED])
            ->asArray()->all();
        $sum1 = $sum2 = $sum3 = $sum4 = $sum5  = 0;
        if( $this->getStoreId() ){
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,supplier_id,sn,bill_date,bill_type,sum(amount_payable) as amount_payable,sum(expend) as expend from {{%addon_finance_invoice}} where merchant_id=".$this->getMerchantId()." and store_id=".$this->getStoreId()." and status=".StatusEnum::ENABLED."  group by supplier_id,id with rollup" )
                ->queryAll();
        }else{
            $list2 = Yii::$app->getDb()
                ->createCommand("select id,supplier_id,sn,bill_date,bill_type,sum(amount_payable) as amount_payable,sum(expend) as expend from {{%addon_finance_invoice}} where merchant_id=".$this->getMerchantId()." and status=".StatusEnum::ENABLED."  group by supplier_id,id with rollup" )
                ->queryAll();
        }
        foreach ( $list1 as $arr => $row ){
            $v[$arr]['code'] = $row['id'];
            $v[$arr]['title']     = $row['title'];
            $v[$arr]['cate']      = "";
            $v[$arr]['billDate'] = "";
            $v[$arr]['billNo']      = '期初余额';
            $v[$arr]['transType']    = "";
            $v[$arr]['payable']      = "";
            $v[$arr]['advance'] = "";
            $v[$arr]['payableBalance'] = $row['init_balance'];
            $v[$arr]['remark']      = '';

            foreach ( $list2 as $arr1 => $row1 ){
                $arr = time() + $arr1;
                if ($row['id'] == $row1['supplier_id'] ) {
                    $sum1 += $a1 = $row1['amount_payable']>0 ? abs($row1['amount_payable']) : 0;
                    $sum2 += $a2 = $row1['expend']>0 ? abs($row1['expend']) : 0;
                    $a3 = $row['init_balance'] + $sum1 - $sum2;
                    $v[$arr]['code'] = $row1['id'] ? $row['id'] : "";
                    $v[$arr]['title']     = $row1['id'] ? $row['title'] : "";
                    $v[$arr]['cate']      = $row1['id'] ? "" : "";
                    $v[$arr]['billDate'] = $row1['id'] ? $row1['bill_date'] : "";
                    $v[$arr]['billNo'] = $row1['id'] ? $row1['sn'] : "小计：";
                    $v[$arr]['transType'] = $row1['id'] ? BillTypeEnum::getValue($row1['bill_type']) : "";
                    $v[$arr]['payable']  = number_format($row1['amount_payable'],2);
                    $v[$arr]['advance'] = number_format($row1['expend'],2);
                    $v[$arr]['payableBalance'] = $row1['id'] ? number_format($a3,2) : number_format($row['init_balance']+$row1['amount_payable']-$row1['expend'],2);
                    $v[$arr]['remark']      = '';
                }

            }

            $sum3 += $sum1;
            $sum4 += $sum2;
            $sum1 = $sum2 = 0;
        }
        $data['list'] = isset($v) ? array_values($v) :'';
        return $this->render( $this->action->id,[
            'model' => $data
        ] );
    }

    public function actionOtherReceivables()
    {
        return $this->render($this->action->id);
    }

    public function actionOtherPayable()
    {
        return $this->render( $this->action->id );
    }

    public function actionProfit()
    {
        return $this->render( $this->action->id );
    }
}