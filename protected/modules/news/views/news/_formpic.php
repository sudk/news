<tr>
    <td class="maxname" ></td>
    <td class="mivalue" >
        <input type='file' name='attach' id="attach" class='input_text'/>
        <span class="sBtn">
				<a class="left" onclick="ajaxUpload();">开始上传</a><a class="right"></a>
		</span>
        <span style='margin-left:10px;' id='upmsg'></span>
        <span style='margin:10px;' id='attach_list'>

            <?php if($attach):?>
                <?php if(substr(strrchr ($attach['path'], '.'), 1)=="mp4"):?>
                    <p>
                        <input type='hidden' name='attaches[]' value='<?=$attach['path']?>'>
                        <video  height='240' controls='controls'>
                        <source src='./images/attach/<?=$attach['path']?>' type='video/mp4'>
                        浏览器不支持</video>
                    </p>
                    <?php else:?>
                    <p>
                        <input type='hidden' name='attaches[]' value='<?=$attach['path']?>'>
                        <img style='height:240px;'  src='./images/attach/<?=$attach['path']?>' />
                    </p>
                    <?php endif;?>
            <?php endif;?>
        </span>
    </td>
</tr>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
    //ajax上传图片
    function ajaxUpload(){
        $("#upmsg").html("<img src='images/loading.gif' style='vertical-align:middle;' width='16px' height='16px'>正在上传请稍候...");
        jQuery.ajaxFileUpload({
            url:'./index.php?r=site/fileupload',
            secureuri:false,
            fileElementId:'attach',
            dataType: 'json',
            success: function (data, status) {
                if (data.status==1) {
                    if(data.type=='mp4'){
                        var n="<p>" +
                            "<input type='hidden' name='attaches[]' value='"+data.savename+"'>" +
                            "<video  height='240' controls='controls'>" +
                            "<source src='"+data.url+"' type='video/mp4'>"+
                            "浏览器不支持</video>" +
                            "</p>";
                    }else{
                        var n="<p><input type='hidden' name='attaches[]' value='"+data.savename+"'><img style='height:240px;' src='"+data.url+"' /></p>";
                    }
                    $("#attach_list").html(n);
                } else if(data.status==-1) {
                    $("#upmsg").html(data.msg).css("color","#ff0000");
                }

            },
            complete:function(data){
                $("#upmsg").html("");
                $("#attach").val(null);
            },
            error: function () {
                alert("上传失败");
            }
        });
        return false;
    }
</script>