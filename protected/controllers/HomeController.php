<?php

/**
 * 首页
 *
 * @author bh
 */
class HomeController extends BaseController {

    public function actionIndex() {
        pprint(Yii::getFrameworkPath());
        echo 'ok';
        $this->render('index');
    }

}
