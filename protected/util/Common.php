<?php

/**
 * 公共方法集合
 *
 * @author baihua <baihua_2011@163.com>
 */
class Common {

    /**
     * @brief 获取评价分数
     * @param $grade float 分数
     * @param $comments int 评论次数
     * @return float
     */
    public static function gradeWidth($grade, $comments = 1) {
        return $comments == 0 ? 0 : 14 * ($grade / $comments);
    }
    
    /**
     * 获取加密后的密码
     * @param type $password
     * @param type $salt
     * @param type $prefix
     * @return type
     */
    public static function getPwd($password, $salt='', $prefix = 'yiishop_') {
        return md5(sha1($prefix . trim($password) . $salt));
    }

}
