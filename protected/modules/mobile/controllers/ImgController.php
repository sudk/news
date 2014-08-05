<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class ImgController extends MobileController {

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
        $args['type']=BaseAttach::TYPE_News;
        $rs = BaseAttach::queryList($page, $this->page_size, $args);

        $this->renderPartial('_list', array('rs' => $rs));
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
        $model=BaseAttach::model()->findByPk($id);
        $this->render('detail',array('model'=>$model));
    }

}