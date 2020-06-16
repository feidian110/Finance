<?php

namespace addons\Finance\common\models\base;

use addons\Finance\common\enums\FinanceCateEnum;
use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_supplier}}".
 *
 * @property string $id 主键
 * @property string $merchant_id 所属商家
 * @property string $store_id 所属门店
 * @property string $inter_id 互联ID
 * @property string $supplier_id 供应商D
 * @property string $title 供应商名称
 * @property string $contact 联系人
 * @property string $mobile 手机号码
 * @property string $address 经营地址
 * @property string $init_payable 初始应付款
 * @property string $init_payment 初始预付款
 * @property string $init_balance 初始余额
 * @property string $balance_date 余额日期
 * @property string $category_id 分类ID
 * @property string $remark 备注
 * @property int $account_type 账户类型
 * @property string $bank_account 银行账户
 * @property string $account_name 账户名称
 * @property string $bank_name 银行名称
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 */
class Supplier extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'contact'], 'required'],
            [['merchant_id', 'store_id', 'inter_id', 'supplier_id', 'category_id', 'account_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['inter_id'], 'uniqueTitle','on' => 'default'],
            [['init_payable', 'init_payment', 'init_balance'], 'number'],
            [['balance_date'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['contact', 'bank_account'], 'string', 'max' => 30],
            [['mobile', 'bank_name'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 2000],
            [['account_name'], 'string', 'max' => 50],
        ];
    }

    public function uniqueTitle($attribute)
    {
        $model = self::find()
            ->where(['inter_id' => $this->inter_id, 'supplier_id' => $this->supplier_id])
            ->andFilterWhere(['merchant_id'=>$this->merchant_id,'store_id'=>$this->store_id])
            ->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '别名已存在');
        }
    }

    public function getCategory()
    {
        return $this->hasOne( Category::class,['id' => 'category_id'] )->where(['type'=>FinanceCateEnum::SUPPLIER]);
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
            'inter_id' => '企业互联',
            'supplier_id' => 'Supplier ID',
            'title' => '供应商名称',
            'contact' => '联系人',
            'mobile' => '手机号码',
            'address' => '经营地址',
            'init_payable' => '期初应付款',
            'init_payment' => '期初预付款',
            'init_balance' => '应付款余额',
            'balance_date' => '余额日期',
            'category_id' => '分类',
            'remark' => '备注',
            'account_type' => '账户类型',
            'bank_account' => '银行账户',
            'account_name' => '账户名称',
            'bank_name' => '银行名称',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
