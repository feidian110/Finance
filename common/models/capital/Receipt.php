<?php

namespace addons\Finance\common\models\capital;

use addons\Crm\common\models\contract\Contract;
use addons\Crm\common\models\customer\Customer;
use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_receipt}}".
 *
 * @property string $id 主键
 * @property string $merchant_id 商户
 * @property string $store_id 门店
 * @property string $sn 收款编码
 * @property string $customer_id 客户
 * @property string $order_id 订单
 * @property string $receipt_date 收款日期
 * @property string $receipt_price 收款金额
 * @property string $receipt_reason 收款摘由
 * @property int $direction 入账方向
 * @property string $remark 备注
 * @property int $status 状态
 * @property string $creator_id 制单人
 * @property string $editor_id 编辑人
 * @property string $payee_id 收款人
 * @property string $auditor_id 审核人
 * @property int $audit_status 审核状态[0:待审核1:已审核]
 * @property int $audit_time 审核时间
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 */
class Receipt extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_receipt}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'customer_id', 'order_id', 'receipt_reason', 'direction', 'status', 'creator_id', 'editor_id', 'payee_id', 'auditor_id', 'audit_status', 'audit_time', 'created_at', 'updated_at'], 'integer'],
            [['sn', 'receipt_date','customer_id', 'order_id'], 'required'],
            [['receipt_date'], 'safe'],
            [['receipt_price'], 'number'],
            [['sn'], 'string', 'max' => 64],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    public function create($data)
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $order = Contract::findOne($data['Receipt']['order_id']);
            $this->store_id = $order['store_id'];
            if(!$this->load($data) || !$this->save()){
                throw new \Exception($this->getErrors());
            }

            foreach ($data['Receipt']['detail'] as $item){
                $profile = new ReceiptDetail();
                $profile->receipt_id = $this->id;
                $profile->sn = $this->sn;
                $profile->receipt_date = $this->receipt_date;
                $profile->store_id = $this->store_id;
                $profile->customer_id = $this->customer_id;
                $profile->order_id = $this->order_id;
                $profile->account_id = $item['account_id'];
                $profile->method_id = $item['method_id'];
                $profile->price = $item['price'];
                $profile->remark = $item['remark'];
                $profile->payee_id = $this->payee_id;
                if(!$profile->save()){
                    throw new \Exception($profile->getErrors());
                }
                if( !Yii::$app->financeService->invoice->createAdvance($profile) ){
                    throw new \Exception('报表错误');
                }
            }

            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollBack();
            return $e->getMessage();
        }
        return true;
    }

    public function getCustomer()
    {
        return $this->hasOne( Customer::class,['id'=>'customer_id'] );
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
            'merchant_id' => 'Merchant ID',
            'store_id' => '门店',
            'sn' => '单据编号',
            'customer_id' => '客户信息',
            'order_id' => '订单信息',
            'receipt_date' => '单据日期',
            'receipt_price' => '收款金额',
            'receipt_reason' => '收款摘由',
            'direction' => '入账方向',
            'remark' => '备注',
            'status' => '状态',
            'creator_id' => '制单人',
            'editor_id' => 'Editor ID',
            'payee_id' => '收款人',
            'auditor_id' => '审核人',
            'audit_status' => '审核状态',
            'audit_time' => '审核时间',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'detail' => '收款明细',
        ];
    }
}
