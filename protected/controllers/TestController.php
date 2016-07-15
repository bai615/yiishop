<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author baihua <baihua_2011@163.com>
 */
class TestController extends BaseController {

    public function actionIndex() {

        $model = new Goods();
        $result = $model->updateCounters(array('sale' => 10), 'id=218');
        pprint($result);
//        $info = $model->findByPk(218);
//        pprint($info);

        exit();

        set_time_limit(18000);
        $goodsModel = new Goods();
        $goodsList = $goodsModel->findAll(array(
            'select' => array('id', 'content'),
            'condition' => 'id > 107'
//            'limit' => 10
        ));
        foreach ($goodsList as $value) {
            $dataArr = array();
            preg_match_all('/<img.*?src="(.*?)".*?>/is', $value['content'], $dataArr);
            if (isset($dataArr[1])) {
                pprint($value['id']);
                foreach ($dataArr[1] as $value) {
                    $imgFile = str_replace(
                        array(
                        'http://img20.360buyimg.com',
                        'http://img30.360buyimg.com',
                        'http://img10.360buyimg.com',
                        'http://i04.c.aliimg.com',
                        'http://i02.c.aliimg.com',
                        'http://i01.c.aliimg.com',
                        'http://i05.c.aliimg.com',
                        'http://i03.c.aliimg.com',
                        'http://i00.c.aliimg.com',
                        'http://img.china.alibaba.com',
                        'http://img01.taobaocdn.com',
                        'http://img03.taobaocdn.com',
                        'http://img14.360buyimg.com',
                        'http://img11.360buyimg.com',
                        'http://img13.360buyimg.com',
                        'http://img12.360buyimg.com',
                        '//img10.360buyimg.com',
                        '//img11.360buyimg.com',
                        '//img12.360buyimg.com',
                        '//img13.360buyimg.com',
                        '//img14.360buyimg.com',
                        '//img20.360buyimg.com',
                        '//img30.360buyimg.com',
                        ), array(
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '' .
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        ), $value
                    );
                    $value = str_replace(
                        array(
                        '//img10.360buyimg.com',
                        '//img11.360buyimg.com',
                        '//img12.360buyimg.com',
                        '//img13.360buyimg.com',
                        '//img14.360buyimg.com',
                        '//img20.360buyimg.com',
                        '//img30.360buyimg.com',
                        ), array(
                        'http://img10.360buyimg.com',
                        'http://img11.360buyimg.com',
                        'http://img12.360buyimg.com',
                        'http://img13.360buyimg.com',
                        'http://img14.360buyimg.com',
                        'http://img20.360buyimg.com',
                        'http://img30.360buyimg.com',
                        ), $value
                    );
                    $imgContent = http_get_data($value);
//                    $imgContent = file_get_contents($value);
                    $dirName = dirname($imgFile);
                    if (!file_exists('./jdimg/' . $dirName)) {
                        mkdir('./jdimg/' . $dirName, 0755, true);
                    }
                    $fileName = basename($imgFile);
                    $fp = @fopen('./jdimg/' . $dirName . '/' . $fileName, "a"); //将文件绑定到流    
                    @fwrite($fp, $imgContent); //写入文件  
                    pprint($value);
                }
            }
//            pprint($dataArr);
        }
//        pprint($goodsList);
    }

}

function http_get_data($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();

    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    pprint($return_code);
    return $return_content;
}

/**
 * 功能：php多种方式完美实现下载远程图片保存到本地
 * 参数：文件url,保存文件名称，使用的下载方式
 * 当保存文件名称为空时则使用远程文件原来的名称
 */
function getImage($url, $filename = '', $type = 0) {
    if ($url == '') {
        return false;
    }
    if ($filename == '') {
        $ext = strrchr($url, '.');
//        if ($ext != '.gif' && $ext != '.jpg') {
//            return false;
//        }
        $filename = time() . $ext;
    }
    //文件保存路径
    if ($type) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    $size = strlen($img);
    //文件大小
    $fp2 = @fopen($filename, 'a');
    fwrite($fp2, $img);
    fclose($fp2);
    return $filename;
}
