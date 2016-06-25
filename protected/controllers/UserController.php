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
    
    public function actionLogin(){
        $this->render('login');
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

}
