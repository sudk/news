<div class="row well well-sm" style="padding-top:0px;margin-bottom:0px;">
    <h4 class=" text-center"><?=$model['title']?></h4>
    <div style="padding:0px;">
        <img width="100%" style="max-height:200px;" src="<?=Yii::app()->params['assets_path'].$img['path']?>" alt="..." class="img-rounded pull-left">
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