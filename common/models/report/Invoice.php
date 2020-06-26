<?php

namespace addons\Finance\common\models\report;

use addons\Crm\common\models\customer\Customer;
use addons\Finance\common\enums\BillTypeEnum;
use addons\Finance\common\models\base\Supplier;
use common\behaviors\MerchantBehavior;
use common\models\merchant\Member;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_invoice}}".
 *
 * @property string $id 主键
 * @property string $merchant_id 商户
 * @property string $store_id 门店
 * @property string $customer_id 客户ID
 * @property string $supplier_id 供应商
 * @property string $obj_id 单据ID（合同，收款，工单，付款）
 * @property string $sn 单据编号
 * @property string $bill_date 单据日期
 * @property int $trans_type 操作类型
 * @property string $amount_receivable 应收金额
 * @property string $amount_payable 应付金额
 * @property string $income 收入
 * @property string $expend 支出
 * @property string $current_arrears 本次欠款
 * @property string $owner_id 收付款人员
 * @property int $status 状态[0:待审，1,正常]
 * @property string $creator_id 操作人员
 * @property string $account_id 结算账户
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 */
class Invoice extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'customer_id', 'supplier_id', 'obj_id', 'trans_type', 'owner_id', 'status', 'creator_id', 'account_id', 'created_at', 'updated_at'], 'integer'],
            [['bill_date'], 'safe'],
            [['amount_receivable', 'amount_payable', 'income', 'expend', 'current_arrears'], 'number'],
            [['sn'], 'string', 'max' => 64],
        ];
    }

    public function getCustomer()
    {
        return $this->hasOne( Customer::class,['id' => 'customer_id'] );
    }

    public function getSupplier()
    {
        return $this->hasOne( Supplier::class,['id' =>'supplier_id'] );
    }

    public function getOwner()
    {
        return $this->hasOne( Member::class,['id' => 'owner_id'] );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'store_id' => 'Store ID',
            'customer_id' => 'Customer ID',
            'supplier_id' => 'Supplier ID',
            'obj_id' => 'Obj ID',
            'sn' => 'Sn',
            'bill_date' => 'Bill Date',
            'trans_type' => 'Trans Type',
            'amount_receivable' => 'Amount Receivable',
            'amount_payable' => 'Amount Payable',
            'income' => 'Income',
            'expend' => 'Expend',
            'current_arrears' => 'Current Arrears',
            'owner_id' => 'Owner ID',
            'status' => 'Status',
            'creator_id' => 'Creator ID',
            'account_id' => 'Account ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
