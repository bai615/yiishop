<?php

/**
 * 基础控制器
 *
 * @author baihua <baihua_2011@163.com>
 */
class BaseController extends CController {

    public $layout = '/layouts/main';
    public $data = array();

    public function init() {
        parent::init();
        $host = Yii::app()->request->getHostInfo();
        $this->data['static_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static';
        $this->data['css_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/css';
        $this->data['js_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/js';
        $this->data['images_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/images';
    }

}
