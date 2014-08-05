<div  class="list-group" style="margin-bottom:0px;">
<?php $i=1; foreach($rs['rows'] as $row):?>
    <?php $attach=BaseAttach::MakeURL($attachs,$row['news_id']);?>
    <?php if($i==1&&$_GET['page']==1):?>
        <a class="list-group-item list-group-item-sm row" href="./?r=mobile/news/detail&id=<?=$row['news_id']?>">
            <div class="col-xs-12" style="padding:0px;vertical-align:middle;">
                <?php if(substr(strrchr ($attach, '.'), 1)=="mp4"):?>
                    <video  width="100%" controls="controls">
                        <source src="<?=$attach?>">
                        浏览器不支持
                    </video>
                <?php else:?>
                    <img width="100%" height="200px" src="<?=$attach?>" alt="..." class="img-rounded">
                <?php endif;?>
            </div>
        </a>
    <?php else:?>
        <a class="list-group-item list-group-item-sm row" href="./?r=mobile/news/detail&id=<?=$row['news_id']?>">
            <div class="col-xs-3" style="padding:0px;vertical-align:middle;">
                <?php if(substr(strrchr ($attach, '.'), 1)=="mp4"):?>
                    <video  width="70px" height="65px" class="pull-left">
                        <source src="<?=$attach?>">
                        浏览器不支持
                    </video>
                <?php else:?>
                    <img width="70px" height="65px" src="<?=$attach?>" alt="..." class="img-rounded pull-left">
                <?php endif;?>
            </div>
            <div class="col-xs-9" style="padding-left:0px;">
                <h5><?=Utils::mbsub($row['title'],19)?></h5>
                <p></p>
                <p class="text-muted"><small><?=$row['public_date']?></small><small class="btn btn-info btn-xs pull-right">图</small></p>
            </div>
        </a>
    <?php endif;?>
<?php $i++; endforeach;?>
<?php $this->widget('MobileLinkPager', array('id' => 'list','page_num' =>$rs['page_num'],'total_num' =>$rs['total_num'],'num_of_page'=>$rs['num_of_page'],'order'=>$rs['order'],'url'=>$rs['url'])); ?>
</div>