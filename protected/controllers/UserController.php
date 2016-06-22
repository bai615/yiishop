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
                'maxLength' => '4', // 最多生成几个字符
                'minLength' => '4', // 最少生成几个字符
                'height' => '40',
                'testLimit' => 0, //限制相同验证码出现的次数,0为不限制
            ),
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * 注册
     */
    public function actionRegister() {
        $this->render('register');
    }

}
