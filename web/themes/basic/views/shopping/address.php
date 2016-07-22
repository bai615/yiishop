<link href="<?php echo $this->data['css_url']; ?>/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $this->data['css_url']; ?>/address.css" rel="stylesheet">
<form role="form" class="add_form" id="address_add_form" name="addressForm" method="" action="<?php echo $this->createAbsoluteUrl('shopping/addressAdd'); ?>" callback="formCallback();">
    <input type="hidden" name="id" />
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
<!--
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->data['js_url']; ?>/Validform.min.js"></script>
-->
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $this->data['libs_url']; ?>/artTemplate/area_select.js"></script>
<script type="text/javascript">
                var area_url = '<?php echo $this->createAbsoluteUrl('common/areaChild'); ?>';
                $(function () {
//        $('.skin-minimal input').iCheck({
//            checkboxClass: 'icheckbox-blue',
//            radioClass: 'iradio-blue',
//            increaseArea: '20%'
//        });
//
//        $("#address_add_form").Validform({
//            tiptype: 2,
//        });

                    //初始化地域联动
                    template.compile("areaTemplate", areaTemplate);
<?php
if ($addressRow):
    ?>
                        $('input[name=id]').val("<?php echo $addressRow['id']; ?>");
                        $('input[name=accept_name]').val("<?php echo $addressRow['accept_name']; ?>");
                        $('input[name=address]').val("<?php echo $addressRow['address']; ?>");
                        $('input[name=mobile]').val("<?php echo $addressRow['mobile']; ?>");
                        createAreaSelect('province', 0, "<?php echo $addressRow['province']; ?>");
                        createAreaSelect('city', "<?php echo $addressRow['province']; ?>", "<?php echo $addressRow['city']; ?>");
                        createAreaSelect('area', "<?php echo $addressRow['city']; ?>", "<?php echo $addressRow['area']; ?>");
    <?php
else:
    ?>
                        createAreaSelect('province', 0, "");
<?php
endif;
?>

                });

                function formCallback() {
                    return false;
                }
</script>