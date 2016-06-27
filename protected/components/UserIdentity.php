<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $userModel = new User();
        $userInfo = $userModel->getUserByName($this->username);
        $newPwd = Common::getPwd($this->password, $userInfo['salt']);

        if (empty($userInfo)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif ($userInfo['password'] !== $newPwd) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
            Yii::app()->session['shopUserInfo'] = array(
                'userId' => $userInfo['id'],
                'userName' => $userInfo['username'],
                'head_ico' => $userInfo['head_ico'],
            );
        }

        return !$this->errorCode;
    }

}
