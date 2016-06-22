<?php

/**
 * Description of Captcha
 *
 * @author user
 */
class Captcha extends CCaptchaAction {

	//重写run方法，使得验证码在页面刷新时刷新
	public function run() {
		if (isset($_GET[self::REFRESH_GET_VAR])) {
			$code = $this->getVerifyCode(true);
			echo CJSON::encode(array(
				'hash1' => $this->generateValidationHash($code),
				'hash2' => $this->generateValidationHash(strtolower($code)),
				'url' => $this->getController()->createUrl($this->getId(), array('v' => uniqid())),
			));
		} else {
			$this->renderImage($this->getVerifyCode(true));
			Yii::app()->end();
		}
	}

}
