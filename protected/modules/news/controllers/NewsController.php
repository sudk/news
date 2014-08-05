<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class NewsController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'list';
    public $pageSize = 20;

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new SimpleGrid($this->gridId);
        $t->url = 'index.php?r=news/news/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('序号', '5%', '');
        $t->set_header('标题', '40%', '');
        $t->set_header('作者', '12%', '');
        $t->set_header('发表日期', '8%', '');
        $t->set_header('置项', '5%', '');
        $t->set_header('类型', '10%', '');
        $t->set_header('记录时间', '10%', '');
        $t->set_header('操作', '10%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page'];
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q'];

        $t = $this->genDataGrid();

        $list = News::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->render('list');
    }

    public function actionNew(){
        $model=new News('create');
        $msg=null;
        if($_POST['News']){
            $id=Utils::G_Id("news");
            $model->attributes = $_POST['News'];
            $model->news_id=$id;
            $model->record_time=date("Y-m-d H:i:s");
            $rs=$model->save();
            if($rs){
                $attaches=$_POST['attaches'];
                BaseAttach::AddAttaches($attaches,$id,BaseAttach::TYPE_News);
                $msg['status']=1;
                $msg['desc']="添加成功";
                $model=new News('create');
            }else{
                $msg['status']=-1;
                $msg['desc']="添加失败";
            }
        }
        $this->render('new',array('model'=>$model,'msg'=>$msg));
    }

    public function actionEdit(){
        $id=$_GET['id'];
        $model=News::model()->FindByPk($id);
        $msg=null;
        if($_POST['News']){
            //print_r($_POST['News']);
            //$id=Utils::G_Id("news");
            $model->scenario = 'modify';
            $model->attributes = $_POST['News'];
            //$model->news_id=$id;
            $model->record_time=date("Y-m-d H:i:s");
            $rs=$model->save();
            if($rs){
                $attaches=$_POST['attaches'];
                BaseAttach::AddAttaches($attaches,$id,BaseAttach::TYPE_News);
                $msg['status']=1;
                $msg['desc']="修改成功";
            }else{
                $msg['status']=-1;
                $msg['desc']="修改失败";
            }
        }
        $attach=BaseAttach::FindOneByRId($id,BaseAttach::TYPE_News);

        $this->layout='//layouts/m_base';
        $this->render('edit',array('model'=>$model,'msg'=>$msg,'attach'=>$attach));
    }

}