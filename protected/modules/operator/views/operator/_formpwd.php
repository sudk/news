
<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'form-container',
    'focus' => array($model, name),
        ));

echo $form->activeHiddenField($model, 'op_id', array(), '');

?>
<table class="formList">
    <tr>
        <td class="maxname">姓名：</td>
        <td class="mivalue"><?php echo $model -> name; ?></td>
    </tr>    
   
    <tr class="line">
        <td class="maxname">登录账号：</td>
        <td class="mivalue">
            <?php echo $model -> login_name;?>
        </td>
    </tr>
    <tr>
        <td class="maxname">登录密码：</td>
        <td class="mivalue"><?php echo $form->activePasswordField($model, 'new_password', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 16), 'required'); ?></td>
        
    </tr>
    <tr>
    	<td class="maxname">确认登录密码：</td>
        <td class="mivalue"><?php echo $form->activePasswordField($model, 'confirm_password', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 16), 'required'); ?></td>
    </tr>
   
    <tr class="btnBox">
        <td colspan="2">
            <span class="sBtn">
                <a class="left" href="javascript:formSubmit();">保存</a><a class="right"></a>
            </span>
            <span class="sBtn-cancel">
                <a class="left" href="javascript:formReset();">重置</a><a class="right"></a>
            </span>
        </td>
    </tr>
   
</table>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    var flag = true;
    function formSubmit() {
        checkMyForm();
        if (flag)
            $("form:first").submit();
        else
            flag=true;
    }
    function formReset() {
        document.getElementById("form1").reset();
    }
    function hideMsg() {
        $("#msg").hide("slow");
    }
   
    function checkMyForm(){
        var e = $("#Operator_new_password");
        var pass = $("#Operator_new_password").val();
        var pass_c = $("#Operator_confirm_password").val();
        if(pass!=pass_c){
            flag = false;
            e.addClass('input_error iptxt');
            e.showTip({flagInfo:"两次输入密码不一致！"});
            e.focus();
        }
    }
 
    
<?php
if ($msg['status']) {
    echo "setTimeout(hideMsg,3000);";
}
?>
    
</script>