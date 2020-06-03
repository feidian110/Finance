<?php

namespace addons\Finance\common\models\base;

use common\behaviors\MerchantBehavior;
use common\traits\MerchantCurd;
use common\traits\Tree;
use Yii;

/**
 * This is the model class for table "{{%addon_finance_category}}".
 *
 * @property string $id 主键
 * @property string $title 名称
 * @property string $pid 父级ID
 * @property string $path 路径
 * @property int $level 级别
 * @property string $tree 树
 * @property int $sort 排序
 * @property int $status 状态[0:停用,1:正常,-1:删除]
 * @property string $remark 备注
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 * @property string $merchant_id 商户
 * @property string $store_id 门店
 * @property int $type 类型
 */
class Category extends \common\models\base\BaseModel
{
    use MerchantBehavior,Tree;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_finance_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['pid', 'level', 'sort', 'status', 'created_at', 'updated_at', 'merchant_id', 'store_id', 'type'], 'integer'],
            [['title'], 'string', 'max' => 60],
            [['path'], 'string', 'max' => 200],
            [['tree'], 'string', 'max' => 5000],
            [['remark'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'pid' => 'Pid',
            'path' => 'Path',
            'level' => 'Level',
            'tree' => 'Tree',
            'sort' => 'Sort',
            'status' => 'Status',
            'remark' => '备注',
            'created_at' => '创建时间',
            'updated_at' => '最后更新时间',
            'merchant_id' => 'Merchant ID',
            'store_id' => '所属门店',
            'type' => 'Type',
        ];
    }
}
