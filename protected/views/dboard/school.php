<?php
$schoolid = Yii::app()->session['schoolid'];
$school = School::model()->findByPk($schoolid);

$grades = Staff::AllMyGrades();
$myclasses = Yii::app()->session['myclasses'];//$classes = Staff::AllMyClasses();

$attstatus = array();
$attsum = array();

if(count($grades)>0)
foreach($grades as $grade)
{
    $r = AttendanceRecord::getGradeCurrentAttStatus($schoolid,$grade['gradeid']);
    foreach($r as $classid => $s)
    {
        $attstatus[$classid] = $s;
        if(array_key_exists($classid,$myclasses))
        {
            $attsum['STATUS_LATE_IN'] = intval($attsum['STATUS_LATE_IN'])+$s['in'][AttendanceRecord::STATUS_LATE_IN];
            $attsum['STATUS_LEAVE_EARLY_OUT'] = intval($attsum['STATUS_LEAVE_EARLY_OUT'])+$s['out'][AttendanceRecord::STATUS_LEAVE_EARLY_OUT];
            $attsum['STATUS_TRUANT_IN'] = intval($attsum['STATUS_TRUANT_IN'])+$s['in'][AttendanceRecord::STATUS_TRUANT_IN];
            $attsum['STATUS_LEAVE_IN'] = intval($attsum['STATUS_LEAVE_IN'])+$s['in'][AttendanceRecord::STATUS_LEAVE_IN];
            $attsum['STATUS_UNKNOWN_IN'] = intval($attsum['STATUS_UNKNOWN_IN'])+$s['in'][AttendanceRecord::STATUS_UNKNOWN_IN];
        }
    }
}
?>
    <!-- content > left start -->
    <div id="left">
        <div class="lc">
            <div class="lc-title">个人信息</div>
            <ul class="lc-main" align="center">
                <li class="lc-content-title"><?php echo date('Y年n月d日');?></li>
                <li><?php echo Utils::chinese_week();?></li>
                <li>&nbsp;</li>
                <li>您好：<?php echo Yii::app()->user->name;?></li>
                <li><?php echo Yii::app()->session['schoolname'];?></li>
                <li>
                    <a href="#" id="btnChangePasswd2" onclick='tipsWindown("修改密码","iframe:index.php?r=site/passwd",  "500", "240","true", "","true", "text")'>修改密码</a>
                    &nbsp; | &nbsp;
                    <a href="./?r=site/logout" title="退出登陆">退出登陆</a>

                </li>
            </ul>
        </div>
        <div class="lc-app">
            <div class="lc-app-title">
                <span class="floatL">快速操作</span>

            </div>
            <ul class="lc-app-main">
<?php
    if(Yii::app()->user->checkAccess('msg/parsmg/unionsend'))
        echo '<li><a href="index.php?r=msg/parsmg/unionsend" class="2s_loggoff"><img src="images/01/2.png" /><span>发送家长短信</span></a></li>';
    if(Yii::app()->user->checkAccess('msg/parsmg/deliver'))
        echo '<li><a href="index.php?r=msg/parsmg/deliver" class="2s_loggoff"><img src="images/01/3.png" /><span>家长留言信息</span></a></li>';
    if(Yii::app()->user->checkAccess('att/attrecord/list'))
        echo '<li><a href="index.php?r=att/attrecord/list" class="2s_loggoff"><img src="images/01/10.png" /><span>考勤记录</span></a></li>';
    if(Yii::app()->user->checkAccess('posrpt/classes/new'))
        echo '<li><a href="index.php?r=posrpt/classes/new" class="2s_loggoff"><img src="images/01/1.png" /><span>添加班级</span></a></li>';
    if(Yii::app()->user->checkAccess('staff/teacher/new'))
        echo '<li><a href="index.php?r=staff/teacher/new" class="2s_loggoff"><img src="images/01/1.png" /><span>添加教师</span></a></li>';
    if(Yii::app()->user->checkAccess('student/stu/new'))
        echo '<li><a href="index.php?r=student/stu/new" class="2s_loggoff"><img src="images/01/1.png" /><span>添加学生</span></a></li>';
    if(Yii::app()->user->checkAccess('att/attrule/index'))
        echo '<li><a href="index.php?r=att/attrule/index" class="2s_loggoff"><img src="images/01/6.png" /><span>考勤规则</span></a></li>';
    if(Yii::app()->user->checkAccess('msg/notice/addschool'))
        echo '<li><a href="index.php?r=msg/notice/addschool" class="2s_loggoff"><img src="images/01/5.png" /><span>发布公告</span></a></li>';
?>

            </ul>
        </div>
    </div>
    <!-- content > left end -->

    <!-- content > right start -->
    <div id="right">
        <div class="title-box">
            <h1>学校概况
            </h1>
        </div>
        <table class="dbList">
            <tbody>
            <tr>
                <td>
                    <span style="padding-right: 40px;">学校编号:<?php echo $school->schoolid;?></span>
                    <span>学校名称:<?php echo $school->name;?></span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <span style="padding-right: 40px;">总学生数：<?php echo $school->studentsnum;?> </span>
                    <span style="padding-right: 40px;"> 教师数：<?php echo $school->staffnum;?> </span>
                    <span style="padding-right: 40px;"> 班级数：<?php echo $school->classnum;?> </span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <span style="padding-right: 10px;padding-left: 10px;"> 今日迟到:<?=$attsum['STATUS_LATE_IN']?></span> |
                    <span style="padding-right: 10px;padding-left: 10px;">今日旷课:<?=$attsum['STATUS_LEAVE_EARLY_OUT']?></span> |
                    <span style="padding-right: 10px;padding-left: 10px;">今日早退:<?=$attsum['STATUS_TRUANT_IN']?></span> |
                    <span style="padding-right: 10px;padding-left: 10px;">今日请假:<?=$attsum['STATUS_LEAVE_IN']?></span><!-- |
                    <span style="padding-right: 10px;padding-left: 10px;">今日未刷卡:<?=$attsum['STATUS_UNKNOWN_IN']?></span> -->
                <td></td>
             </tr>
            </tbody>
        </table>

        <div class="title-box">
            <h1>消息中心</h1>
        </div>
        <table class="dbList">
            <tbody>
<?php
    $msgs = Notice::queryMyList(1,5,array('staffid'=>Yii::app()->user->id));
    if(count($msgs['rows'])==0)
    {
        echo '<tr>';
        echo '<td>无记录</td>';
        echo '</tr>';
    }
    else
    {
        foreach($msgs['rows'] as $msg)
        {
            echo '<tr>';
            echo "<td>".Notice::getReadStatus($msg['isread'])."</td>";
            echo "<td>".($msg['type']==1?'【学校公告】':'')."</td>";
            echo "<td>".substr($msg['createtime'],0,10)."</td>";
            echo "<td>{$msg['title']}</td>";

            echo "<td>{$msg['creatorname']}</td>";
            echo '<td><a href="#" onclick="noticeView('.$msg['noticeid'].','.$msg['isread'].')">详细</a></td>';
            echo "</tr>";
        }
    }
?>
            </tbody>
        </table>
<?php
if(Yii::app()->user->checkAccess('ClassManager'))
{
?>
        <div class="title-box">
            <h1>我的班级</h1>
        </div>
        <div>
            <ul>
            <?php
foreach($myclasses as $classid => $class)
{
    echo '<li style="padding: 10px 30px 10px 30px;display: inline-block;">';
    echo '<a href="index.php?r=myclass/index&classid='.$class['classid'].'">';
    echo $class['name'];
    echo '</a></li>';
}
            ?>
            </ul>
        </div>
<?php
}
?>
    </div>
    <!-- content > right end -->
    <!-- content end -->
<?php


?>
<script type="text/javascript">
    var noticeView = function(id,isread) {
        tipsWindown(
            "查看公告",	// title：窗口标题
            "iframe:index.php?r=msg/notice/view&id="+id+"&read="+isread,	// Url：弹窗所加截的页面路径
            "600",	// width：窗体宽度
            "300",	// height：窗体高度
            "true",	// drag：是否可以拖动（ture为是,false为否）
            "",	// time：自动关闭等待的时间，为空代表不会自动关闭
            "true",	// showbg：设置是否显示遮罩层（false为不显示,true为显示）
            "text"	// cssName：附加class名称
        );
    }
</script>

