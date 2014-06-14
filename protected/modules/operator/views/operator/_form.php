<style type="text/css">
    .city_list {
        text-align:left;
        list-style-type:none;
        width:260px;
    }
    .city_list li {
        display:inline-block;
        list-style-type:none;
        padding: 3px;
    }
</style>
<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'form-container',
    'focus' => array($model, name),
        ));
if ($_mode_ == 'modify') {
    echo $form->activeHiddenField($model, 'op_id', array(), '');
}


?>
<table class="formList">
    <tr>
        <td class="maxname">姓名：</td>
        <td class="mivalue">
        <?php 
        	echo $form->activeTextField($model, 'name', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 32), 'required'); 
        	echo "&nbsp;<span class='colRed'>*</span>";
        	?>
        </td>
        <td class="maxname">性别：</td>
        <td class="mivalue">
            <?php echo $form->activeDropDownList($model, 'sex', Operator::GetSex(), array(), 'required'); ?>
        </td>
    </tr>    
    <tr>
        <td class="maxname">电话：</td>
        <td class="mivalue">
        <?php 
        	echo $form->activeTextField($model, 'phone', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 32), 'required&number'); 
        	echo "&nbsp;<span class='colRed'>*</span>";
        ?></td>
        <td class="maxname">E-Mail：</td>
        <td class="mivalue"><?php echo $form->activeTextField($model, 'email', array('title' => '请填写邮箱地址', 'class' => 'input_text'), 'email'); ?></td>
    </tr>
    <tr>
        <td class="maxname">操作员类型：</td>
        <td class="mivalue">
            <?php echo $form->activeDropDownList($model, 'type', Operator::getTypeRs(), array('title' => '本项必选', 'class' => 'input_text', 'readonly' => "true"), 'required'); ?>
        </td>
        
        <td class="maxname">登录账号：</td>
        <td class="mivalue">
            <?php
            if ($_mode_ == 'insert') {
                echo $form->activeTextField($model, 'login_name', array('title' => '本项必填', 'class' => 'input_text', 'onblur' => $checkId, 'readonly' => $readonly, 'maxlength' => 20), 'required');
                echo "&nbsp;<span class='colRed'>*</span>";
            } elseif ($_mode_ == 'modify') {
                echo $model->login_name;
            }
            ?>
        </td>
    </tr>
    <?php if ($_mode_ != 'modify'):?> 
    <tr>
        <td class="maxname">登录密码：</td>
        <td class="mivalue">
        <?php 
        	echo $form->activePasswordField($model, 'new_password', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 16), $pass_r); 
        	echo "&nbsp;<span class='colRed'>*</span>";
        ?>
        </td>
        <td class="maxname">确认登录密码：</td>
        <td class="mivalue">
        <?php 
        	echo $form->activePasswordField($model, 'confirm_password', array('title' => '本项必填', 'class' => 'input_text', 'maxlength' => 16), $pass_r); 
        	echo "&nbsp;<span class='colRed'>*</span>";
        ?>
        </td>
    </tr>
	<?php endif;?>
    <tr>
    </tr>
    <tr>
        
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
        checkPassword();
    }
    function checkPassword(){
        var e = $("#Staff_password");
        var pass = $("#Staff_password").val();
        var pass_c = $("#Staff_passwordc").val();
        if(pass!=pass_c){
            flag = false;
            e.addClass('input_error iptxt');
            e.showTip({flagInfo:"两次输入密码不一致！"});
            e.focus();
        }
    }
    function checkId(obj) {
        var id = obj.value;
        //setLogin(id);
        // var id = $("#Staff_staffid").val();
        var e = $(obj);
        var ti = "登录账号已经存在！";
        $.ajax({
            url:'./?r=staff/staff/checkloginid',
            data:{id:id},
            dataType:"json",
            success:function (data) {
                if (data.status) {
                    if (data.msg > 0) {
                        e.addClass('input_error iptxt');
                        e.showTip({flagInfo:ti});
                        e.focus();
                        flag = false;
                    } else {
                        flag = true;
                    }
                }
            }
        })
    }
    
<?php
if ($msg['status']) {
    echo "setTimeout(hideMsg,3000);
        ";
    echo "parent.itemQuery();";
}
?>
    var city_list=<?php echo json_encode($suCityList['unsel']); ?>;
    var selected_cities=<?php echo json_encode($suCityList['sel']); ?>;
    function AppViewModel(){
        var self=this;
        self.cities=ko.observableArray(city_list);
        self.sel_cities=ko.observableArray(selected_cities);
        self.addCity=function(){
            self.sel_cities.push(this);
            self.cities.remove(this);
            self.setCitiesField();
        };
        self.removeCity=function(){
            self.cities.push(this);
            self.sel_cities.remove(this);
            self.setCitiesField();
        };
        self.setCitiesField=function(){
            var cities_str="";
            for(var key in selected_cities){
                var code=selected_cities[key]['code'];
                var name=selected_cities[key]['name'];
                var split="|";
                if(key==0){
                    split="";
                }
                cities_str+=split+code+"~"+name;
            }
            $("#Staff_cities").val(cities_str);
        };
    }
    ko.applyBindings(new AppViewModel(),document.getElementById('city_tr'));
</script>