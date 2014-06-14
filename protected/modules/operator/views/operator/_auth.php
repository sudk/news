<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'form-container',
    'focus' => array($model, name),
    'htmlOptions' => array(),
        ));
?>
<div id="content" style="width:580px;">
    <div class="tab-main">
        <table class="formList">
            <tr class="form-name">
                <td colspan="6"><?php echo $model->name; ?> 的权限管理</td>
            </tr>
            <tr>
                <td class="name"></td>
                <td class="value">已有的功能</td>
                <td class="value">可选的功能</td>
            </tr>

            <tr>
                <td class="name"></td>
                <td class="value">
                    <div id="taskBox-left" style=" float:left;width:250px;height:230px; border:1px solid gray;margin-right:10px;overflow-y:auto;">
                        <?php
                        $task_data = OperatorAuth::queryLeftTasks($model->login_name);
                        foreach ($task_data as $id => $name) {
                            if ($name != '')
                                echo '<span class="password_ico"></span>' . $name . '<span class="del_ico cursor" onclick=itemDelete("' . $id . '","task")></span><br/>';
                        }
                        ?>
                    </div>
                </td>

                <td class="value">
                    <div id="taskBox-right" style="float:left;width:250px;height:230px; border:1px solid gray;margin-right:10px;overflow-y:auto;">
                        <?php
                        $task_data = OperatorAuth::queryRightTasks($model->login_name);
                        foreach ($task_data as $id => $name) {
                            if ($name != '')
                                echo '<span class="password_ico"></span>' . $name . '<span class="add_ico cursor" onclick=itemNew("' . $id . '","task")></span><br/>';
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

    var itemNew = function(item,type){
        var url = '&confirm=1&q[login_name]=<?php echo $model->login_name; ?>&q[auth_id]='+item+"&q[auth_type]="+type;
        //url = encodeURI(url);
        //alert(url);
        jQuery.ajax({
            type: 'post',
            data:url,
            url:'./?r=operator/auth/new',
            dataType:"json",
            success: function(data,html){
                var data = eval(data);
                $("#" + type + "Box-left").html(data.left_data);
                $("#" + type + "Box-right").html(data.right_data);
            },
            //complete: function(XMLHttpRequest, textStatus){hideLoadingLayer();},
            error: function(){alert('请求失败');}
        })
    }

    var itemDelete = function(item,type){
        var url = '&confirm=1&q[login_name]=<?php echo $model->login_name; ?>&q[auth_id]='+item+"&q[auth_type]="+type;
        //url = encodeURI(url);

        jQuery.ajax({
            type: 'post',
            data:url,
            url:'./?r=operator/auth/delete',
            dataType:"json",
            success: function(data,html){
                var data = eval(data);
                $("#" + type + "Box-left").html(data.left_data);
                $("#" + type + "Box-right").html(data.right_data);
            },
            //complete: function(XMLHttpRequest, textStatus){hideLoadingLayer();},
            error: function(){alert('请求失败');}
        })
    }


</script>

