    <tr>
    	<td class="maxname" >附件：</td>
    	<td class="mivalue" >
    		<input type='file' name='attach' id="attach" class='input_text' onchange="ajaxUpload()"/>
    		<span style='margin-left:10px;' id='upmsg'>

    		</span>
            <p>
                <span style='margin-left:10px;' id='attach_list'>

    		    </span>
            </p>
    	</td>
    </tr>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
//ajax上传图片
function ajaxUpload(){
    $("#upmsg").html("<img src='images/loading.gif' style='vertical-align:middle;' width='16px' height='16px'>正在上传请稍候...");
    jQuery.ajaxFileUpload({
        url:'./index.php?r=site/picupload',
        secureuri:false,
        fileElementId:'attach',
        dataType: 'json',
        success: function (data, status) {
            if (data.status==1) {
                var h=$("#attach_list").html();
                var n="<p><span>"+data.filename+"<input type='hidden' name='attaches[]' value='"+data.filename+"'></span></p>";
                $("#attach_list").html(h+n);
            } else if(data.status==-1) {
            	$("#upmsg").html(data.msg).css("color","#ff0000");
            }
        },
        complete:function(data){
            $("#upmsg").html("");
        },
        error: function () {
            alert("上传失败");
        }
    });
    return false;
}
</script>