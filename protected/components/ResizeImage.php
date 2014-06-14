<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sudunkuai
 * Date: 11-10-18
 * Time: 上午11:14
 * To change this template use File | Settings | File Templates.
 */
 
class ResizeImage {
    public $res_path;  //原图片路径
    public $dst_path;  //目标图片路径
    public $res_name;
    public $dst_name;
    public $res_type;
    public $dst_type;
    public $max_width;
    public $max_height;
    public $res_x;  //截取原图的x坐标，默认0对整个图片缩放
    public $res_y;  //截取原图的y坐标，默认0对整个图片缩放
    public $res_w;  //截取原图的宽度,默认截取整个图片
    public $res_h;  //截取原图的高度,默认截取整个图片
   public function Resize($im,$max_width,$max_height,$name,$type,$res_x,$res_y,$res_w,$res_h){
        //取得当前图片大小
        $width = $res_w?$res_w:imagesx($im);
        $height = $res_h?$res_h:imagesy($im);
        //生成缩略图的大小
       // if((imagesx($im) > $max_width) || (imagesy($im) > $max_height)){
            $new_width=$max_width;
            $new_height=$max_height;

            if(function_exists("imagecopyresampled")){
                $new_im = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($new_im, $im, 0, 0, $res_x, $res_y, $new_width, $new_height, $width, $height);
            }else{
                $new_im = imagecreate($new_width, $new_height);
                imagecopyresized($new_im, $im, 0, 0, $res_x, $res_y, $new_width, $new_height, $width, $height);
            }
            ob_start();//输出图片，并放入i中。
            switch($type)
            {
                case "jpg" :imagejpeg($new_im,null,100);
                    break;
                case "png" :imagepng($new_im,null,9);
                    break;
                case "gif" :imagegif($new_im,null,100);
                    break;
            }
            $i = ob_get_clean();
            ImageDestroy($new_im);
            return $i;
            //ImageJpeg ($new_im,$name . ".jpg");
            //ImageDestroy ($new_im);
//         }else{
//             return $im;
//             //ImageJpeg ($im,$name . ".jpg");
//         }
    }
    public function runResize(){
        $filename=$this->res_path."/".$this->res_name.".".$this->res_type;
        if($this->dst_type=="jpg")
        {
            $im=imagecreatefromjpeg($filename);
        }
        if($this->dst_type=="png")
        {
            $im=imagecreatefrompng($filename);
        }
        if($this->dst_type=="gif")
        {
            $im=imagecreatefromgif($filename);
        }
        if($im)
        {
           $new_im=$this->Resize($im,$this->max_width,$this->max_height,$this->dst_name,$this->dst_type,$this->res_x,$this->res_y,$this->res_w,$this->res_h);
           //var_dump($new_im);exit; 
           return $this->storeFile($this->dst_path,$this->dst_name,$this->dst_type,$new_im);
        }else{
            return false;
        }
    }

    public function storeFile($path,$name,$type,$file){
        if(!is_dir($path))
        {
            mkdir($path);
        }
        $filename=$path."/".$name.".".$type;
        $of=fopen($filename,"w");
        if(fwrite($of,$file))
        {
            fclose($of);
            return $filename;
        }else{
            return false;
        }
    }
    
}
