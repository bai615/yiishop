<link href="<?php echo $this->data['libs_url']; ?>/artdialog/skins/aero.css" rel="stylesheet">
<div class="container block_box">
    <div class="row">
        <div class="col-md-4"><h1>结算中心</h1></div>
        <div class="col-md-8">
            <div class="stepflex">
                <dl class="done">
                    <dt class="s-num">1</dt>
                    <dd>1.我的购物车</dd>
                </dl>
                <dl class="doing">
                    <dt class="s-num">2</dt>
                    <dd>2.填写核对订单信息</dd>
                </dl>
                <dl>
                    <dt class="s-num">3</dt>
                    <dd>3.成功提交订单</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="row confirm_info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">填写并核对订单信息</h3>
            </div>
            <div class="panel-body">
                <div class="page-header">
                    <h4>收货人信息</h4>
                </div>
                <div class="">
                    <ul class="list-group">
                        <li class="list-group-item">Cras justo odio</li>
                        <li class="list-group-item"><a href="javascript:void(0);" onclick="addressAdd()">添加新地址</a></li>
                        <li class="list-group-item">Morbi leo risus</li>
                        <li class="list-group-item">Porta ac consectetur ac</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                    </ul>
                </div><!-- /.col-sm-4 -->
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->data['libs_url']; ?>/artdialog/artDialog.js"></script>
<script src="<?php echo $this->data['libs_url']; ?>/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript">
                            function addressAdd() {
                                var urlVal = '<?php echo $this->createAbsoluteUrl('shopping/address');?>';
                                art.dialog.open(urlVal,
                                        {
                                            "id": "addressWindow",
                                            "title": "添加收货地址",
                                            "ok": function (iframeWin, topWin) {
                                                var formObject = iframeWin.document.forms[0];
                                                console.log(formObject)
                                                formObject.submit();
//                                                $.getJSON(formObject.action, $(formObject).serialize(), function (content) {
//                                                    if (content.result == false)
//                                                    {
//                                                        alert(content.msg);
//                                                        return;
//                                                    }
//                                                    var addressLiHtml = template.render('addressLiTemplate', {"item": content.data});
//                                                    $('.addr_list').prepend(addressLiHtml);
//                                                    $('input:radio[name="radio_address"]:first').trigger('click');
//
//                                                    art.dialog({"id": "addressWindow"}).close();
//                                                });
                                                return false;
                                            },
//                                            "height":200,
//                                            "width":500,
                                            "okVal": "添加",
                                            "cancel": true,
                                            "cancelVal": "取消",
                                        });
                                return false;
                            }
</script>