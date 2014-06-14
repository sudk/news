<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'form-container',
    'focus' => array($model, photo),
        ));

echo $form->activeHiddenField($model, 'op_id');
echo $form->activeHiddenField($model, 'name');
?>
<table class="formList">
	<tr>
        <td class="maxname">登录账号：</td>
        <td class="mivalue"><?php echo $model->login_name; ?></td>
        <td class="maxname">电话：</td>
        <td class="mivalue"><?php echo $form->activeTextField($model, 'phone', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 32), 'required&number'); ?></td>
    </tr>
    <tr>
        <td class="maxname">姓名：</td>
        <td class="mivalue"><?php echo $model->name; ?></td>
        <td class="maxname">E-Mail：</td>
        <td class="mivalue"><?php echo $form->activeTextField($model, 'email', array('title' => '请填写邮箱地址', 'class' => 'input_text'), 'email'); ?></td>
    </tr>
    <tr>
    	<td class="maxname">操作员类型：</td>
        <td class="mivalue">
            <?php echo Operator::getTypeRs($model->type); ?>
        </td>
        <td class="maxname">性别：</td>
        <td class="mivalue">
            <?php echo $form->activeDropDownList($model, 'sex', Operator::GetSex(), array(), 'required'); ?>
        </td>
        
    </tr>
   
    <tr>
        <td class="maxname">状态：</td>
        <td class="mivalue">
            <?php echo Operator::getStatusTitle($model->status); ?>
        </td>
        <td class="maxname">地址：</td>
        <td class="mivalue"><?php echo $form->activeTextField($model, 'addr', array('title' => '本项必填', 'class' => 'input_text'), ''); ?></td>
    </tr>
    <tr class="btnBox">
        <td colspan="4">
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
<link rel="stylesheet" type="text/css" href="js/JQwindow/windowCSS.css"/>
<script type="text/javascript" src="js/JQwindow/windowJS.js"></script>
<script type="text/javascript">
    var flag = true;
    function formSubmit() {
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
<?php
if ($msg['status']) {
    echo "setTimeout(hideMsg,3000);";
}
?>
</script>