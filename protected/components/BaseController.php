<?php

/**
 * 基础控制器
 *
 * @author baihua <baihua_2011@163.com>
 */
class BaseController extends CController {

    public $user;
    public $layout = '/layouts/main';
    public $data = array();
    public $_userI = array();

    public function init() {
        parent::init();
        $this->_userI = Yii::app()->session['shopUserInfo'];
        //处理自动登录
        $cookieName = Common::getAutoCookieName();
        if (isset($_COOKIE[$cookieName]) && empty($this->_userI)) {
            $cookie = Yii::app()->request->getCookies();
            $value = $cookie[$cookieName]->value;
            list($userName, $lastLoginIP) = explode('|', Common::encrypt($value, 'D'));
            $newIP = Common::real_ip();
            //本次登录IP与上一次登录IP一致时
            if ($newIP == $lastLoginIP) {
                $userModel = new User();
                $userInfo = $userModel->getUserByName($userName);
                //检索出用户信息并且该用户没有被锁定时，保存登录状态
                if ($userInfo) {
                    Yii::app()->session['shopUserInfo'] = array(
                        'userId' => $userInfo['id'],
                        'userName' => $userInfo['username'],
                        'head_ico' => $userInfo['head_ico'],
                    );
                    //重新加载页面
                    die("<script language=JavaScript> location.replace(location.href);</script>");
                }
            }
        }

        $host = Yii::app()->request->getHostInfo();
        $this->data['static_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static';
        $this->data['libs_url'] = $host . '/libs';
        $this->data['css_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/css';
        $this->data['js_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/js';
        $this->data['images_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/images';
    }

    public function is_login() {
        if (empty($this->_userI)) {
            $callBackUrl = $this->createAbsoluteUrl($this->id . '/' . $this->action->id) . '?' . http_build_query($_GET);
            $this->redirect($this->createAbsoluteUrl( 'user/login', array('callback'=>$callBackUrl)));
        }
    }

}
