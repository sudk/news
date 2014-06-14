<?php

/**
 * Description of Statsystem
 *
 * @author sudk
 */
class TransrecordCommand extends CConsoleCommand
{
    /*获得刷卡记录*/
    public function actionGet($mchtid="",$recorddate="")
    {
        if($recorddate=="")
        {
            $recorddate=date("Ymd",strtotime("-1 days"));

        }
        echo '开始获取交易记录,可能要花几分钟时间....';
        $r=Transrecord::getRemoteData($recorddate,$mchtid);
        print_r($r);
    }
    public function actionCheck($recorddate="")
    {
        if($recorddate=="")
        {
            $recorddate=date("Ymd",strtotime("-1 days"));

        }
        echo '开始获取交易记录,可能要花几分钟时间....';
        Transrecord::checkRemoteData($recorddate);
        echo "获取交易记录完成！";
    }
}
