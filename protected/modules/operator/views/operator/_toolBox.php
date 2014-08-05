<form name="_query_form" id="_query_form" action="javascript:itemQuery();">
    <li>
        <span class="sift-title">搜索：</span>
        
        <input name="q[name]" type="text" class="input_text grayTips mr5" value='姓名' />
        <input name="q[login_name]" type="text" class="input_text grayTips" value='登录账号' />
        <input type="hidden" name="q[type]" id="q_type" value=''/>
        <input type="submit" value="" class="search_btn"/>
    </li>
</form>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery($("a[name='Types[]']")).click(function(){
            var qvalue = jQuery(this).attr("qvalue");
            if(qvalue != '')
            {
                jQuery("#q_type").attr("value",qvalue);
            }else{
                jQuery("#q_type").attr("value","");
            }
            jQuery($("a[name='Types[]']")).removeClass('air');
            jQuery(this).addClass('air');
            itemQuery();
        });
    });
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