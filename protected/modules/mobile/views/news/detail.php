<div class="row well well-sm" style="padding-top:0px;margin-bottom:0px;">
    <h4 class=" text-center"><?=$model['title']?></h4>
    <div style="padding:0px;">
        <?php if(substr(strrchr ($attach['path'], '.'), 1)=="mp4"):?>
            <video  width="100%" style="max-height:400px;" class="pull-left" controls="controls">
                <source src="<?=Yii::app()->params['assets_path'].$attach['path']?>">
                浏览器不支持
            </video>
        <?php else:?>
            <img width="100%" style="max-height:400px;" src="<?=Yii::app()->params['assets_path'].$attach['path']?>" alt="..." class="img-rounded pull-left">
        <?php endif;?>
    </div>
</div>
<div id="list">
    <div class="list-group">
        <a class="list-group-item row">
            <span><?=$model['author'];?> <?=$model['public_date'];?></span>
        </a>
        <a class="list-group-item row">
            <?=$model['content'];?>
        </a>
    </div>
</div>
<span class="fixed_favor glyphicon glyphicon-circle-arrow-left" onclick="javascript:history.go(-1);"></span>
