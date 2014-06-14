<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
echo "签名密钥:"."32#af*dsf";
echo "<br>";
echo "源加密串:"."email=test@msn.com&service=create_direct_pay_by_user";
echo "<br>";
echo "排序后待加密串:"."email=test@msn.com&service=create_direct_pay_by_user32#af*dsf";
echo "<br>";
$str=md5("email=test@msn.com&service=create_direct_pay_by_user32#af*dsf");
echo "加密后的密文:".$str;
echo "<br>";
echo "加密后的传输串:"."email=".urlencode("test@msn.com")."&service=create_direct_pay_by_user&sign_type=MD5&sign=$str";
echo "<br>";
echo sha1("sudunkuai");
$ar=array('1','2','3','4','5');
print_r($ar);
$br=$ar;
$br[0]=9;
print_r($ar);
echo "------------------------------------<br>";
print_r(explode("%","0.5%"));
?>
<div style='width: %100'>
	<bdo dir='ltl'>我是谁！</bdo>
</div>

<canvas id="myCanvas">
your browser does not support the canvas tag
</canvas>
<script type="text/javascript">
function drawCanvas()
{
var canvas=document.getElementById('myCanvas');
var ctx=canvas.getContext('2d');
ctx.fillStyle='#FF0000';
ctx.fillRect(0,0,100,100);
}
drawCanvas();
</script>
</body>