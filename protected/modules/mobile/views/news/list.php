<div id="list">
    <?php $this->actionGrid();?>
</div>
<script>
    var q=function(val){
        var url="./?r=mobile/news/list&q[search]="+val;
        window.location.href=url;
    }
</script>