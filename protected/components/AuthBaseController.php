<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AuthBaseController extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/m_main';

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }
    
    public function init() {
        parent::init();
    }
    
    /**
     * Checks if rbac access is granted for the current user
     * @param String $action . The current action
     * @return boolean true if access is granted else false
     */
    protected function beforeAction($action) {
        return true;
    }

    /**
     * The auth items that access is always  allowed. Configured in rbac module's
     * configuration
     * @return The always allowed auth items
     */
    protected function allowedAccess() {
        return array(
            'dboard/index', 'site/s', 'site/index', 'site/error', 'site/contact', 
        	'site/login', 'site/logout', 'site/switchwidth', 'site/updateoperation',
        	'site/switchcity'
        );
    }

}