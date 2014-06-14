<div  class="list-group" style="margin-bottom:5px;">
    <div class="list-group-item list-group-item-sm row">
        <?php foreach($rs['rows'] as $row):?>
        <a class="col-xs-6" href="./?r=mobile/img/detail&id=<?=$row['id']?>" style="margin-bottom:10px;">
            <img width="100%" height="100%" src="<?=Yii::app()->params['assets_path'].$row['path']?>" alt="..." class="img-thumbnail">
        </a>
        <?php endforeach;?>
    </div>
<?php $this->widget('MobileLinkPager', array('id' => 'list','page_num' =>$rs['page_num'],'total_num' =>$rs['total_num'],'num_of_page'=>$rs['num_of_page'],'condition'=>$rs['condition'],'order'=>$rs['order'],'url'=>$rs['url'])); ?>
</div>