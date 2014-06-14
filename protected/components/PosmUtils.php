<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-7-12
 * Time: 上午11:18
 * To change this template use File | Settings | File Templates.
 */
class PosmUtils
{
    public static function  DF_Accbank($accbank){
         return "<span title='{$accbank}'>".mb_substr($accbank,0,5,"UTF-8")."</span>";
    }
    public static function  DF_Accnum($accnum){
        return Yii::app()->user->data['auth']["smanager"]?$accnum:substr_replace($accnum,"****",-8,4);
    }
    public static function  DF_Mchtname($mchtname){
        return "<span title='{$mchtname}'>".mb_substr($mchtname,0,10,"UTF-8")."</span>";
    }
    public static function  DF_Contacter($contacter,$tel){
        return "<span title='联系人：{$contacter}，电话：{$tel}'>".$contacter."</span>";
    }
    public static function  DF_Addr($addr){
        return "<span title='{$addr}'>".mb_substr($addr,0,15,"UTF-8")."</span>";
    }
    public static function  DF_Area($area_full_name){
        if(!$area_full_name){
            return "<span title='没有设定地区' style='color:red;'>没有设定地区</span>";
        }
        return "<span title='{$area_full_name}'>".mb_substr($area_full_name,3,6,"UTF-8")."</span>";
    }
    public static function  DF_MsgTitle($title){
        if(!$title){
            return "<span title='没有标题' style='color:red;'>没有标题</span>";
        }
        return "<span title='{$title}'>".mb_substr($title,0,15,"UTF-8")."</span>";
    }
}
