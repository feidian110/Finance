<?php

namespace addons\Finance\common\models\capital;

use addons\Finance\common\models\base\Category;
use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_receipt_detail}}".
 *
 * @property string $id 主键
 * @property string $receipt_id 收款ID
 * @property string $customer_id 客户ID
 * @property string $order_id 订单ID
 * @property string $merchant_id 商户ID
 * @property string $store_id 门店ID
 * @property string $method_id 收款方式
 * @property string $account_id 收款账户
 * @property string $price 收款金额
 * @property string $remark 备注
 * @property string $creator_id 制单人
 * @property string $editor_id 编辑人
 * @property string $payee_id 收款人
 * @property string $auditor_id 审核人
 * @property int $status 状态
 * @property int $audit_status 审核状态
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 * @property int $audit_time 审核时间
 */
class ReceiptDetail extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_receipt_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_id', 'customer_id', 'order_id', 'merchant_id', 'store_id', 'method_id', 'account_id', 'creator_id', 'editor_id', 'payee_id', 'auditor_id', 'status', 'audit_status', 'created_at', 'updated_at', 'audit_time'], 'integer'],
            [['price'], 'number'],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    public function getMethod()
    {
        return $this->hasOne( Category::class,['id' => 'method_id'] );
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->creator_id = Yii::$app->user->getId();
            $this->editor_id = Yii::$app->user->getId();
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
            'receipt_id' => 'Receipt ID',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'merchant_id' => 'Merchant ID',
            'store_id' => 'Store ID',
            'method_id' => 'Method ID',
            'account_id' => 'Account ID',
            'price' => 'Price',
            'remark' => 'Remark',
            'creator_id' => 'Creator ID',
            'editor_id' => 'Editor ID',
            'payee_id' => 'Payee ID',
            'auditor_id' => 'Auditor ID',
            'status' => 'Status',
            'audit_status' => 'Audit Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'audit_time' => 'Audit Time',
        ];
    }
}
