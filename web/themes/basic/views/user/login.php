<div class="container block_box">
    <div class="user_login_box">
        <div class="login_ad">
            <img src="/upload/login_ad.png" alt="" title="" style="height: 400px;" />
        </div>
        <div class="login_form">
            <h2>欢迎登录</h2>
            <form>
                <table>
                    <tr>
                        <td class="text-right"><a href="<?php echo $this->createAbsoluteUrl('user/register'); ?>">立即注册»</a></td>
                    </tr>
                    <tr>
                        <td class="username"><i></i><input type="text" class="form-control" name="username" /></td>
                    </tr>
                    <tr>
                        <td class="password"><i></i><input type="password" class="form-control" name="password" /></td>
                    </tr>
                    <tr>
                        <td><span><input type="checkbox" name="online">自动登录</span><span class="find_pwd"><a href="">忘记密码</a></span></td>
                    </tr>
                    <tr>
                        <td class="text-center"><button type="submit" class="btn btn-lg btn-danger"> 登&nbsp;录 </button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="other_login">
                                <p>使用合作网站登录</p>
                                <dl>
                                    <dt><a href="javascript:void(0)" title="QQ" class="qq">QQ</a></dt>
                                    <dt><a href="javascript:void(0)" title="微信" class="wx">微信</a></dt>
                                    <dt><a href="javascript:void(0)" title="新浪微博" class="wb">新浪微博</a></dt>
                                    <dt><a href="javascript:void(0)" title="支付宝" class="alpay">支付宝</a></dt>
                                </dl>
                            </div>
                        </td>
                    </tr>
                </table>
                
            </form>
        </div>
    </div>
</div>