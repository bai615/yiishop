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
                    <ul class="list-group addr_list">
                        <?php
                        if ($addressList):
                            foreach ($addressList as $value):
                                ?>
                                <li class="list-group-item" id="addressItem<?php echo $value['id']; ?>">
                                    <input type="radio" name="radio_address" type="radio" value="<?php echo $value['id']; ?>"/> <?php echo $value['accept_name'] . '&nbsp;&nbsp;&nbsp;' . $value['province_val'] . ' ' . $value['city_val'] . ' ' . $value['area_val'] . ' ' . $value['address']; ?>
                                    [<a href = "javascript:orderFormInstance.addressEdit(<?php echo $value['id']; ?>);" style = "color:#005ea7;">修改地址</a>] 
                                    [<a href = "javascript:orderFormInstance.addressDel(<?php echo $value['id']; ?>);" style = "color:#005ea7">删除</a>]
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <li class="list-group-item"><a href="javascript:void(0);" onclick="addressAdd()">添加新地址</a></li>
                    </ul>
                </div><!-- /.col-sm-4 -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artdialog/artDialog.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript">
                            window.alert = function (mess) {
                                art.dialog.alert(mess);
                            }
                            
                            function addressAdd() {
                                var urlVal = '<?php echo $this->createAbsoluteUrl('shopping/address'); ?>';
                                art.dialog.open(urlVal,
                                        {
                                            "id": "addressWindow",
                                            "title": "添加收货地址",
                                            "ok": function (iframeWin, topWin) {
                                                var formObject = iframeWin.document.forms[0];
//                        console.log(formObject)
//                        formObject.submit();
                                                $.getJSON(formObject.action, $(formObject).serialize(), function (content) {
                                                    if (content.result === false)
                                                    {
                                                        alert(content.msg);
                                                        return false;
                                                    }
                                                    console.log('next')
                                                    var addressLiHtml = template.render('addressLiTemplate', {"item": content.data});
                                                    $('.addr_list').prepend(addressLiHtml);
                                                    $('input:radio[name="radio_address"]:first').trigger('click');

                                                    art.dialog({"id": "addressWindow"}).close();
                                                });
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
                            /**
	 * address初始化
	 */
	addressInit = function()
	{
		var addressList = $('input:radio[name="radio_address"]');
		if(addressList.length > 0)
		{
			addressList.first().trigger('click');
		}
	}
    addressInit();
</script>
<!--收货地址项模板-->
<script type='text/html' id='addressLiTemplate'>
    <li class="list-group-item" id="addressItem<%=item['id']%>">
        <input name="radio_address" type="radio" value="<%=item['id']%>" /> <%=item['accept_name']%>&nbsp;&nbsp;&nbsp;<%=item['province_val']%> <%=item['city_val']%> <%=item['area_val']%> <%=item['address']%> [<a href="javascript:orderFormInstance.addressEdit(<%=item['id']%>);" style="color:#005ea7;">修改地址</a>] [<a href="javascript:orderFormInstance.addressDel(<%=item['id']%>);" style="color:#005ea7">删除</a>]
    </li>
</script>