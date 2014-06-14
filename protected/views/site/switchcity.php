<?php
//var_dump(Yii::app()->session['city_list']);
if(is_array(Yii::app()->session['city_list'])){
	$i = 0;
	foreach(Yii::app()->session['city_list'] as $code => $name)
	{
		if($i%3==0)
			echo "<p style='padding-left:10px;'>";
		echo '<a style="display:inline-block;width:100px;height:30px;" href="index.php?r=site/switchcity&city_code='.$code.'">'.$name.'</a>';
		if(($i+1)%3==0||($i==count(Yii::app()->session['city_list'])-1))
			echo "</p>";
		
		$i++;
	}
}


