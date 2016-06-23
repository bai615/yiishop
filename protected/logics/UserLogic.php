<?php

/**
 * 用户逻辑
 *
 * @author baihua <baihua_2011@163.com>
 */
class UserLogic {

    /**
     * 验证注册信息
     * @param type $data
     * @return type
     */
    public function checkRegData($data) {
        //验证是否选择注册协议
        if (empty($data['agreen'])) {
            return array('errcode' => 1, 'errmsg' => '请选择同意注册协议');
        }
        //验证验证码
        if ($data['captcha'] != $data['oldCode']) {
            return array('errcode' => 1, 'errmsg' => '验证码错误');
        }
        //验证必填项
        if (empty($data['username']) || empty($data['password']) || empty($data['repassword'])) {
            return array('errcode' => 1, 'errmsg' => '信息不完整，请完善信息');
        }
        //验证手机号格式
        if (preg_match('/^1[3587]\d{9}$/', $data['username']) == 0) {
            return array('errcode' => 1, 'errmsg' => '请填写正确的手机号');
        }
        //验证密码长度
        $pwdLen = iconv_strlen($data['password']);
        if (6 > $pwdLen || 32 < $pwdLen) {
            return array('errcode' => 1, 'errmsg' => '请填写密码6-16位');
        }
        //验证两次密码是否一致
        if ($data['password'] != $data['repassword']) {
            return array('errcode' => 1, 'errmsg' => '两次密码不一致');
        }
        return array('errcode' => 0, 'errmsg' => 'OK');
    }

    /**
     * 注册
     * @param type $data
     * @return type
     */
    public function signUp($data) {
        if ($data) {
            $salt = uniqid();
            $userModel = new User();
            $userInfo = $userModel->find(array(
                'select' => 'username',
                'condition' => 'username=:username and is_del=0',
                'params' => array(':username' => $data['username'])
            ));
            if ($userInfo) {
                return array('errcode' => 1, 'errmsg' => '您的手机已被占用');
            } else {
                $userModel->username = $data['username'];
                $userModel->password = Common::getPwd($data['password'], $salt);
                $userModel->salt = $salt;
                $userModel->reg_time = date('Y-m-d H:i:s');
                $userModel->save();
                return array('errcode' => 0, 'errmsg' => 'OK');
            }
        }
        return array('errcode' => 1, 'errmsg' => '注册失败，请稍后重试');
    }

}
