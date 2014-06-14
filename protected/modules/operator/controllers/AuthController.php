<?php

/**
 * 操作员权限表
 *
 * @author liuxy
 */
class AuthController extends AuthBaseController {

    /**
     * 添加
     */
    public function actionNew() {

        if ($_REQUEST['confirm']) {
            $args = $_REQUEST['q'];
            $r = OperatorAuth::insertAuth($args);

            if ($r['message'] == true) {
                $left_data = '';
                $right_data = '';

                if ($args['auth_type'] == 'task') {
                    //可选的功能
                    $task_data = OperatorAuth::queryLeftTasks($args['login_name']);
                    foreach ($task_data as $id => $name) {
                        $left_data.='<span class="password_ico"></span>' . $name . '<span class="del_ico cursor" onclick=itemDelete("' . $id . '","task")></span><br/>';
                    }

                    //已选的功能
                    $task_data = OperatorAuth::queryRightTasks($args['login_name']);
                    foreach ($task_data as $id => $name) {
                        $right_data.='<span class="password_ico"></span>' . $name . '<span class="add_ico cursor" onclick=itemNew("' . $id . '","task")></span><br/>';
                    }
                }
                $r['left_data'] = $left_data;
                $r['right_data'] = $right_data;
            }
        }
        
        echo json_encode($r);
    }

    /**
     * 删除
     */
    public function actionDelete() {

        if ($_REQUEST['confirm']) {
            $args = $_REQUEST['q'];
            
            $r = OperatorAuth::deleteAuth($args);
            
            if ($r['message'] == true) {
                $left_data = '';
                $right_data = '';

                if ($args['auth_type'] == 'task') {
                    //可选的功能
                    $task_data = OperatorAuth::queryLeftTasks($args['login_name']);
                    foreach ($task_data as $id => $name) {
                        $left_data.='<span class="password_ico"></span>' . $name . '<span class=del_ico onclick=itemDelete("' . $id . '","task")></span><br/>';
                    }

                    //已选的功能
                    $task_data = OperatorAuth::queryRightTasks($args['login_name']);
                    foreach ($task_data as $id => $name) {
                        $right_data.='<span class="password_ico"></span>' . $name . '<span class="add_ico cursor" onclick=itemNew("' . $id . '","task")></span><br/>';
                    }
                }
                $r['left_data'] = $left_data;
                $r['right_data'] = $right_data;
            }
        }
        echo json_encode($r);
    }

}

?>
