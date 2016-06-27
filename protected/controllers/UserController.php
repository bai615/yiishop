<?php

/**
 * 用户
 *
 * @author baihua <baihua_2011@163.com>
 */
class UserController extends BaseController {

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
//				'class' => 'system.web.widgets.captcha.CCaptchaAction',
                'class' => 'Captcha',
                'backColor' => 0xE9E5DA,
                'maxLength' => '5', // 最多生成几个字符
                'minLength' => '5', // 最少生成几个字符
                'height' => '40',
                'testLimit' => 0, //限制相同验证码出现的次数,0为不限制
            ),
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * 登录
     */
    public function actionLogin() {
        $model = new SignIn();
        if (Yii::app()->request->isPostRequest) {
            $signInArr = Yii::app()->request->getParam('SignIn');
            $model->username = $signInArr['username'];
            $model->password = $signInArr['password'];
            $model->online = $signInArr['online'];
//            $model->attributes = $_POST['SignIn'];
            $password = $model->password;
            if ($model->validate() && $model->login()) {
                $this->redirect($this->createAbsoluteUrl('home/index'));
            }
            $model->password = $password;
        }
        $this->render('login', array('model' => $model));
    }

    /**
     * 注册
     */
    public function actionRegister() {
        $data = array();
        if (Yii::app()->request->isPostRequest) {
            $data['username'] = Yii::app()->request->getParam('mobile');
            $data['password'] = Yii::app()->request->getParam('password');
            $data['repassword'] = Yii::app()->request->getParam('repassword');
            $data['captcha'] = Yii::app()->request->getParam('captcha');
            $data['agreen'] = Yii::app()->request->getParam('agreen');
            $data['oldCode'] = $this->createAction('captcha')->getVerifyCode();

            $userLogic = new UserLogic();
            //验证用户输入信息
            $checkInfo = $userLogic->checkRegData($data);
            if (0 == $checkInfo['errcode']) {
                $signUpInfo = $userLogic->signUp($data);
                if (1 == $signUpInfo['errcode']) {
                    $data['errmsg'] = $signUpInfo['errmsg'];
                } else {
                    $this->redirect($this->createAbsoluteUrl('common/success', array('message' => '注册成功')));
                }
            } else {
                $data['errmsg'] = $checkInfo['errmsg'];
            }
        }
        $this->render('register', $data);
    }

    /**
     * 检查用户是否已存在
     */
    public function actionCheckName() {
        $username = Yii::app()->request->getParam('mobile');
        $flag = UserLogic::checkByName($username);
        if ($flag) {
            echo CJSON::encode(array('errcode' => 1, 'errmsg' => '您的手机已被占用'));
        } else {
            echo CJSON::encode(array('errcode' => 0, 'errmsg' => 'OK'));
        }
    }

    /**
     * 退出
     */
    public function actionLogout() {
        //清除SESSION信息
        Yii::app()->session['shopUserInfo'] = array();
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        //清除COOKIE信息
        $cookieName = $cookieName = Common::getAutoCookieName();
        $cookie = new CHttpCookie($cookieName, '');
        $cookie->expire = time() - 60;  //有限期
        Yii::app()->request->cookies[$cookieName] = $cookie;
        $this->redirect($this->createAbsoluteUrl('user/login'));
    }

}
