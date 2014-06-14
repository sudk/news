<?php

/**
 * 操作员权限
 *
 * @author liuxy
 */
class OperatorAuth extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'operator_auth';
    }

    public function rules() {
        return array(
            //安全性
            array('login_name,auth_id,auth_type', 'safe', 'on' => 'create'),
            array('login_name,auth_id,auth_type', 'safe', 'on' => 'modify'),
        );
    }

    /**
     * 得到所有权限对象
     * @return <type>
     */
    public static function getItemsList() {
        $path = dirname(__FILE__) . '/../data/auth.php';
        $_items = require($path);
        return $_items;
    }

    /**
     * 根据角色批量添加任务
     * @param type $login_name
     * @param type $role
     */
    public static function batchAdd($login_name,$role){
        
        $task_data = self::getSonRows($role);
        foreach ($task_data as $task){
            $params = array('login_name'=>$login_name,'auth_id'=>$task,'auth_type'=>'task');
            self::insertAuth($params);
        }
    }
    
    
    /**
     * 添加
     * @param  array $args
     * @return array
     */
    public static function insertAuth($args) {
        $r = array();

        //操作员账号
        if ($args['login_name'] != '') {
            $r['message'] = '操作员账号为空，不能添加';
            $r['refresh'] = false;
        }
        //权限对象
        if ($args['auth_id'] != '') {
            $r['message'] = '权限对象为空，不能添加';
            $r['refresh'] = false;
        }
        //权限对象的类型
        if ($args['auth_type'] != '') {
            $r['message'] = '权限对象的类型为空，不能添加';
            $r['refresh'] = false;
        }
        //检查权限对象的类型
//        $searcharray = array(self::AUTH_TYPE_ROLE => 1, self::AUTH_TYPE_OPERATION => 2);
//        if (array_key_exists($args['auth_type'], $searcharray) == false) {
//            $r['message'] = '非法的权限对象类型，不能添加';
//            $r['refresh'] = false;
//        }

        if ($args['auth_type'] == 'role') {
            $args['auth_type'] = CAuthItem::TYPE_ROLE;
        } else if ($args['auth_type'] == 'task') {
            $args['auth_type'] = CAuthItem::TYPE_TASK;
        }
        
        //检查账号的唯一性
        $oper = Operator::model() -> find("login_name=:lname",array(":lname"=> $args['login_name']));
        if ($oper === null) {
            $r['message'] = '非法的操作员账号，不能添加';
            $r['refresh'] = false;
            return $r;
        }

        $model = new OperatorAuth();
        $model->login_name = trim($args['login_name']);
        $model->auth_id = trim($args['auth_id']);
        $model->auth_type = trim($args['auth_type']);

        $flag = $model->save();

        if ($flag == true) {
            $r['message'] = '权限设置成功';
            $r['refresh'] = true;
        } else {
            $r['message'] = $flag['desc'];
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 删除权限
     * @return boolean
     */
    public static function deleteAuth($args) {

        if ($args['login_name'] == '') {
            $r['message'] = '账号不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['auth_id'] == '') {
            $r['message'] = '权限不能为空';
            $r['refresh'] = false;
            return $r;
        }
        
        $sql = 'DELETE FROM operator_auth WHERE login_name=:login_name AND auth_id=:auth_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":login_name", $args['login_name'], PDO::PARAM_STR);
        $command->bindParam(":auth_id", $args['auth_id'], PDO::PARAM_STR);
        
        $rs = $command->execute();

        if ($rs == 0) {
            $r['message'] = '您要删除的记录不存在！';
            $r['refresh'] = false;
        } else {
            //Utils::saveclog(self::deleteLog($args));
            $r['message'] = '删除成功';
            $r['refresh'] = true;
        }
        return $r;
    }

    /**
     * 批量删除权限
     * @return boolean
     */
    public static function batchDel($login_name) {

        if ($login_name == '') {
            $r['message'] = '账号不能为空';
            $r['refresh'] = false;
            return $r;
        }
        
        $sql = 'DELETE FROM operator_auth WHERE login_name=:login_name';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":login_name", $login_name, PDO::PARAM_STR);
        
        $rs = $command->execute();

        if ($rs == 0) {
            $r['message'] = '您要删除的记录不存在！';
            $r['refresh'] = false;
        } else {
            //Utils::saveclog(self::deleteLog($args));
            $r['message'] = '删除成功';
            $r['refresh'] = true;
        }
        return $r;
    }
    
     /**
     * 得到所有二级菜单列表
     * @param string $v 一级菜单名
     * @return array
     */
    public static function getSonRows($v) {

        $rs = array();//二级菜单名
        $items = self::getItemsList();//得到所有权限对象
        $row = $items[$v];//得到一级菜单名

        //一级菜单不存在
        if($row == null):
            return $rs;
        endif;

        //没有子项
        if(is_array($row['children']) == false or count($row['children']) == 0):
            return $rs;
        endif;

        //收集子项的信息
        foreach($row['children'] as $sid ):
            $rs[$sid] = $sid;//$items[$sid];
        endforeach;

        return $rs;
    }
    
    /**
     * 操作员权限列表
     * @param string $admin_login 操作员账号
     * @param array $args
     * @return array
     */
    public static function queryRows($login_name) {

        $result = array();

        $sql = "SELECT auth_id FROM operator_auth where login_name=:login_name";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":login_name", $login_name, PDO::PARAM_STR);

        $rows = $command->queryAll();
        foreach ($rows as $key => $row) {
            $result[$row['auth_id']] = $row['auth_id'];
            $children = self::getSonRows($row['auth_id']);
            $result = array_merge($result,$children);
        }
        
        
        

        return $result;
    }

    /**
     * 已选的任务
     * @param string $operatorid
     * @return arrray
     */
    public static function queryLeftTasks($login_name) {


        $task_data = array();

        $path = dirname(__FILE__) . '/../data/task.php';
        $_items = require($path);

        $condition = 'login_name=:login_name AND auth_type=:auth_type';
        $params = array('login_name' => $login_name, 'auth_type' => CAuthItem::TYPE_TASK);

        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = OperatorAuth::model()->findAll($criteria);


        if (is_array($rows)) {

            foreach ($rows as $i => $authitem) {

                $task_data[$authitem['auth_id']] = $_items[$authitem['auth_id']]['description'];
            }
        }

        return $task_data;
    }

    /**
     * 功能
     * @param integer $v
     * @return
     */
    public static function getTaskRs($v = '') {
        $path = dirname(__FILE__) . '/../data/task.php';
        $_items = require($path);
        $rs = array();

        if (is_array($_items)) {
            foreach ($_items as $i => $item) {
                if (!empty($item['display'])) {
                    if ($item['display'] == true) {
                        $rs[$i] = $item['description'];
                    }
                }
            }
        }
        return ((string) $v !== '') ? $rs[$v] : $rs;
    }

    /**
     * 可选的功能
     * @return array
     */
    public static function queryRightTasks($staffid) {
        $left_task_data = OperatorAuth::getTaskRs();
        $right_task_data = OperatorAuth::queryLeftTasks($staffid);
        $task_data = array_diff_assoc($left_task_data, $right_task_data);
        return $task_data;
    }

}

?>
