<?php

/**
 * 登录模型
 *
 * @author baihua <baihua_2011@163.com>
 */
class SignIn extends CActiveRecord {

    public $online;
    private $_identity;

    /**
     * 登录操作
     * @return boolean
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            if($this->online){
                $this->saveAutoLogin($this->username);
            }
//            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
//            $duration = 0;
//            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存自动登录信息
     * @param type $username
     */
    private function saveAutoLogin($username) {
        $ip = Common::real_ip();
        //自动登录设置
        $value = $username . '|' . $ip;
        $value = Common::encrypt($value, 'E');
        $cookieName = Common::getAutoCookieName();
        $cookie = new CHttpCookie($cookieName, $value);
        $cookie->expire = Yii::app()->params['auto_login_time'];  //有限期
        Yii::app()->request->cookies[$cookieName] = $cookie;
    }

    /**
     * 绑定属性名称
     * @return type
     */
    public function attributeLabels() {
        return array(
            'username' => '用户名',
            'password' => '密码',
        );
    }

    /**
     * 验证规则
     * @return type
     */
    public function rules() {
        return array(
            // username and password are required
            array('username', 'required', 'message' => '手机号必填'),
            array('password', 'required', 'message' => '密码必填'),
            array('username', 'mobile', 'allowEmpty' => false, 'message' => '格式不正确'),
            // password needs to be authenticated
            array('password', 'authenticate'),
        );
    }

    /**
     * 验证登录用户名手机格式
     * @param type $attribute
     * @param type $params
     */
    public function mobile($attribute, $params) {
        //验证手机号格式
        if (preg_match('/^1[3587]\d{9}$/', $this->username) == 0) {
            $this->addError($attribute, $params['message']);
        }
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate()) {
                $this->addError('password', '用户名或者密码错误');
            }
        }
    }

    /**
     * model 的静态方法
     * @param type $className
     * @return type
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 对应表名
     * @return string
     */
    public function tableName() {
        return '{{user}}';
    }

}
