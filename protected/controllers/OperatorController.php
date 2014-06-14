<?php
/*
 * 模块编号: M0501
 */
class OperatorController extends AuthBaseController
{

    public $defaultAction = 'list';
    public $gridId = 'sample_list';
    public $pageSize = 20;
    private $_rec;

    private function genDataGrid()
    {
        $t = new SimpleGrid($this->gridId);
        $t->url = 'index.php?r=operator/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('ID', '', '', 'operator_id');
        $t->set_header('姓名', '', 'left', 'name');
        $t->set_header('联系电话', '', '', 'phone');
        //$t->set_header('Emai', '', '');
        $t->set_header('类型', '', '', 'type');
        $t->set_header('所属组织', '110', '');
        //$t->set_header('所属部门', '', '');
        $t->set_header('状态', '', '');
        $t->set_header('操作', '90', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid()
    {
        $page = intval($_REQUEST['page']);
        $args = $_GET['q']; //查询条件
        $type=Yii::app()->user->getState('type');
        $org_id=Yii::app()->user->getState('org_id');
        //$node_id=Yii::app()->user->getState('node_id');
        $area_code=Yii::app()->user->getState('area_code');
        if($type!=Operator::TYPE_SYSOPR){
            $args['type']=$type;
            $args['org_id']=$org_id;
        }
        if(trim($area_code)!="")
        {
            $args['area_code']=$area_code;
        }
        $t = $this->genDataGrid();
        $rs = Operator::queryList($page, $this->pageSize, $args); //分页
        $this->saveUrl();
        $this->renderPartial('_list', array('t' => $t, 'rows' => $rs['rows'], 'page' => $rs['_pg_']));
    }

    /*
     * 保存查询链接
     */

    private function saveUrl()
    {
        $a = Yii::app()->session['list_url'];
        $a['sample'] = str_replace("r=sample/grid", "r=sample/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->render('list');
        return;
        $rs = array();
        $r = Yii::app()->uc->call('12620103', array(), $rs);
        echo $r;
        var_dump($rs);
        $r = UniwebClient::list2map($rs);
        var_dump($r);

    }

    /**
     * 添加
     */
    public function actionNew()
    {
        $model = new Operator('insert');
        $model->type=Yii::app()->user->getState('type');
        $model->org_id=Yii::app()->user->getState('org_id');
        $model->org_name=Yii::app()->user->getState('org_name');
        $r = array();
        if (isset($_POST['Operator'])) {
            $r = array();
            $_POST['Operator']['sms_check_flag']=Operator::SMS_NOCHECK;
            $args=$this->checkOpinfo($_POST['Operator']);
            //print_r($args);
            //print_r(json_encode($args));
            $retcode = Yii::app()->uc->call('126C0101',$args, $r);
            if ($retcode == 0) {
                $r['message'] = '添加成功.';
                $r['refresh'] = true;
                
            } else
            {
                $r['message'] = $r['desc'];
            }
            $log=array(
                    'opt_field'=>'操作员添加',
                    'opt_desc'=>'成功操作员信息：操作员ID='.$_POST['Operator']['operator_id']."操作员名称=".$_POST['Operator']['name'],
                    'opt_result'=>$retcode==0?"成功":"失败",
                    'result_desc'=>$r['desc'],
                );
            Operator::addOperatorLog($log);
        }
        $this->render('new', array('model' => $model, 'result' => $r));
    }

    /**
     * 添加
     */
    public function actionNewdialog()
    {
        $model = new Operator('create');

        $r = array();
        if (isset($_POST['Operator'])) {
            $r = array();
            $args=$this->checkOpinfo($_POST['Operator']);
            $retcode = Yii::app()->uc->call('126C0101',$args, $r);
            if ($retcode == 0) {
                $r['message'] = '添加成功.';
                $r['refresh'] = true;
            } else
            {
                $r['message'] = $r['desc'];
            }
        }
        $this->layout = '//layouts/base';
        $this->render('newdialog', array('model' => $model, 'result' => $r));
    }

    /**
     * 编辑
     */
    public function actionEdit()
    {
        $id = intval($_REQUEST['id']);
        $model = $this->loadRecord();
        $r = array();
        if (isset($_POST['Operator'])) {
            $model->_attributes = $_POST['Operator'];
            $args=$this->checkOpinfo($_POST['Operator']);
            $r = array();
            $retcode = Yii::app()->uc->call('126C0102',$args, $r);
            if ($retcode == 0) {
                $r['message'] = '修改成功.';
                $r['refresh'] = true;
            } else
            {
                $r['message'] = $r['desc'];
            }
            $log=array(
                    'opt_field'=>'操作员修改',
                    'opt_desc'=>'修改后操作员信息:操作员ID='.$_POST['Operator']['operator_id']."操作员名称=".$_POST['Operator']['name'],
                    'opt_result'=>$retcode==0?"成功":"失败",
                    'result_desc'=>$r['desc'],
                );
            Operator::addOperatorLog($log);
        }
        $model->password = "";
        $this->layout = '//layouts/base';
        $this->render('edit', array('id' => $id, 'model' => $model, 'result' => $r));
    }
    public function actionFreeze(){
        if (isset($_POST['operator_id'])) {
            $flag['refresh'] = false;
            if($_POST['operator_id']=="admin")
            {
                $flag['status'] = -1;
                $flag['desc'] = "不能对超级管理员执行此操作！";
                print_r(json_encode($flag));
                exit;
            }
            $args=array(
                'operator_id'=>$_POST['operator_id'],
                'status'=>$_POST['status']
            );
            $r = array();
            if($_POST['status']==0){
                $retcode = Yii::app()->uc->call('126C0146',$args, $r);
            }else{
                $retcode = Yii::app()->uc->call('126C0145',$args, $r);
            }
            if ($retcode != 0) {
                $flag['status'] = $retcode;
                $flag['desc'] = $r['desc'];
            } else {
                $flag['status'] = 0;
                $flag['refresh'] = true;
            }
            print_r(json_encode($flag));
            if($_POST['status']==0){
                $type="解冻";
            }else{
                $type="冻结";
            }
            $log=array(
                    'opt_field'=>'操作员'.$type,
                    'opt_desc'=>'操作员id：'.$_POST['operator_id'],
                    'opt_result'=>$retcode=0?"成功":"失败",
                    'result_desc'=>$r['desc'],
                );
            Operator::addOperatorLog($log);
        }else{
            echo false;
        }
    }
    /**
     * 口令修改
     */
    public function actionChpasswd()
    {
        $id = Yii::app()->user->getId();
        $model = $this->loadRecord($id);
        $r = array();
        if (isset($_POST['Operator'])) {
            $model->_attributes = $_POST['Operator'];
            $args=$this->checkOpinfo($_POST['Operator']);
            $r = array();
            $retcode = Yii::app()->uc->call('126C0102',$args, $r);
            if ($retcode == 0) {
                $r['message'] = '修改成功.';
                $r['refresh'] = true;
            } else
            {
                $r['message'] = $r['desc'];
            }
            $log=array(
                    'opt_field'=>'口令修改修改',
                    'opt_desc'=>'口令修改修改',
                    'opt_result'=>$retcode==0?"成功":"失败",
                    'result_desc'=>$r['desc'],
                );
            Operator::addOperatorLog($log);
        }
        $model->password = "";
        $this->layout = '//layouts/base';
        $this->render('chpasswd', array('id' => $id, 'model' => $model, 'result' => $r));
    }

    public function checkOpinfo($opinfo){
        $type=Yii::app()->user->getState('type');
        $org_id=Yii::app()->user->getState('org_id');
        //$node_id=Yii::app()->user->getState('node_id');
        $area_code=Yii::app()->user->getState('area_code');
        if($type!=Operator::TYPE_SYSOPR){
            $opinfo['type']=$type;
        }
        if(trim($org_id)!=""){
            $opinfo['org_id']=$org_id;
        }
        if(trim($area_code)!="")
        {
            $opinfo['area_code']=$area_code;
        }
        return $opinfo;
    }

    public function actionSorg()
    {
        $rs = Org::queryList(0, 10, $_GET['q']);
        $rows = $rs['rows'];
        $msg['success'] = false;
        //print_r($rows);
        if ($rows) {
            $msg['success'] = true;
            foreach ($rows as $row)
            {
                $ht .= "<span><a style='color:#666; margin-right:10px;' href='javascript:void(0)' org_id='" . $row['org_id'] . "' onclick='orgsel(this)'>" . $row['full_name'] . "</a></span>
                ";
            }
            $msg['ht'] = $ht;
        }
        print_r(json_encode($msg));
    }

    public function actionAddsorg()
    {
        $sorg = $_POST['sorg'];
        $s1 = Yii::app()->session['sorg1'];
        $s2 = Yii::app()->session['sorg2'];
        $s3 = Yii::app()->session['sorg3'];
        if ($s1 != $sorg && $s2 != $sorg && $s3 != $sorg) {
            Yii::app()->session['sorg3'] = $s2;
            Yii::app()->session['sorg2'] = $s1;
            Yii::app()->session['sorg1'] = $sorg;
        }
    }

    public function actionLoadnode()
    {
        $org_id = $_GET['org_id'];
        $org_name = $_GET['org_name'];
        $this->widget('AsynchTree', array("id" => 'AsynchTree', "root" => $org_name, 'tiHtmlOption' => "onclick='chdept(this);'", 'parameter' => array('org_id' => $org_id, 'node_id' => $org_id),));
    }

    /**
     * 查看
     */
    public function actionView()
    {
        $id = intval($_REQUEST['id']);
        $model = $this->loadRecord();
        $r = array();
        if (isset($_POST['Operator'])) {
            $model->attributes = $_POST['Operator'];
            $model->html_url = $_POST['Operator']['html_url'];
            $model->flash_url = $_POST['Operator']['flash_url'];
            $model->wap_url = $_POST['Operator']['wap_url'];
            $model->poster = Yii::app()->user->id;
            $model->poster_type = 2;
            if ($model->validate()) {
                $model->save();
                $r['message'] = '添加成功.';
            } else
            {
                $r['message'] = '有错误';
            }
        } else {
            //$model->targets = 0;
        }
        $this->render('view', array('model' => $model, 'result' => $r));
    }

    public function actionDelete()
    {
        if (isset($_POST['operator_id'])) {
            $op=new Operator();
            $rs = $op->delete($_POST['operator_id']);
            print_r(json_encode($rs));
            $log=array(
                    'opt_field'=>'操作员删除',
                    'opt_desc'=>'被删除的操作员id：'.$_POST['operator_id'],
                    'opt_result'=>$retcode=0?"成功":"失败",
                    'result_desc'=>$r['desc'],
                );
            Operator::addOperatorLog($log);
        }else{
            echo false;
        }
    }

    public function actionEdit2()
    {
        $this->layout = '//layouts/column2';

        $model = new Operator();
        $r = array();
        if (isset($_POST['Operator'])) {
            $model->attributes = $_POST['Operator'];
            $model->html_url = $_POST['Operator']['html_url'];
            $model->flash_url = $_POST['Operator']['flash_url'];
            $model->wap_url = $_POST['Operator']['wap_url'];

            if ($model->validate()) {
                $model->save();


                $r['message'] = '添加成功.' . $_POST['Operator']['flash_url'] . ',' . $model->html_url;
            } else
            {
                $r['message'] = '有错误';
            }
        } else {
            //$model->targets = 0;
        }
        $this->render('edit', array('model' => $model, 'result' => $r));
    }

    public function loadRecord($id = null)
    {
        if ($this->_rec === null) {
            if ($id !== null || isset($_GET['id'])) {
                $id = $id !== null ? $id : $_GET['id'];
                $this->_rec = Operator::model()->find(array('operator_id' => $id));
            }
            if ($this->_rec === null)
                throw new CHttpException(404, 'The requested message does not exist.');
        }
        return $this->_rec;
    }

}