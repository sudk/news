<?php
/*
 * 模块编号: M1001
 */
class DboardController extends BaseController
{

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index',),
                'users' => array('*'),
            ),
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }
    
    protected function beforeAction($action) {
    
    	if (Yii::app()->user->isGuest) {
    		$this->renderPartial('//site/login');
    	} else {
    		return true;
    	}
    }

	public function actionIndex()
	{
        return $this->actionSystem();

	}

	public function actionSystem(){	

        $this->render('system',array());
	}

}