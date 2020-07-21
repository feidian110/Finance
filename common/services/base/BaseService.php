<?php


namespace addons\Finance\common\services\base;


use addons\Crm\common\enums\CrmTypeEnum;
use addons\Finance\common\enums\FinanceTypeEnum;
use common\components\Service;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\merchant\Member;
use Yii;

class BaseService extends Service
{
    /**
     * 创建数据编号
     * @param $model
     * @param $crmType
     * @return int|string
     */
    public function createSn($model,$financeType)
    {
        $date = date('Ymd');
        $time['start'] = mktime(0, 0, 0, date('m'), date('d'), date('y'));
        $time['end'] = mktime(0, 0, 0, date('m'), date('d') + 1, date('y'))-1;

        $prefix = $this->getPrefix($financeType);
        $data = $model::find()->where(['merchant_id'=>$this->getMerchantId()])
            ->where(['between','created_at',$time['start'],$time['end']])
            ->orderBy(['created_at'=>SORT_DESC] )
            ->one();
        if( $data == null ){
            $sn = $prefix.$date.$this->getMerchantId().'1001';
        }else{
            $position = strpos($data['sn'],$prefix);
            $str = strlen($prefix);
            $count =substr_replace($data['sn'],"",$position,$str)+1;
            $sn =$prefix.$count;
        }
        return $sn;
    }

    /**
     * 获取Crm类型表单前缀
     * @param $crmType
     * @return bool|string
     */
    public function getPrefix($crmType)
    {
        switch ($crmType){
            case FinanceTypeEnum::RECEIPT :
                return "SKD_";
                break;
            case CrmTypeEnum::CUSTOMER:
                return "KH_";
                break;
            case CrmTypeEnum::BUSINESS:
                return "SJ_";
                break;
            case CrmTypeEnum::CONTACT:
                return "LXR_";
                break;
            case CrmTypeEnum::CONTRACT:
                return "HT_";
                break;
            case CrmTypeEnum::FOLLOW:
                return "GJ_";
                break;
            case CrmTypeEnum::PAY:
                return "FKD_";
                break;
        }
        return false;
    }



    public function getNormalStaff()
    {
        $storeId = $this->getStoreId();

        $member = Member::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->andWhere($storeId ? ['store_id'=>$storeId] : [] )
            ->andWhere(['=','status',StatusEnum::ENABLED])->asArray()->all();
        return ArrayHelper::map($member,'id','realname');
    }

    public function getStoreId()
    {
        $role = Yii::$app->services->rbacAuthRole->getRole();

        if($role['pid'] == 0 && in_array(Yii::$app->id, [AppEnum::MERCHANT, AppEnum::BACKEND])){
            return null;
        }
        return Yii::$app->user->identity->store_id;
    }

}