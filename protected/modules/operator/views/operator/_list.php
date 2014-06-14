<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {
        //$t->begin_row("onclick", "getDetail(this,'{$row['op_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $link = "";
        if($row['login_name']=='admin'||$row['login_name']==Yii::app()->user->id)
        	$link = "<i>无操作</i>";
        else{
        	
        	if(Yii::app ()->user->checkAccess ( "operator/operator/edit" ))
        		$link = CHtml::link('编辑', "javascript:itemEdit('{$row['op_id']}')", array());
        	if(Yii::app ()->user->checkAccess ( "operator/operator/auth" ))
        		$link .= CHtml::link('权限', "javascript:authEdit('{$row['op_id']}','{$row['name']}')", array());
        	if(Yii::app ()->user->checkAccess ( "operator/operator/pwd" ))
        		$link .= CHtml::link('重置密码', "javascript:itemPwd('{$row['op_id']}')", array());
        	if(Yii::app ()->user->checkAccess ( "operator/operator/del" ))
        		$link .= CHtml::link('删除', "javascript:itemDelete('{$row['op_id']}')", array());
        	
        	
        }
        //$t->echo_td($num);
        $t->echo_td($row['op_id']);
        $t->echo_td(Operator::getTypeRs($row['type']));
        $t->echo_td($row['name']); 
        $t->echo_td($row['login_name']); 
        $t->echo_td($row['phone']);
        $status_desc = Operator::getStatusTitle($row['status']);
        if($row['status']==Operator::STATUS_NORMAL)
        	$status_desc = "<span class='colGreen'>$status_desc</span>";
        if($row['status']==Operator::STATUS_FREEZE)
        	$status_desc = "<span class='colRed'>$status_desc</span>";
        $t->echo_td($status_desc);
        $t->echo_td($row['dttm']);
        $t->echo_td($link);
        $t->end_row();
    }
}
$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>
<div class="page">
    <div class="floatL">共 <strong><?php echo $cnt; ?></strong>&nbsp;条</div>
    <div class="pageNumber">
<?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
    </div>
</div>
<div class="alternate-rule" style="display: none;"></div>