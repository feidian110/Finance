<?php

namespace addons\Finance\common\models\base;

use addons\Finance\common\enums\FinanceCateEnum;
use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_account}}".
 *
 * @property string $id 主键
 * @property string $sn 账户编码
 * @property string $title 标题
 * @property string $cate_id 账户分类
 * @property string $init_balance 期初余额
 * @property string $init_date 期初日期
 * @property string $income 收入
 * @property string $expend 支出
 * @property string $current_balance 当前余额
 * @property string $remark 备注
 * @property string $merchant_id 商户ID
 * @property string $store_id 门店ID
 * @property int $sort 排序
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Account extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sn', 'title', 'init_date'], 'required'],
            [['sn'], 'uniqueCode'],
            [['cate_id', 'merchant_id', 'store_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['init_balance', 'income', 'expend', 'current_balance'], 'number'],
            [['init_date'], 'safe'],
            [['sn'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 60],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    public function uniqueTitle($attribute)
    {
        $model = self::find()->where([
            'merchant_id' => Yii::$app->services->merchant->getId(),
            'store_id' =>$this->store_id,
            'title' => $this->title
        ])->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '该账户名称已存在');
        }
    }

    public function uniqueCode($attribute)
    {
        $model = self::find()->where([
            'merchant_id' => Yii::$app->services->merchant->getId(),
            'store_id' => $this->store_id,
            'sn' => $this->sn
        ])->one();

        if ($model && $model->id != $this->id) {
            $this->addError($attribute, '该编码已存在');
        }
    }

    public function getCategory()
    {
        return $this->hasOne( Category::className(),['id' => 'cate_id'] )->where(['type' => FinanceCateEnum::ACCOUNT]);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sn' => '账户编码',
            'title' => '账户名称',
            'cate_id' => '账户分类',
            'init_balance' => '期初余额',
            'init_date' => '初始日期',
            'income' => '收入',
            'expend' => '支出',
            'current_balance' => '当前余额',
            'remark' => '备注',
            'merchant_id' => 'Merchant ID',
            'store_id' => '所属门店',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
