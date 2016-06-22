<div class="container block_box">
    <div class="text-right"><a href="/"><i class="glyphicon glyphicon-home"></i> 网站首页</a> &nbsp;&nbsp; 已有账号，<a href="<?php echo $this->createAbsoluteUrl('user/login'); ?>">请登录</a></div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="javascript:void(0)">新用户注册</a></li>
    </ul>
    <div class="user_table_box">
        <form action="" method="" class="user_register">
            <table class="table">
                <tr>
                    <td>手机号</td>
                    <td><input class="form-control" type="text" name='mobile' pattern="mobi" alt="填写正确的手机格式" /></td>
                    <td>填写正确的手机格式</td>
                </tr>
                <tr>
                    <td>登录密码</td>
                    <td><input class="form-control" type="password" name='password' pattern="^\S{6,32}$" bind='repassword' alt='填写6-32个字符' /></td>
                    <td>填写登录密码，6-32个字符</td>
                </tr>
                <tr>
                    <td>确认密码</td>
                    <td><input class="form-control" type="password" name='repassword' pattern="^\S{6,32}$" bind='password' alt='重复上面所填写的密码' /></td>
                    <td>重复上面所填写的密码</td>
                </tr>
                <tr>
                    <td>验证码</td>
                    <td><input class='form-control' type='text' name='captcha' pattern='^\w{5,10}$' alt='填写图片所示的字符' /></td>
                    <td>填写图片所示的字符</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">
                        <input type="checkbox" name="agreen" checked="true"/>
                        我已阅读并同意
                        <a id="protocol" href="javascript:;">《用户注册协议》</a>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">
                        <input type="submit" class="btn btn-lg btn-default" value="立即注册"/>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
</div>