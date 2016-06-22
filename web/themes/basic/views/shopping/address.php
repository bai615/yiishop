<link href="<?php echo $this->data['css_url']; ?>/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $this->data['css_url']; ?>/address.css" rel="stylesheet">
<form role="form" class="add_form" id="address_add_form" method="post" action="<?php echo $this->createAbsoluteUrl('shopping/addressAdd'); ?>">
    <div class="form-group">
        <div class="col-xs-3">
            <label for="firstname" class="">姓名</label>
        </div>
        <div class="col-xs-9 form_content">
            <input id="firstname" type="text" name="accept_name" datatype="*" nullmsg=""/>
        </div>
    </div>
    <div class="form-group province">
        <div class="col-xs-3">
            <label for="firstname" class="">省份</label>
        </div>
        <div class="col-xs-9 form_content">
            <select class="form-control" name="province" child="city,area" onchange="areaChangeCallback(this);">
            </select>
        </div>
    </div>
    <div class="form-group city">
        <div class="col-xs-3">
        </div>
        <div class="col-xs-9 form_content">
            <select class="form-control" name="city" child="area" parent="province" onchange="areaChangeCallback(this);">
            </select>
        </div>
    </div>
    <div class="form-group district">
        <div class="col-xs-3">
        </div>
        <div class="col-xs-9 form_content">
            <select class="form-control" name="area" parent="city" >
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-3">
            <label for="firstname" class="">地址</label>
        </div>
        <div class="col-xs-9 form_content">
            <input id="firstname" name='address' type="text"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-3">
            <label for="firstname" class="">手机</label>
        </div>
        <div class="col-xs-9 form_content">
            <input id="firstname" name='mobile' type="text"/>
        </div>
    </div>
</form>
<script src="<?php echo $this->data['js_url']; ?>/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->data['static_url']; ?>/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->data['js_url']; ?>/Validform.min.js"></script>
<script type="text/javascript" src="<?php echo $this->data['static_url']; ?>/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $this->data['static_url']; ?>/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $this->data['static_url']; ?>/artTemplate/area_select.js"></script>
<script type="text/javascript">
    var area_url = '<?php echo $this->createAbsoluteUrl('common/areaChild'); ?>';
    $(function () {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        $("#address_add_form").Validform({
            tiptype: 2,
        });

        //初始化地域联动
        template.compile("areaTemplate", areaTemplate);
        createAreaSelect('province', 0, "");
    });
</script>