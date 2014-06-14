<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;

        $link = CHtml::link('编辑', "javascript:itemEdit('{$row['news_id']}')", array());
        $link .= CHtml::link('删除', "javascript:itemDelete('{$row['news_id']}')", array());
        $t->echo_td($num);
        $t->echo_td($row['title']);
        $t->echo_td($row['author']);
        $t->echo_td($row['public_date']);
        $t->echo_td($row['to_top']);
        $t->echo_td($row['type']);
        $t->echo_td($row['record_time']);
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