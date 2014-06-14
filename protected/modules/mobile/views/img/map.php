<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;} @media (max-device-width: 780px){#golist{display: block!important;}}#golist {display: none;}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=wIoGZpBRPoxmoEM3FbZU1ZlL&v=1.0"></script>
<title><?=$_GET['merchant_name']?></title>
</head>
<body>
<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">

// 百度地图API功能
var map = new BMap.Map("allmap");
map.addControl(new BMap.ZoomControl());          //添加地图缩放控件
// 创建地址解析器实例
var myGeo = new BMap.Geocoder();
// 将地址解析结果显示在地图上,并调整地图视野
myGeo.getPoint("<?=$_GET['merchant_addr']?>", function(point){
  if (point) {
    map.centerAndZoom(point, 16);
    map.addOverlay(new BMap.Marker(point));
  }
}, "济南市");
</script>
