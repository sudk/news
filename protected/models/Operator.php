<?php

/**
 * Operator class file.
 * 操作员
 * @author liuxy
 * @copyright Copyright &copy; 2003-2011 TrunkBow Co., Inc
 */
class Operator extends CActiveRecord {

    public $new_password; //新密码
    public $confirm_password; //确认密码
    public $update_pwd_flag;

    //用户类型

    const TYPE_MCHI = '2'; //商户
    const TYPE_SYSTEM = '1'; //系统
    const TYPE_BANK = '3'; //银行操作员
    //状态
    const STATUS_NORMAL = '0'; //正常
    const STATUS_DISABLE = '9'; //注销
    const STATUS_FREEZE = '1'; //冻结
    const SEX_MEN = 0;
    const SEX_WOMEN = 1;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'operator';
    }

    public static function getTypeRs($key = null) {
        $rs = array(
            self::TYPE_SYSTEM => '系统管理员',
            self::TYPE_BANK => '银行操作员',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function getStatusTitle($key = null) {
        $rs = array(
            self::STATUS_NORMAL => '正常',
            self::STATUS_DISABLE => '注销',
            self::STATUS_FREEZE => '冻结',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function GetSex($s = "") {
        $ar = array(
            self::SEX_MEN => "男",
            self::SEX_WOMEN => "女",
        );
        return trim($s) ? $ar[$s] : $ar;
    }

    public static function loadRecord($id) {
        $model = Operator::model()->findByPk($id);
        return $model;
    }

    public static function insertLog($model) {
        return array(
            '用户名' => $model->name,
            '性别' => self::GetSex($model->sex),
            '电话' => $model->phone,
            'E-Mail' => $model->email,
            '操作员类型' => self::getTypeRs($model->type),
            '登录账号' => $model->login_name,
            '地址' => $model->addr,
            '状态' => self::getStatusTitle($model->status),
        );
    }

    public static function updateLog($model) {
        return array(
            '用户名' => $model->name,
            '性别' => self::GetSex($model->sex),
            '电话' => $model->phone,
            'E-Mail' => $model->email,
            '操作员类型' => self::getTypeRs($model->type),
            '登录账号' => $model->login_name,
            '地址' => $model->addr,
            '状态' => self::getStatusTitle($model->status),
        );
    }

    public static function resetPwdLog($model) {
        return array(
            '操作员编号' => $model->op_id,
        );
    }

    /**
     * 添加
     * @param array $args
     * @return array
     */
    public static function add($args) {

        if ($args['login_name'] == '') {
            $r['message'] = '登陆账号不能为空';
            $r['refresh'] = false;
            return $r;
        }

        $args['type'] = self::TYPE_SYSTEM;

        if ($args['type'] == '') {
            $r['message'] = '操作员类型不能为空';
            $r['refresh'] = false;
            return $r;
        }
        //检测账号的惟一性
        $total_num = Operator::model()->count('login_name=:login_name AND type=:type', array('login_name' => $args['login_name'], 'type' => $args['type']));
        if ($total_num <> 0) {
            $r['message'] = '登陆账号已存在';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['phone'] == '') {
            $r['message'] = '绑定手机号不能为空';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['new_password'] == '') {
            $r['message'] = '密码不能为空';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['confirm_password'] == '') {
            $r['message'] = '确认密码不能为空';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['new_password'] <> $args['confirm_password']) {
            $r['message'] = '两次输入的密码不一致';
            $r['refresh'] = false;
            return $r;
        }

        $args['password'] = md5($args['new_password']);

        try {
            $model = new Operator();
            $model->login_name = trim($args['login_name']);
            $model->password = trim($args['password']);
            $model->name = trim($args['name']);
            $model->sex = trim($args['sex']);
            $model->phone = trim($args['phone']);
            $model->addr = trim($args['addr']);
            $model->email = trim($args['email']);
            $model->type = $args['type'];
            $rs = $model->save();
            if ($rs) {
                //设置权限
                OperatorAuth::batchAdd($args['login_name'], self::getRoleName($args['type']));

                $r['message'] = '添加成功';
                $r['refresh'] = true;
                $r['model'] = $model;
            }
        } catch (PDOException $e) {
            $r['message'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function getRoleName($key) {

        $rs = array(
            self::TYPE_SYSTEM => 'smanager', //系统管理员
            self::TYPE_BANK => 'bank_m', //银行操作员
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 修改操作员基本信息
     * @param array $args
     * @return array
     */
    public static function edit($args) {
        if ($args['op_id'] == '') {
            $r['message'] = '账号不能为空';
            $r['refresh'] = false;
            return $r;
        }
        $model = Operator::loadRecord($args['op_id']);
        if ($model === null) {
            $r['message'] = '无效的操作员';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['new_password'] != '') {
            if ($args['confirm_password'] == '') {
                $r['message'] = '确认密码不能为空';
                $r['refresh'] = false;
                return $r;
            }

            if ($args['new_password'] <> $args['confirm_password']) {
                $r['message'] = '两次输入的密码不一致';
                $r['refresh'] = false;
                return $r;
            }

            $args['password'] = md5($args['new_password']);
        }

        try {
            $model->name = trim($args['name']);
            $model->phone = trim($args['phone']);
            $model->addr = trim($args['addr']);
            $model->sex = trim($args['sex']);
            if ($args['password'] != '') {
                $model->password = trim($args['password']);
            }
            $model->email = trim($args['email']);
            if ($args['status'] != '') {
                $model->status = trim($args['status']);
            }
            if ($args['type'] != '') {
                $model->type = $args['type'];
            }

            $result = $model->save();

            if ($result) {

                //设置权限
                if ($args['type'] != '') {
                    OperatorAuth::batchDel($model->login_name); //清空权限
                    OperatorAuth::batchAdd($model->login_name, self::getRoleName($args['type'])); //批量添加
                }
                //记录日志
                //Ophis::savelog(self::updateLog($model), 0);
                $r['message'] = '修改成功';
                $r['refresh'] = true;
                $r['model'] = $model;
            }
        } catch (PDOException $e) {
            $r['message'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 修改密码
     * @param array $args
     * @return array
     */
    public static function pwd($args) {

        //操作员必须存在
        $model = Operator::loadRecord($args['op_id']);
        if ($model === null) {
            $r['message'] = '无效的操作员';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['new_password'] == '') {
            $r['message'] = '新密码不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['confirm_password'] == '') {
            $r['message'] = '确认密码不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['new_password'] <> $args['confirm_password']) {
            $r['message'] = '两次输入的密码不一致';
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model->password = md5(trim($args['new_password']));
            $model->save();

            //Ophis::savelog(self::resetPwdLog($model), 0);

            $r['message'] = '重设密码成功';
            $r['refresh'] = true;
            $r['model'] = $model;
        } catch (PDOException $e) {
            $r['message'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryRows($args = array()) {
        $condition = '';
        $params = array();

        if ($args['login_name'] != '') {
            $condition.= ( $condition == '') ? ' login_name=:login_name' : ' AND login_name=:login_name';
            $params['login_name'] = $args['login_name'];
        }
        if ($args['name'] != '') {
            $condition.= ( $condition == '') ? ' name =:name ' : ' AND name =:name';
            $params['name'] = $args['name'];
        }
        if ($args['phone'] != '') {
            $condition.= ( $condition == '') ? ' phone=:phone' : ' AND phone=:phone';
            $params['phone'] = $args['phone'];
        }

        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        if ($args['type'] != '') {
            $condition.= ( $condition == '') ? ' type=:type' : ' AND type=:type';
            $params['type'] = $args['type'];
        }

        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;

        $rows = Operator::model()->findAll($criteria);
        return $rows;
    }

    /**
     * 删除
     * @param  array $operatorid
     * @return array
     */
//    public function delete($operatorid) {
//        $sql = 'DELETE FROM operator WHERE login_name=:login_name';
//        try {
//
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":login_name", $operatorid, PDO::PARAM_STR);
//            $rs = $command->execute();
//            $r['message'] = '删除成功';
//            $r['refresh'] = true;
//        } catch (CDbException $e) {
//            $r['message'] = $e->getMessage();
//            $r['refresh'] = false;
//        }
//        return $r;
//    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        if ($args['op_id'] != '') {
            $condition.= ( $condition == '') ? ' op_id=:op_id' : ' AND op_id=:op_id';
            $params['op_id'] = $args['op_id'];
        }
        if ($args['login_name'] != '') {
            $condition.= ( $condition == '') ? ' login_name=:login_name' : ' AND login_name=:login_name';
            $params['login_name'] = $args['login_name'];
        }
        if ($args['name'] != '') {
            $condition.= ( $condition == '') ? ' name LIKE :name ' : ' AND name LIKE :name';
            $params['name'] = $args['name'] . '%';
        }
        if ($args['phone'] != '') {
            $condition.= ( $condition == '') ? ' phone=:phone' : ' AND phone=:phone';
            $params['phone'] = $args['phone'];
        }

        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        if ($args['type'] != '') {
            $condition.= ( $condition == '') ? ' type=:type' : ' AND type=:type';
            $params['type'] = $args['type'];
        }




        $total_num = Operator::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $criteria->order = '';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $criteria->order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $criteria->order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;

        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Operator::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($pages->currentPage + 1);
        $rs['total_num'] = $total_num;
        $rs['total_page'] = ceil($rs['total_num'] / $rs['page_num']);
        $rs['num_of_page'] = $pages->pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

}