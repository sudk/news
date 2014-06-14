<form name="_query_form" id="_query_form" action="javascript:itemQuery();">
    <li>
        <span class="sift-title">搜索：</span>
        <input name="q[title]" type="text" class="input_text grayTips mr5" placeholder='标题' />
        <input type="hidden" name="q[type]" id="q_type" value=''/>
        <input type="submit" value="" class="search_btn"/>
    </li>
</form>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">

    var itemQuery = function(){
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        //alert(url);
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
</script>