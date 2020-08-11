<?php

namespace addons\Finance\common\models\capital;

use addons\Finance\common\models\base\Account;
use addons\Finance\common\models\base\Category;
use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_payment_detail}}".
 *
 * @property string $id 主键
 * @property string $merchant_id 商户ID
 * @property string $store_id 门店ID
 * @property string $payment_id 付款单ID
 * @property string $supplier_id 供应商ID
 * @property string $sn 编号
 * @property string $payment_date 单据时间
 * @property string $account_id 结算账户
 * @property string $settlement_method 结算方式
 * @property string $amount 付款金额
 * @property string $settlement_no 结算号
 * @property string $payer_id 付款人
 * @property string $remark 备注
 * @property string $creator_id 创建人
 * @property string $auditor_id 审核人
 * @property int $audit_time 审核时间
 * @property int $audit_status 审核状态
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 */
class PaymentDetail extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_payment_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'payment_id', 'supplier_id', 'account_id', 'settlement_method', 'creator_id', 'auditor_id', 'payer_id', 'audit_time', 'audit_status', 'created_at', 'updated_at'], 'integer'],
            [['payment_date'], 'safe'],
            [['sn'], 'string', 'max' => 32],
            [['amount'], 'number'],
            [['settlement_no'], 'string', 'max' => 64],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    public function getAccount()
    {
        return $this->hasOne( Account::class,['id' => 'account_id'] );
    }


    public function getMethod()
    {
        return $this->hasOne( Category::class,['id' => 'settlement_method'] );
    }


    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->creator_id = Yii::$app->user->getId();
        }
        return parent::beforeSave($insert);
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
            'payment_id' => 'Payment ID',
            'supplier_id' => 'Supplier ID',
            'sn' => 'Sn',
            'payment_date' => 'Payment Date',
            'account_id' => 'Account ID',
            'settlement_method' => 'Settlement Method',
            'settlement_no' => 'Settlement No',
            'remark' => 'Remark',
            'creator_id' => 'Creator ID',
            'auditor_id' => 'Auditor ID',
            'audit_time' => 'Audit Time',
            'audit_status' => 'Audit Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
