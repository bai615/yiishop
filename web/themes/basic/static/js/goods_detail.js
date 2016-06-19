deliveryProvince = 0;
deliveryProvinceString = '';
$(function () {
    //规格的选择
    $(".goods_spec_value a").click(function () {
        var specObj = $.parseJSON($(this).attr("value"));
        //清除同规格下已选数据
        $('#selectedSpan' + specObj.id).remove();
        //已经为选中状态时
        if ($(this).attr('class') === 'spec_current') {
            $('#selectedSpan' + specObj.id).remove();
            $(this).removeClass('spec_current');
        } else {
            //清除同行中其余规格选中状态
            $(this).parent('div').siblings().children('a').removeClass('spec_current');
            $(this).addClass('spec_current');
            if ('1' === specObj.type) {
                $("#specSelected").append('<span id="selectedSpan' + specObj.id + '">“' + specObj.value + '”</span>');
            }
        }

        if (checkSpecSelected()) {
            //整理规格值
            var specArray = [];
            $('[name="specCols"]').each(function () {
                specArray.push($(this).find('a.spec_current').attr('value'));
            });
            var specJSON = '[' + specArray.join(",") + ']';
            console.log(specJSON);
        }
    });
});
//检查规格选择是否符合标准
function checkSpecSelected()
{
    if ($('[name="specCols"]').length === $('[name="specCols"] .spec_current').length)
    {
        return true;
    }
    return false;
}
/**
 * 购物车数量的加减
 * @param code 增加或者减少购买的商品数量
 */
function modified(code)
{
    var buyNums = parseInt($.trim($('#buyNums').val()));
    switch (code)
    {
        case 1:
            {
                buyNums++;
            }
            break;

        case -1:
            {
                buyNums--;
            }
            break;
    }

    $('#buyNums').val(buyNums);
    checkBuyNums();
}
//检查购买数量是否合法
function checkBuyNums()
{
    //购买数量小于0
    var buyNums = parseInt($.trim($('#buyNums').val()));
    if (isNaN(buyNums) || buyNums <= 0)
    {
        $('#buyNums').val(1);
        return;
    }

    //购买数量大于库存
    var storeNums = parseInt($.trim($('#data_storeNums').text()));
    if (buyNums >= storeNums)
    {
        $('#buyNums').val(storeNums);
        return;
    }

    //运费查询
    //	delivery(deliveryProvince,deliveryProvinceString);
}
