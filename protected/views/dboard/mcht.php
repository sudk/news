
<div class="clearfix" >
    <div class="index_c_big" >
        <div class="index_c_big_top"></div>
        <div class="index_c_big_main" >
        	<div style='width:60%;float:left;'>
        		<h1><img src="images/icons/index_1.png" />商户信息</h1>
            	<div class="clear"></div>
            	<ul class="list" style="overflow-y:auto;height:200px;">
                 	<li style='width:90%;'>商户编号：<?php echo $mchtinfo['mchtid'];?></li>
                	<li style='width:90%;'>商户名称：<?php echo $mchtinfo['mchtname'];?></li>
                	<li style='width:90%;'>电话：<?php echo $mchtinfo['tel'];?></li>
                	<li style='width:90%;'>联系人：<?php echo $mchtinfo['contactor'];?></li>
                	<li style='width:90%;'>地区：<?php echo $mchtinfo['area_full_name'];?></li>
                	<li style='width:90%;'>地址：<?php echo $mchtinfo['mchtaddr'];?></li>
            	</ul>
            	
        	</div>
        	<div style='width:40%;float:left;'>
        		<h1><img src="images/icons/index_1.png" />POS概述</h1>
            	<div class="clear"></div>
            	<ul class="list" style="overflow-y:auto;height:200px;">
                 	<li>正常POS数：<span style='color:blue;'><?php echo $normal_cnt;?></span></li>
                 	<li>禁用POS数：<span style='color:red;'><?php echo $cancel_cnt;?></span></li>
                	<li>审核中POS数：<span><?php echo $check_cnt;?></span></li>
                	<li>审核未通过POS数：<span style='color:red;'><?php echo $check_false_cnt;?></span></li>
                	<li>待安装POS数：<span ><?php echo $install_cnt;?></span></li>
                	<li>安装失败POS数：<span style='color:red;'><?php echo $install_false_cnt;?></span></li>
            	</ul>
        	</div>
            
        </div>
        <div class="index_c_big_bottom"></div>
    </div>
</div>
<div class="index_c_big">
	<div class="index_c_big_top"></div>
	<div class="index_c_big_main">
		<h1 >友情提示</h1>
        <div class="clear"></div>
        <ul class="list" style="overflow-y:auto;height:auto;">
            <li style='width:90%'>1.每天10点之后才能查询前一天的POS交易记录。</li>
            <li style='width:90%'>2.对账时，如果发现某日的交易记录不存在，请点击【POS交易明细->日报表->手动获取交易记录】。</li>
        </ul>
    </div>
	<div class="index_c_big_bottom"></div>
</div>
<div class="index_c_big">
	<div class="index_c_big_top"></div>
	<div class="index_c_big_main">
		<ul style='height:auto;'>
            <?php if(Yii::app()->user->checkAccess('reconcile/dayrpt/list')):?>
        	<li>
            	<img src="images/48/01.png" />
                <h1><a href="./?r=reconcile/dayrpt/list">POS交易明细</a></h1>
            </li>
            <?php endif;?>
        </ul>
    </div>
	<div class="index_c_big_bottom"></div>
</div>

