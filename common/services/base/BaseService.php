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

    public function num_to_rmb($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        }else{
            return $c . "整";
        }
    }


}