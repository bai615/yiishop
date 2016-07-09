<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="EasyShop,Easy Shop,Easy 商城，Easy 购物网站,Bootstrap Shop">
        <meta name="keywords" content="EasyShop,Easy Shop,Easy 商城，Easy 购物网站,Bootstrap Shop">
        <link rel="icon" href="../../favicon.ico">

        <title>Theme Template for Bootstrap</title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo $this->data['css_url']; ?>/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap theme -->
        <link href="<?php echo $this->data['css_url']; ?>/bootstrap-theme.min.css" rel="stylesheet">

        <link href="<?php echo $this->data['css_url']; ?>/easyshop.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="<?php echo $this->data['js_url']; ?>/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo $this->data['js_url']; ?>/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
        <script src="<?php echo $this->data['js_url']; ?>/jquery.min.js"></script>
        <script src="<?php echo $this->data['js_url']; ?>/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo $this->data['js_url']; ?>/ie10-viewport-bug-workaround.js"></script>
    </head>

    <body>

        <!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Bootstrap</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Bootstrap</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="/">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0)" style="color: #ba0505;"><b>非运营，仅供学习交流的DEMO展示</b></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        if ($this->_userI):
                            ?>
                            <li><a href="<?php echo $this->createAbsoluteUrl('user/register'); ?>"><?php echo $this->_userI['userName']; ?></a></li>
                            <li><a href="<?php echo $this->createAbsoluteUrl('user/logout'); ?>">退出</a></li>
                            <?php
                        else:
                            ?>
                            <li><a href="<?php echo $this->createAbsoluteUrl('user/login'); ?>">请登录</a></li>
                            <li><a href="<?php echo $this->createAbsoluteUrl('user/register'); ?>">免费注册</a></li>
                        <?php
                        endif;
                        ?>

                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <?php echo $content; ?>

        <footer class="shop-footer">
            <p>Shop template built for <a target="_bank" href="http://getbootstrap.com">Bootstrap</a> by <a target="_bank" href="https://yq.aliyun.com/users/1615464680740592">@大漠胡杨</a>.</p>
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>
        <script type="text/javascript">
            window.alert = function (mess) {
                art.dialog.alert(mess);
            }
            window.confirm = function (mess, bnYes, bnNo)
            {
                if (bnYes === undefined && bnNo === undefined)
                {
                    return eval("window.realConfirm(mess)");
                } else
                {
                    art.dialog.confirm(
                            mess,
                            function () {
                                eval(bnYes)
                            },
                            function () {
                                eval(bnNo)
                            }
                    );
                }
            }
            //立即购买
            var buy_now_url = '<?php echo $this->createAbsoluteUrl('shopping/confirm'); ?>';
        </script>
    </body>
</html>
