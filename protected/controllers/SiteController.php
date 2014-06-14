<?php

Yii::import('application.controllers.site.*');

class SiteController extends BaseController {

    public $gridId = 'apply_list';
    public $pageSize = 15;
    private $_rec;

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'login', 'captcha', '401', 'error', 'getpass', 'chart', 'upload', 'apply', 'applyquery', 'queryarea'),
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


    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            return $this->actionLogin();
        }
        $this->redirect('index.php?r=dboard');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->renderPartial('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (isset($_POST['LoginForm'])) {
            $form = $_POST['LoginForm'];
            $hasErrors = false;
            if ($form['username'] == '' || $form['passwd'] == '') {
                $message = '用户名或口令不能为空！';
                $hasErrors = true;
            } elseif ($form['captcha'] != $this->createAction('captcha')->getVerifyCode()) {
                $message = '验证码错误！';
                $hasErrors = true;
            }
            if (!$hasErrors) {
                //var_dump($form);
                $identity = new UserIdentity(trim($form['username']), trim($form['passwd']));
                $identity->authenticate();
                switch ($identity->errorCode) {
                    case UserIdentity::ERROR_NONE:
                        $duration = isset($form['rememberMe']) ? 3600 * 24 * 1 : 0; // 1 day
                        Yii::app()->user->login($identity);
                        if ($duration !== 0) {
                            setcookie('posm_loginid', trim($form['username']), time() + $duration, Yii::app()->request->baseUrl);
                        } else {
                            unset($_COOKIE['posm_loginid']);
                            setcookie('posm_loginid', NULL, -1);
                        }

                        $this->redirect(Yii::app()->user->returnUrl);
                        return;
                        break;
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $message = '用户名错误！';
                        break;
                    case UserIdentity::ERROR_PASSWORD_INVALID:
                        $message = '密码错误！';
                        break;
                    case 200:
                        $message = '没有分配权限！';
                        break;
                    case UserIdentity::ERROR_USER_DISABLE:
                        $message = '用户已被注销！';
                        break;
                    case UserIdentity::ERROR_USER_FREEZE:
                        $message = '用户已被冻结！';
                        break;
                    default: // UserIdentity::ERROR_PASSWORD_INVALID
                        $message = '用户名或口令错误！';
                        break;
                }
            }
        } else {
            //echo 'returnUrl:'.Yii::app()->user->returnUrl;
        }

        $this->renderPartial('login', array('form' => $form, 'message' => $message, 'loginid' => $_COOKIE['ecard_loginid']));
    }

    /**
     *
     */
    public function actionGetpass() {
        $hasErrors = false;
        $username = $_GET['username'];
        $captcha = $_GET['captcha'];
        if ($username == '') {
            echo '用户名不能为空！';
            return;
        }
        if ($captcha == '') {
            echo '验证码不能为空！';
            return;
        }
        if ($captcha != $this->createAction('captcha')->getVerifyCode()) {
            echo '验证码错误！';
            return;
        }

        $staff = Staff::model()->find('loginid=:loginid', array(':loginid' => $username));
        if ($staff == false) {
            echo '用户名无效！';
            return;
        }

        if ($staff->phone == '') {
            echo '用户的手机号不存在，无法发送短信';
            return;
        }

        $newpasswd = rand(100000, 999999);

        $staff->passwd = $newpasswd;
        $staff->save();

        $content = '尊敬的用户，您的口令已被重置为' . $newpasswd . '。';

        Yii::app()->redis->getClient()->lPush("ark_tool_queue", 'sendsms|' . $staff->phone . '|' . $content);

        echo '密码重置短信已经发送，请注意查收短信。';
        return;
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * 切换屏幕宽度
     */
    public function actionPasswd() {
        $model = Staff::model()->findByPk(Yii::app()->user->id);
        if (count($_POST)) {
            $msg['status'] = "-1";
            if ($_POST['current_passwd'] == '' || $_POST['new_passwd'] == '') {
                $msg['msg'] = "密码不能为空!";
            } elseif ($_POST['new_passwd'] != $_POST["confirm_passwd"]) {
                $msg['msg'] = "确认密码与新密码不相等!";
            } else {

                if (crypt($_POST['current_passwd'], $model->password) != $model->password) {
                    $msg['msg'] = '当前密码错误';
                } else {
                    $model->password = crypt($_POST['new_passwd']);
                    $model->save();
                    $msg['msg'] = "修改成功！";
                    $msg['status'] = "1";
                }
            }
        }
        $this->layout = '//layouts/base';
        $this->render('passwd', array('msg' => $msg, 'model' => $model));
    }

    /**
     * 更新operation权限配置
     */
    public function actionUpdateoperation() {
        //if(Yii::app()->user->id != 'wangdy') return;
        $a = Utils::getControllersActions();
        $config = require(Yii::app()->basePath . '/config/main.php');

        if (count($config['modules']) > 0) {
            foreach ($config['modules'] as $module_name => $module) {
                if ($module_name == p)
                    continue;
                //$actions = Utils::getControllersActions($module_name);
                $actions = array();
                exec(Yii::app()->basePath . '/yiic reflect run --m=' . $module_name, $actions);
                //print_r($actions);
                if (count($actions) > 0) {
                    foreach ($actions as $action) {
                        if (strpos($action, '/') > 0)
                            $a[] = $module_name . '/' . $action;
                    }
                }
            }
        }
        $s = '<?php

return array(
';
        $s2 = '<?phpreturn array(';
        foreach ($a as $action) {
            echo "'" . $action . "',";
            $s .= "
    '" . $action . "' => array('type' => 0),";
            $s2 .= "
    '$action' => array('title' => '','item' => array(
        '' => '',
    )),";
        }
        $s .= "\n);";

        //echo nl2br($s);
        file_put_contents(Yii::app()->basePath . '/data/operation.php', $s);
        //file_put_contents(Yii::app()->basePath . '/data/optdescription.php', $s2);
        echo 'ok.';
    }

    /**
     * 更新operation权限配置
     */
    public function actionShowtask() {
        //if(Yii::app()->user->id != 'wangdy') return;
        $a = Utils::getControllersActions();
        $config = require(Yii::app()->basePath . '/config/main.php');

        if (count($config['modules']) > 0)
            foreach ($config['modules'] as $module_name => $module) {
                if ($module_name == p)
                    continue;
                //$actions = Utils::getControllersActions($module_name);
                $actions = array();
                exec(Yii::app()->basePath . '/yiic reflect run --m=' . $module_name, $actions);
                if (count($actions) > 0)
                    foreach ($actions as $action) {
                        $a[] = $module_name . '/' . $action;
                    }
            }

        $cs = array();
        foreach ($a as $action) {
            //echo "'" . $action . "',<br>";
            $items = explode('/', $action);
            if (count($items) == 2) {
                $cs[$items[0]][] = $action;
            } else {
                $cs[$items[0] . '/' . $items[1]][] = $action;
            }
        }
        //var_dump($cs);
        foreach ($cs as $c => $actions) {
            echo "'$c' => array(";
            foreach ($actions as $action) {
                echo "'$action',";
            }
            echo "\n<br/><br/>";
        }
    }

    //上传图片
    public function actionPicupload(){
        $file = $_FILES['attach'];
        if($file){
            //上传图片
            if($file['name']){
                $file_rs = Utils::fileUpload($file);
                $msg['status'] = $file_rs['status'];
                $msg['msg'] = $file_rs['desc'];
                $msg['fullpath'] = Yii::app() -> params['upload_file_path']."/".$file_rs['savename'];
                $msg['filename'] = $file_rs['savename'];
                $imgsize = getimagesize($msg['fullpath']);
                $msg["pic_w"] = $imgsize[0];
                $msg["pic_h"] = $imgsize[1];
            }

        }else{
            $msg['status'] = -1;
            $msg['msg'] = "没有上传任何图片";

        }

        print_r(json_encode($msg));

    }

}
