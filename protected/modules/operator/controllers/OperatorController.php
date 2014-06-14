<?php

/**
 * 操作员管理
 *
 * @author liuxy
 */
class OperatorController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'list';
    public $pageSize = 100;

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new SimpleGrid($this->gridId);
        $t->url = 'index.php?r=operator/operator/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('序号', '8%', '');
        $t->set_header('账号类型', '12%', '');
        $t->set_header('姓名', '15%', '');
        $t->set_header('登陆账号', '10%', '');
        $t->set_header('电话', '10%', '');
        $t->set_header('状态', '10%', '');
        $t->set_header('记录时间', '15%', '');
        $t->set_header('操作', '35%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件


        if ($_REQUEST['q_value']) {
            $args[$_REQUEST['q_by']] = $_REQUEST['q_value'];
        }
        if($args['name']=='姓名')
        	$args['name'] = '';
        if($args['login_name']=='登录账号')
        	$args['login_name'] = '';
        

        $t = $this->genDataGrid();
        $this->saveUrl();

        $list = Operator::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->render('list');
    }

    public function actionNew() {
        $model = new Operator('create');
        if ($_POST['Operator']) {
            $args = $_POST['Operator'];
            $r = Operator::add($args);

            if ($r['refresh'] == false) {
                $model->attributes = $_POST['Operator'];
            }
        }
        $this->render("new", array('model' => $model, 'result' => $r));
    }

    public function actionEdit() {
        $id = $_GET['id'];
        $model = Operator::model()->findByPk($id);
        if ($_POST['Operator']) {
            $args = $_POST['Operator'];
            $model->scenario = 'modify';
            $model->attributes = $_POST['Operator'];
            $r = Operator::edit($args);
        }

        $this->layout = '//layouts/m_base';
        $this->render("edit", array('model' => $model, 'result' => $r));
    }

    public function actionEditpri() {

        $id = Yii::app()->user->id;
        $model = Operator::model()->find('login_name=:login_name', array(':login_name' => $id));

        if ($_POST['Operator']) {
            $args = $_POST['Operator'];
            $model->scenario = 'modify';
            $model->attributes = $_POST['Operator'];
            $r = Operator::edit($args);
        }

        $this->layout = '//layouts/m_base';
        $this->render("editpri", array('model' => $model, 'result' => $r));
    }

    public function actionCheckid() {
        $id = $_GET['id'];
        $data['status'] = true;
        if ($id) {
            $operator = Operator::model()->findByPk($id);
            //print_r($operator);
            if ($operator) {
                $data['msg'] = 2;
            } else {
                $data['msg'] = 0;
            }
        } else {
            $data['status'] = false;
        }
        print_r(json_encode($data));
    }

    public function actionCheckloginid() {
        $id = $_GET['id'];
        $data['status'] = true;
        if ($id) {
            $operator = Operator::model()->findByAttributes(array('loginid' => $id));
            //print_r($operator);
            if ($operator) {
                $data['msg'] = 2;
            } else {
                $data['msg'] = 0;
            }
        } else {
            $data['status'] = false;
        }
        print_r(json_encode($data));
    }

    public function actionDel() {
        $id = $_POST['id'];
        $rs = Operator::model()->deleteByPk($id);
        if ($rs) {
            Ophis::savelog(array('操作员编号' => $id), 0);
            $msg['status'] = true;
        } else {
            $msg['status'] = false;
        }
        print_r(json_encode($msg));
    }

    public function actionDetail() {
        $id = $_POST['id'];
        $model = Yii::app()->db->createCommand()
                ->select("*")
                ->from("operator st")
                ->where("st.operatorid='{$id}'")
                ->queryRow();
        $msg['status'] = true;
        if ($model) {
            $cd = "";
            $label = "";
            if ($model['type'] == 1) {
                $cd = "pi.managerid='{$id}'";
                $label = "发展终端个数：";
            } elseif ($model['type'] == 2) {
                $cd = "pi.maintainid='{$id}'";
                $label = "维护终端个数：";
            }
            if ($cd) {
                $countPos = Yii::app()->db->createCommand()
                        ->select("count(1) count_pos")
                        ->from("posinfo pi")
                        ->where($cd)
                        ->queryRow();
            } else {
                $countPos['count_pos'] = 0;
            }
            //$msg['detail']=$model;
            $msg['detail'] .= "<span>E-Mail:</span>" . (trim($model['email']) ? $model['email'] : '无') . "&nbsp;&nbsp;";
            $msg['detail'] .= "<span>QQ:</span>" . (trim($model['qq']) ? $model['qq'] : '无') . "&nbsp;&nbsp;<br><br>";
            $msg['detail'] .= "<span>工作类型:</span>" . Operator::GetJobType($model['jobtype']) . "&nbsp;&nbsp;";
            $msg['detail'] .= "<span>创建人:</span>" . (trim($model['creator']) ? $model['creator'] : '无') . "&nbsp;&nbsp;<br><br>";
            $msg['detail'] .= "<span>备注:</span>" . (trim($model['remark']) ? $model['remark'] : '无') . "&nbsp;&nbsp;";
            $msg['detail'] .= "<span>{$label}</span>" . $countPos['count_pos'] . "&nbsp;&nbsp;<br><br>";
        } else {
            $msg['status'] = false;
            $msg['detail'] = "获取人员信息失败！";
        }
        print_r(json_encode($msg));
    }

    /**
     * 权限管理
     */
    public function actionAuth() {

        $id = $_GET['id'];
        $model = Operator::model()->findByPk($id);
        $this->layout = "//layouts/m_base";
        $this->render('auth', array('model' => $model));
    }
    
    //重置密码
    public function actionPwd() {
    	$id = $_GET['id'];
    	$model = Operator::model()->findByPk($id);
    	if ($_POST['Operator']) {
    		$args = $_POST['Operator'];
    		
    		$r = Operator::pwd($args);
    	}
    
    	$this->layout = '//layouts/m_base';
    	$this->render("pwd", array('model' => $model, 'result' => $r));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['rpt/school'] = str_replace("r=operator/operator/grid", "r=operator/operator/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}