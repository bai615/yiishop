<?php

/**
 * 用户
 *
 * @author baihua <baihua_2011@163.com>
 */
class UserController extends BaseController {
    
    /**
     * 注册
     */
    public function actionRegister(){
        $this->render('register');
    }
}
