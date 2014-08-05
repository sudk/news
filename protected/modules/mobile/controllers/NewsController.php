<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class NewsController extends MobileController {

    public $defaultAction = 'list';

    /**
     * 查询
     */
    public function actionGrid()
    {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page']=$_GET['page']+1;
        $args = $_GET['q']; //查询条件
        if ($_REQUEST['q_value'])
        {
            $args[$_REQUEST['q_by']] = $_REQUEST['q_value'];
        }
        $rs = News::queryList($page, $this->page_size, $args);
        $attachs=BaseAttach::FindByNewsRows($rs['rows'],BaseAttach::TYPE_News);

        $this->renderPartial('_list', array('rs' => $rs,'attachs'=>$attachs));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->render('list');
    }

    /**
     * 列表
     */
    public function actionDetail()
    {
        $this->layout='//layouts/second';
        $id=$_GET['id'];
        $model=News::model()->findByPk($id);
        $attach=BaseAttach::FindOneByRId($id,BaseAttach::TYPE_News);
        if(!$img){
            $img['path']="base.jpg";
        }
        $this->render('detail',array('model'=>$model,'attach'=>$attach));
    }

    public function actionMap()
    {
        $this->renderPartial('map');
    }
}