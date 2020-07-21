<?php
namespace addons\Finance\common\services\report;

use addons\Crm\common\models\customer\Customer;
use addons\Finance\common\enums\BillTypeEnum;
use addons\Finance\common\models\report\Invoice;
use common\components\Service;
use common\enums\StatusEnum;
use Yii;

class InvoiceService extends Service
{
    /**
     * 增加应收款记录
     * @param $data
     * @return array|bool
     */
    public function createReceivables($data)
    {
        $model = new Invoice();
        $model -> obj_id = $data['id'];
        $model -> customer_id = $data['customer_id'];
        $model -> sn = $data['sn'];
        $model -> bill_date = date( 'Y-m-d',$data['sign_time'] );
        $model -> bill_type = BillTypeEnum::SALE;
        $model -> amount_receivable	= $data['contract_price'];
        $model -> current_arrears =   - $data['contract_price'];
        $model -> creator_id = Yii::$app->user->getId();
        $model -> owner_id = $data['owner_id'];
        $model -> store_id = $data['store_id'] ?? 0;
        if( !$model ->save() ){
            return $model->getErrors();
        }
        return true;
    }

    public function createPayable($data)
    {
        $model = new Invoice();
        $model -> obj_id = $data['id'];
        $model -> supplier_id = $data['supplier_id'];
        $model -> sn = $data['sn'];
        $model -> bill_date = date( 'Y-m-d',$data['execute_date'] );
        $model -> bill_type = BillTypeEnum::WORKS;
        $model -> amount_payable =$data['price'];
        $model -> current_arrears = $data['price'];
        $model -> creator_id = Yii::$app->user->getId();
        $model -> owner_id = $data['owner_id'];
        $model -> store_id = $data['store_id'] ?? 0;
        if( !$model ->save() ){
            return $model->getErrors();
        }
        return true;
    }

    public function createAdvance($data)
    {
        $model = new Invoice();
        $model -> obj_id = $data['receipt_id'];
        $model ->profile_id = $data['id'];
        $model -> customer_id = $data['customer_id'];
        $model -> account_id = $data['account_id'];
        $model -> sn = $data['sn'];
        $model -> bill_date = $data['receipt_date'];
        $model -> bill_type = BillTypeEnum::INCOME;
        $model -> income	= $data['price'];
        $model -> creator_id = Yii::$app->user->getId();
        $model -> owner_id = $data['payee_id'];
        $model -> store_id = $data['store_id'] ?? 0;
        if( !$model ->save() ){
            return $model->getErrors();
        }
        return true;
    }

    /**
     * 应收款总和
     * @return mixed
     */
    public function getReceivablesSum()
    {
        $sum = Invoice::find()
            ->where(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['=','status',StatusEnum::ENABLED])
            ->sum('amount_receivable');
        return $sum;
    }

    /**
     * 预收款总和
     * @return mixed
     */
    public function getAdvanceChargeSum()
    {
        $sum = Invoice::find()
            ->where(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['>=','status',StatusEnum::DISABLED])
            ->sum('income');
        return $sum;
    }

    /**
     * 客户期初预收款总和
     * @return mixed
     */
    public function getOpeningBalanceSum()
    {
        $sum = Customer::find()
            ->where(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['>=','status',StatusEnum::DISABLED])
            ->sum('receivables_balance');
        return $sum;
    }

    public function getReceivableBalance()
    {
        $data = $this->getOpeningBalanceSum() + $this->getReceivablesSum() - $this->getAdvanceChargeSum();
        return $data;
    }

    /**
     * 根据单据类型获取往来单位信息
     * @param $billType
     * @param $objId
     * @return mixed|string
     */
    public function getContactByBillType($billType,$objId)
    {
        $invoice = $this->getInvoice($objId);
        if( $billType == BillTypeEnum::INCOME ){
            return $invoice['customer']['title'];
        }elseif($billType == BillTypeEnum::EXPEND){
            return $invoice['supplier']['title'];
        }
        return "";
    }

    /**
     * 获取单据信息
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getInvoice($id)
    {
        return Invoice::find()
            ->where(['id' =>$id])
            ->andWhere(['merchant_id' =>$this->getMerchantId()])
            ->andWhere(['status' =>StatusEnum::ENABLED])
            ->one();
    }

    /**
     * 根据单据类型 获取单据负责人信息
     * @param $billType
     * @param $objId
     * @return mixed
     */
    public function getOwnerByBillType($billType,$objId)
    {
        $invoice = $this->getInvoice($objId);
        return $invoice['owner']['realname'];
    }

    
}