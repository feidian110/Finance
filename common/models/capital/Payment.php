<?php

namespace addons\Finance\common\models\capital;

use addons\Finance\common\models\base\Supplier;
use addons\Store\common\models\store\Store;
use common\behaviors\MerchantBehavior;
use common\models\merchant\Member;
use common\models\merchant\Merchant;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_payment}}".
 *
 * @property string $id 主键
 * @property string $merchant_id 商户ID
 * @property string $store_id 门店ID
 * @property string $supplier_id 供应商ID
 * @property string $sn 单据编号
 * @property string $amount 付款金额
 * @property string $bill_date 单据时间
 * @property string $remark 付款备注
 * @property string $creator_id 制单人
 * @property int $audit_status 审核状态[0:待审核，1：已审核]
 * @property int $status 状态：[0:待审核，1：待付款，2：已付款，-1：删除]
 * @property int $audit_time 审核时间
 * @property string $auditor_id 审核人
 * @property string $payer_id 付款人
 * @property int $pay_time 付款时间
 * @property int $pay_status 付款状态[0:待付款，1：已付款]
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $print_times 打印次数
 */
class Payment extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_payment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'supplier_id', 'creator_id', 'audit_status', 'status', 'audit_time', 'auditor_id', 'payer_id', 'pay_time', 'pay_status', 'sort', 'created_at', 'updated_at', 'print_times'], 'integer'],
            [['amount'], 'number'],
            [['bill_date', 'supplier_id', 'payer_id'], 'required'],
            [['bill_date'], 'safe'],
            [['sn'], 'string', 'max' => 32],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    public function create($data)
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $this->store_id = $data['Payment']['store_id'] ?? Yii::$app->user->identity->store_id;
            if(!$this->load($data) || !$this->save()){
                throw new \Exception($this->getErrors());
            }
            foreach ( $data['Payment']['detail'] as $item ){
                $detail = new PaymentDetail();
                $detail->store_id = $this->store_id;
                $detail->payment_id = $this->id;
                $detail->sn = $this->sn;
                $detail->payment_date = $this->bill_date;
                $detail->supplier_id = $this->supplier_id;
                $detail->account_id = $item['account_id'];
                $detail->settlement_method = $item['settlement_method'];
                $detail->settlement_no = $item['settlement_no'];
                $detail->amount = $item['amount'];
                $detail->payer_id = $this->payer_id;
                $detail->remark = $item['remark'];
                if(!$detail->save()){
                    throw new \Exception($detail->getErrors());
                }
                if( !Yii::$app->financeService->invoice->createCharge($detail) ){
                    throw new \Exception('报表错误');
                }
            }
            $trans->commit();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            return $e->getMessage();
        }
    }

    public function getMerchant()
    {
        return $this->hasOne(Merchant::class,['id' =>'merchant_id']);
    }

    public function getStore()
    {
        return $this->hasOne( Store::class,['id' => 'store_id'] );
    }

    public function getSupplier()
    {
        return $this->hasOne( Supplier::class,['id' => 'supplier_id'] );
    }

    public function getCreator()
    {
        return $this->hasOne( Member::class,['id' => 'creator_id'] );
    }

    public function getAuditor()
    {
        return $this->hasOne( Member::class,['id'  => 'auditor_id'] );
    }

    public function getDetail()
    {
        return $this->hasMany( PaymentDetail::class,['payment_id' => 'id'] );
    }

    public function getPayer()
    {
        return $this->hasOne( Member::class,['id'=>'payer_id'] );
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
            'store_id' => '所属门店',
            'supplier_id' => '供应商',
            'sn' => '单据编号',
            'amount' => '付款金额',
            'bill_date' => '单据日期',
            'remark' => '备注',
            'creator_id' => '制单人',
            'audit_status' => '审核状态',
            'status' => '单据状态',
            'audit_time' => '审核时间',
            'auditor_id' => '审核人',
            'payer_id' => '付款人',
            'pay_time' => '付款时间',
            'pay_status' => '付款状态',
            'detail' => '结算信息',
            'source' => '关联单据',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'print_times' => 'Print Times',
        ];
    }
}
