<link href="<?php echo $this->data['static_url']; ?>/autovalidate/style.css" rel="stylesheet">
<div class="container block_box">
    <div class="text-right"><a href="/"><i class="glyphicon glyphicon-home"></i> 网站首页</a> &nbsp;&nbsp; 已有账号，<a href="<?php echo $this->createAbsoluteUrl('user/login'); ?>">请登录</a></div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="javascript:void(0)">新用户注册</a></li>
    </ul>
    <div class="user_table_box">
        <form action="" method="post" class="user_register" id="user_reg_form">
            <table class="table">
                <tr>
                    <th class="text-right" style="width: 280px;">手机号</th>
                    <td><input class="form-control" type="text" name='mobile' value="<?php echo isset($username) ? $username : ''; ?>" pattern="mobi" alt="填写正确的手机格式" /><label>填写正确的手机格式</label></td>
                </tr>
                <tr>
                    <th class="text-right">登录密码</th>
                    <td><input class="form-control" type="password" name='password' pattern="^\S{6,32}$" bind='repassword' alt='填写6-32个字符' /><label>填写登录密码，6-32个字符</label></td>
                </tr>
                <tr>
                    <th class="text-right">确认密码</td>
                    <td><input class="form-control" type="password" name='repassword' pattern="^\S{6,32}$" bind='password' alt='重复上面所填写的密码' /><label>重复上面所填写的密码</label></td>
                </tr>
                <tr>
                    <th class="text-right"></th>
                    <td>
                        <?php $this->widget("CCaptcha", array('showRefreshButton' => true, 'clickableImage' => true, 'buttonType' => 'link', 'buttonLabel' => '换一张', 'imageOptions' => array('alt' => '点击换图', 'title' => '点击换图', 'align' => 'absmiddle'))); ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-right">验证码</th>
                    <td>
                        <input class='form-control' type='text' name='captcha' pattern='^\w{5,10}$' alt='填写图片所示的字符' />
                        <label>填写图片所示的字符</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="checkbox" name="agreen" checked="true"/>
                        我已阅读并同意
                        <a id="protocol" href="javascript:;">《用户注册协议》</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" class="btn btn-lg btn-default" value="立即注册"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->data['js_url']; ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->data['static_url']; ?>/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/layer/layer.js"></script> 
<script type="text/javascript">
<?php
if (isset($errmsg)):
    ?>
        layer.ready(function () {
            layer.msg('<?php echo $errmsg; ?>', {icon: 2, time: 2000});
        });
    <?php
endif;
?>
</script>