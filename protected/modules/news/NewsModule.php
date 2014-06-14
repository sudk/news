<?php

/**
 * ProjectModule is the module that Pm in the application
 * author:sudk
 */
class NewsModule extends CWebModule {

    public $pageSite;

    public function init() {

        $this->setImport(array(
            'news.models.*',
            'news.components.*',
            'news.components.widgets.*',
        ));
        // import the module-level models and components
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {

            return true;
        }
        else
            return false;
    }

}