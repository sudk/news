<?php
if ($msg['status']) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='{$class}' id='msg' style='width:600px;'>{$msg['desc']}</div>";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'form-container',
    'focus' => array($model, 'title'),
));
?>
<?php

if($__model__=='edit'){
	echo $form->activeHiddenField($model, 'news_id',array(),'');
}
?>
<table class="formList" >
    <tr>
        <td class="maxname">标题：</td>
        <td class="mivalue" style='width:350px;'>
        	<?php echo $form->activeTextField($model, 'title', array('title' => '本项必填', 'class' => 'input_text lng_address'), 'required'); ?>
        	<span class='colRed'>*</span>
        </td>
    </tr>
    <tr>
    	<td class="maxname">发表日期：</td>
    	<td class="mivalue">
    		<?php echo $form->activeTextField($model, 'public_date', array('title' => '本项必填', 'class' => 'input_text Wdate','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',skin:'whyGreen',errDealMode:0})"), ''); ?>
    	</td>
    </tr>
    <tr>
        <td class="maxname">作者：</td>
        <td class="mivalue">
            <?php echo $form->activeTextField($model, 'author', array('title' => '本项必填', 'class' => 'input_text'), 'required'); ?>
            <span class='colRed'>*</span>
        </td>
    </tr>
    <tr>
        <td class="maxname">类型：</td>
        <td class="mivalue">
            <?php echo $form->activeDropDownList($model,'type',News::GetType(), array('title' => '本项必填', 'class' => 'input_text'), 'required'); ?>
            <span class='colRed'>*</span>
        </td>
    </tr>
    <tr>
        <td class="maxname">地址：</td>
        <td class="mivalue">
            <?php echo $form->activeTextField($model,'addr', array('class' => 'input_text address'), ''); ?>
            <span class='colRed'>*</span>
        </td>
    </tr>
   <?php $this->renderPartial('_formpic', array('model' => $model,'__model__'=>$__model__));?>
    <tr>
        <td class="maxname">摘要：</td>
        <td class="mivalue">
        	<?php echo $form->activeTextArea($model, 'summary', array('title' => '本项必填','style'=>'height:150px;width:650px;'), ''); ?>
        	<span class='colRed'>*</span>
        	<span class="colGray">最多输入250个汉字</span>
        </td>
    </tr>
    <tr>
        <td class="maxname">内容：</td>
        <td class="mivalue" >
        	<?php echo $form->activeTextArea($model, 'content', array('style'=>'height:400px;width:650px;'), ''); ?>
        </td>
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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/kindedit/kindeditor.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">
    function formSubmit() {
        KE.util.setData("News_summary");
        KE.util.setData("News_content");
        $("form:first").submit();
    }
    function formReset() {
        document.getElementById("form1").reset();
    }

    //html编辑器
    var editor = {"id":["News_summary","News_content"],"tools":"simpleTools"};
    var simpleTools =
        [ 'title', 'fontname', 'fontsize', 'textcolor', 'bgcolor', 'bold', 'italic','underline',
        'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
        'removeformat','undo', 'redo', 'fullscreen', 'source', 'about'];


    $(document).ready(function(){
        $.each(editor.id, function(key, editorID){
            editorTool = simpleTools;
            KE.show({
                id:editorID,
                resizeMode : 1,
                filterMode:true,
                urlType:'relative',
                allowPreviewEmoticons : false,
                allowUpload : false,
                items :simpleTools
            });

        })
    });
    <?php
    if ($msg['status']) {
        echo "setTimeout(hideMsg,3000);";
    }

    ?>
</script>