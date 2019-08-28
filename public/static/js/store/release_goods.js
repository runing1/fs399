var dataobj = {user_id: localStorage.getItem('userId')};
var user_id = localStorage.getItem('userId');
var type = localStorage.getItem('type'); // 店铺类别
var cat_id = localStorage.getItem('catId'); // 线下类别
var store_id = localStorage.getItem('storeId'); // 店铺ID
// 线上
var on_tit = $('.on_tit_input'), // 标题
    on_detail_textarea = $('.detail_textarea'), // 详细描述
    on_category = $('.online_choose'), // 类别
    on_custom = $('.online_custom'), // 自定义
    on_weight = $('.weight_input'), // 净含量
    on_inventory = $('.inventory_input'), // 库存
    on_price = $('.online_price_input'), // 价格
    on_freight = $('.freight_input'); // 运费
// 线下
var off_category = $('.offline_choose'),  // 类别
    off_tit = $('.off_tit_input'), // 商品名称
    off_introduce = $('.off_introduce'), // 商品简介
    off_describe = $('.offline_detail'), // 商品描述
    off_date_choose = $('.date_choose'), // 日期
    off_shop_price = $('.shop_price'), // 价格
    off_market_price = $('.market_price'); // 原价
// 获取自定义属性
var pro_title = new Array(),
    pro_price = new Array(),
    pro_count = new Array(),
    attr_name = new Array(),
    attr_value = new Array(),
    data = new Array(),
    attrArr = new Array();
// 日期、价格
var one_day = '',
    day_price = '';

// 发布商品
function relsasea_goods(){
    // 判断 online/offline
    if(type == "offlin") {
        $('.release_online').html('');
        $.ajax({
            type: 'post',
            url: '/admin/store/store_type',
            data: dataobj,
            dataType: 'json',
            success: function(res){
                if (res.code == 200) {
                    if (res.data.cat_id == 1223) {
                        $('.date_shop_price').css('visibility','visible');
                    } else {
                        $('.hotel_category').hide();
                    }
                    
                } 
            },
            error: function(err){
                console.log(err);
                layer.open({
                    title: '提示',
                    time: 2000,
                    content: '抱歉，请刷新重试！'
                })
            }
        })
    } else {
        $('#goods_release').show();
        $('.release_offline').html('');
    }
}

// 线上自定义
function onlineCustom(){
    // 添加标签
    $('#add_btn').on('click',function(){
        $('#setting_spec .spec_content').append('<div class="spec_item">\
            <input class="spec_tit" type="text" placeholder="自定义标题">\
            <div class="tag_main">\
                <button>添加</button>\
            </div>\
            <span class="close">x</span>\
        </div');
    })

    var resArr = new Array(),
        save_price = new Array(),
        save_count = new Array(),
        save_title = new Array();
    
    // 自定义标签
    $('.custom_tag').on('click',function(){
        var num;
        layer.open({
            type: '1',
            title: '',
            skin: 'fill_box',
            closeBtn: 0,
            area: ['480px','607px'],
            content: $('#setting_spec'),
            btn: ['完成','取消'],
            success: function(){
                if ($('#setting_spec .spec_content').children().length == 0) {
                    $('#setting_spec .spec_content').append('<div class="spec_item">\
                        <input class="spec_tit" type="text" placeholder="自定义标题">\
                        <div class="tag_main">\
                            <button>添加</button>\
                        </div>\
                        <span class="close">x</span>\
                    </div');
                }
                num = $('.spec_tag_box .spec_tag').length;
            },
            yes: function(index){
                var tag_item = $('.spec_item'),
                    arrTit = new Array(),
                    arrCon = new Array(),
                    newArr = new Array();
                // 属性标题
                tag_item.each(function(){
                    var val = $(this).find('.spec_tit').val();
                    arrTit.push(val);
                })
                for(var i=0; i<arrTit.length; i++) {
                    if (arrTit[i] == '') {
                        return layer.msg('属性标题不能为空');
                    } else if (arrTit[i] == arrTit[i-1]) {
                        return layer.msg('属性标题不能相同');
                    }
                }
                // 属性内容
                newArr = [];
                tag_item.each(function(){
                    var subArr = new Array();
                    var tag_box = $(this).find(".spec_tag_box")
                    tag_box.each(function(){
                        var txt = $(this).find('.spec_tag').text()
                        arrCon.push(txt)
                        subArr.push(txt);
                    })
                    newArr.push(subArr);
                })
                for(var i=0; i<arrCon.length; i++) {
                    if (arrCon[i] == '') {
                        return layer.msg('属性内容不能为空');
                    }
                }
                
                computed(newArr);
                // 添加自定义标签到html
                var attr = '';
                for(var i=0; i<$('.spec_tit').length; i++){
                    attr += '<div class="attr_list">\
                        <p class="attr_name">'+ arrTit[i] +'</p>\
                        <p class="attr_value">'+ newArr[i] +'</p>\
                    </div>'
                }
                $('.attr_txt').html(attr);
                var len = $('.spec_tag_box .spec_tag').length;
                if (len == 0) { // 不存在自定义
                    layer.close(index);
                    $('.price_tag').hide();
                    $('.online_inventory,.online_price').show();
                } else if (len == num) { // 自定义未作修改
                    layer.close(index);
                    $('.price_tag').show();
                    $('.online_inventory,.online_price').hide();
                    layer.close(index);
                } else {
                    $('.price_tag').show();
                    $('.online_inventory,.online_price').hide();
                    setPrice();
                    layer.close(index);
                }
            }
        })
    })
    // 价格、库存
    $('.price_tag').on('click',function(){
        setPrice();
    })

    // 计算组合属性
    function computed(data){
        function count(obj1,obj2){
            var a = 0,
                asseArr = new Array();
            for(var i=0; i<obj1.length; i++) {
                for(var j=0; j<obj2.length; j++) {
                    asseArr[a] = obj1[i] + '|' + obj2[j];
                    a++;
                }
            }
            return asseArr;
        }
        for(var k = 0;k<data.length;k++){
            if(k == 0){
                resArr = data[k];
            } else {
                resArr = count(resArr, data[k]);
            }
        }
    }

    // 设置价格、库存
    function setPrice(){
        layer.open({
            type: '1',
            title: '',
            skin: 'price_box',
            area: ['480px','617px'],
            content: $('#price'),
            btn: '完成',
            success: function(){
                var str = '';
                var priceArr = new Array();
                var countArr = new Array();
                var price,count;
                for(var i=0; i<resArr.length; i++){
                    var res = resArr[i].replace(/\|/g,' '); // 将|替换
                    for(var j=0; j<save_title.length; j++){
                        if(resArr[i] == save_title[j]){
                            priceArr[i] = save_price[j];
                            countArr[i] = save_count[j];
                        }
                    }
                    price = priceArr[i];
                    count = countArr[i];
                    if (price == undefined || count == undefined) {
                        str += '<div class="price_item">\
                            <p class="price_txt">'+ res +'</p>\
                            <input class="price_inp" type="text" placeholder="请输入价格" oninput="clearNoNum(this)" />\
                            <input class="count_inp" type="text" placeholder="请输入库存" oninput="clearNoNum(this)" />\
                        </div>'
                        $('.price_content').html(str);
                    } else {
                        str += '<div class="price_item">\
                            <p class="price_txt">'+ res +'</p>\
                            <input class="price_inp" type="text" placeholder="请输入价格" oninput="clearNoNum(this)" value="'+ price +'" />\
                            <input class="count_inp" type="text" placeholder="请输入库存" oninput="clearNoNum(this)" value="'+ count +'"/>\
                        </div>'
                        $('.price_content').html(str);
                    }
                }
            },
            yes: function(index){
                var priceArr = new Array(),
                    countArr = new Array(),
                    count = 0;

                $('.price_item .price_inp').each(function(){
                    return priceArr.push($(this).val());
                })
                $('.price_item .count_inp').each(function(){
                    count = count + parseInt($(this).val());
                    return countArr.push($(this).val());
                })
                for(var i=0; i<priceArr.length; i++){
                    if (priceArr[i] == '') {
                        return layer.msg('价格不能为空');
                    }
                }
                for(var i=0; i<countArr.length; i++){
                    if (countArr[i] == '') {
                        return layer.msg('库存不能为空');
                    }
                }
                save_price = priceArr;
                save_count = countArr;
                save_title = resArr;
                // 添加标签名、价格、库存到html
                var dataValue = '';
                for(var i=0; i<resArr.length; i++){
                    dataValue += '<div class="data_value">\
                        <p class="pro_title">'+ save_title[i] +'</p>\
                        <p class="pro_price">'+ save_price[i] +'</p>\
                        <p class="pro_count">'+ save_count[i] +'</p>\
                    </div>'
                }
                $('.hidden_txt').html(dataValue);
                layer.close(index);
            }
        })
    }
    
    // 添加属性
    $('.spec_item button').live('click',function(){
        var _this = $(this);
        layer.open({
            type: '1',
            title: '',
            skin: 'fill_box',
            closeBtn: 0,
            area: ['360px','140px'],
            content: $('#fill_box'),
            btn: ['确定','取消'],
            success: function(){
                $('#fill_box .add').val('');
            },
            yes: function(index){
                var value = $('#fill_box .add').val();
                if (value == '') {
                    return layer.msg('标签内容不能为空');
                }
                var arrTxt = new Array();
                var tag = _this.parent().find('.spec_tag_box');
                tag.each(function(){
                    var txt = $(this).find('.spec_tag').text();
                    arrTxt.push(txt);
                })
                for(var i=0; i<arrTxt.length; i++) {
                    if (value == arrTxt[i]) {
                        return layer.msg('请不要重复添加相同标签');
                    }
                }
                _this.before('<div class="spec_tag_box"><span class="spec_tag">'+ value +'</span></div>');
                layer.close(index);
            }
        })
    })
    // 修改属性
    $('.spec_tag').live('click',function(){
        var _this = $(this);
        layer.open({
            type: '1',
            title: '',
            skin: 'fill_box',
            closeBtn: 0,
            area: ['360px','140px'],
            content: $('#modify_box'),
            btn: ['确定','取消'],
            success: function(){
                $('#modify_box .modify').val('');
            },
            yes: function(index){
                var value = $('#modify_box .modify').val();
                if (value == '') {
                    return layer.msg('标签内容不能为空');
                }
                var arrTxt = new Array();
                var tag = _this.parent().parent().find('.spec_tag_box');
                tag.each(function(){
                    var txt = $(this).find('.spec_tag').text();
                    arrTxt.push(txt);
                })
                for(var i=0; i<arrTxt.length; i++) {
                    if (value == arrTxt[i]) {
                        return layer.msg('请不要修改成与其他标签相同标签');
                    }
                }
                _this.text(value);
                _this.parent().find('input[type="hidden"]').val(value);
                layer.close(index);
            }
        })
    })
    // 删除
    $('.close').live('click',function(){
        $(this).parent().remove();
    })

    // 批量
    $('.all_set').on('click',function(){
        $('.set_price').val('');
        $('.set_count').val('');
        layer.open({
            type: '1',
            title: '',
            skin: 'fill_box',
            closeBtn: 0,
            area: '360px',
            content: $('#all_set'),
            btn: ['确定','取消'],
            yes: function(index){
                var priceVal = $('.set_price').val(),
                    countVal = $('.set_count').val();
                $('.price_item .price_inp').each(function(){
                    $(this).val(priceVal)
                })
                $('.price_item .count_inp').each(function(){
                    $(this).val(countVal)
                })
                layer.close(index);
            }
        })
    })
}

//获取自定义标签
function defineTag(){
    var leng = $('.hidden_txt').children().length;
    if (leng != 0) {
        pro_title = [];
        pro_price = [];
        pro_count = [];
        $('.hidden_txt .data_value').each(function(){
            var tit = $(this).find('.pro_title').text(),
                price = $(this).find('.pro_price').text(),
                count = $(this).find('.pro_count').text();
            pro_title.push(tit);
            pro_price.push(price);
            pro_count.push(count);
        })
    }
    if (leng == 0) {
        data = [];
    } else {
        data = [];
        for(var i=0; i<pro_title.length; i++){ 
            var json = {'product_name':pro_title[i],'product_price':pro_price[i],'product_number':pro_count[i]};
            data.push(json);
        }
    } 
}

// 获取标签属性
function attrList(){
    var attr_leng = $('.attr_list').length;
    if (attr_leng != 0) {
        var val;
        attr_name = [];
        attr_value = [];
        $('.attr_list').each(function(){
            var name = $(this).find('.attr_name').text(),
                value = $(this).find('.attr_value').text();
            val = value.split(',');  // 字符串转数组
            attr_name.push(name);
            attr_value.push(val);
        })
    }
    
    if (attr_leng == 0) {
        attrArr = [];
    } else {
        attrArr = [];
        for(var i=0; i<attr_name.length; i++){
            var json = {'attr_name':attr_name[i],'attr_value':attr_value[i]};
            attrArr.push(json);
        }
    }
}

// 确认发布
function submit_goods(){
    // 获取优惠方式
    var promotion_method = $('.promotion_info').attr('data-id'),
        promotion_designation = $('.promotion_info').attr('data-designation'),
        promotion_universal = $('.promotion_info').attr('data-universal'),
        address = $('.goods_address').text(),
        goods_address = $.trim(address),
        on_goods_img = [],
        on_goods_detal_img = [],
        off_goods_img = [],
        shop_price, // 线上商品价格
        goods_number; // 线上商品数量

    defineTag();
    attrList();

    if (attrArr.length != 0 && data.length !=0) {
        // 获取最低价格
        var priceArray = pro_price,
            minPrice;
        priceArray.sort(function (a, b) {
            return a-b;
        });
        minPrice = priceArray[0];
        shop_price = minPrice;

        // 获取商品总数
        var count = 0;
        for(var i=0; i<pro_count.length; i++) {
            count += parseInt(pro_count[i]);
        }
        goods_number = count;
    } else {
        shop_price = on_price.val();
        goods_number = on_inventory.val();
    }

    // 线上
    if (type == 'onlin') {
        // 获取线上上传图片src 
        $('.tit_pic .pic').each(function(){
            on_goods_img.push($(this).find('input[type="hidden"]').val());
        })
        $('.on_detail_pic .pic').each(function(){
            on_goods_detal_img.push($(this).find('input[type="hidden"]').val());
        })
        var online_info = {
            goods_name: on_tit.val(), // 名称
            goods_brief: on_detail_textarea.val(), // 描述
            goods_img: on_goods_img, // 商品图
            goods_desc: on_goods_detal_img, // 详情图
            cat_id: on_category.attr('data-id'), // 类别
            product: data, // 自定义属性名
            attr: attrArr, // 自定义属性
            goods_weight: on_weight.val(), // 净含量
            goods_number: goods_number, // 库存
            shop_price: shop_price, // 价格
            fare: on_freight.val(), // 运费
            promotion_method: promotion_method, // 优惠方式
            promotion_designation_price: promotion_designation, // 指定
            promotion_universal_price: promotion_universal, // 通用
            store_id: store_id,
            user_id: user_id,
        };
        console.log(online_info);

        if (on_goods_img == '' || on_goods_img == null) {
            return layer.msg('请上传商品图');
        } else if (on_tit.val() == '' || on_tit.val() == null) {
            return layer.msg('请输入商品名字');
        } else if ((on_detail_textarea.val() == '' || on_detail_textarea.val() == null) && (on_goods_detal_img == '' || on_goods_detal_img == null)) {
            return layer.msg('请描述商品详情或者上传商品详情图');
        } else if (on_category.text() == '请选择' || on_category.text() == null) {
            return layer.msg('请选择类别');
        } else if (on_weight.val() == '' || on_weight.val() == null) {
            return layer.msg('请输入净含量');
        } else if (on_price.val() == '' && data == '' && on_inventory.val() == '' && attrArr == '') {
            return layer.msg('请设置自定义属性或者输入价格/库存');
        } else if (on_freight.val() == '' || on_freight.val() == null) {
            return layer.msg('请输入运费');
        } else {

            $.ajax({
                type: 'post',
                url: '/admin/store/publish_goods',
                data: online_info,
                dataType: 'json',
                success: function(res){
                    // console.log(res)
                    if (res.code == 200) {
                        layer.msg(res.msg);
                        // 清空
                        on_tit.val('');
                        on_detail_textarea.val('');
                        $('.tit_pic .pic').remove();
                        $('.on_detail_pic .pic').remove();
                        product = [];
                        attr = [];
                        on_weight.val('');
                        on_inventory.val('');
                        on_price.val('');
                        on_freight.val('');
                        on_category.text('请选择').css('color','#999');
                        $('.promotion_info').removeAttr('data-id');
                        $('.promotion_info').removeAttr('data-designation');
                        $('.promotion_info').removeAttr('data-universal');
                        $('.promotion_info').html('无');
                        history.go(-1);
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function(err){
                    layer.open({content:'服务器连接出错，请重试'});
                    console.log(err)
                }
            })
        }
    } else { 
        // 获取店铺图片src 
        $('.detail_pic .pic').each(function(){
            off_goods_img.push($(this).find('input[type="hidden"]').val());
        })

        // 获取酒店日期、价格
        $('.date_time_price .item').each(function(){
            one_day += $(this).find('.item_time').text() + ',';
            day_price += $(this).find('.item_price').text() + ',';
        })

        // 美食
        var offline_info = {
            cat_id: off_category.attr('data-id'),
            goods_name: off_tit.val(),
            goods_brief: off_introduce.val(),
            goods_desc: off_describe.val(),
            goods_img: off_goods_img,
            location: goods_address,
            shop_price: off_shop_price.val(),
            market_price: off_market_price.val(),
            store_id: store_id,
            user_id: user_id,
            promotion_method: promotion_method, // 优惠方式
            promotion_designation_price: promotion_designation, // 指定
            promotion_universal_price: promotion_universal, // 通用
        }

        // 酒店
        var offline_hotel_info = {
            cat_id: off_category.attr('data-id'),
            goods_name: off_tit.val(),
            goods_brief: off_introduce.val(),
            goods_desc: off_describe.val(),
            goods_img: off_goods_img,
            location: goods_address,
            start_time: $('#startDate').val(),
            end_time: $('#endDate').val(),
            one_day: one_day,
            day_price: day_price,
            shop_price: off_shop_price.val(),
            market_price: off_market_price.val(),
            store_id: store_id,
            user_id: user_id,
            promotion_method: promotion_method, // 优惠方式
            promotion_designation_price: promotion_designation, // 指定
            promotion_universal_price: promotion_universal, // 通用
        }
        // console.log(offline_hotel_info)

        if (cat_id == 1223){
            if (off_category.attr('data-id') == '' || off_category.attr('data-id') == null) {
                return layer.msg('请选择类别');
            } else if (off_tit.val() == '' || off_tit.val() == null) {
                return layer.msg('请输入标题(套餐或者服务的名称)');
            } else if (off_introduce.val() == '' || off_introduce.val() == null) {
                return layer.msg('商品介绍(简要描述套餐内容或者商品卖点)');
            } else if (off_describe.val() == '' || off_describe.val() == null) {
                return layer.msg('具体描述套餐或服务信息');
            } else if (off_goods_img == '' || off_goods_img == null) {
                return layer.msg('请添加商品图片');
            } else if (off_shop_price.val() == '' || off_shop_price.val() == null) {
                return layer.msg('请输入商品价格');
            } else if (off_date_choose.text() == '选择日期 >') {
                return layer.msg('请选择日期');
            } else if (one_day == '' || one_day == null || day_price == '' || day_price == null) {
                return layer.msg('请按日期定价');
            } else if (off_market_price.val() == '' || off_market_price.val() == null) {
                return layer.msg('请输入商品原价');
            } else {
                var url = '/admin/UnderLine/publishHotel';
                ajaxInfo(url,offline_hotel_info);
            }
        } else {
            if (off_category.attr('data-id') == '' || off_category.attr('data-id') == null) {
                return layer.msg('请选择类别');
            } else if (off_tit.val() == '' || off_tit.val() == null) {
                return layer.msg('请输入标题(套餐或者服务的名称)');
            } else if (off_introduce.val() == '' || off_introduce.val() == null) {
                return layer.msg('商品介绍(简要描述套餐内容或者商品卖点)');
            } else if (off_describe.val() == '' || off_describe.val() == null) {
                return layer.msg('具体描述套餐或服务信息');
            } else if (off_goods_img == '' || off_goods_img == null) {
                return layer.msg('请添加商品图片');
            } else if (off_shop_price.val() == '' || off_shop_price.val() == null) {
                return layer.msg('请输入商品价格');
            } else if (off_market_price.val() == '' || off_market_price.val() == null) {
                return layer.msg('请输入商品原价');
            } else {
                var url = '/admin/UnderLine/publishProduct';
                ajaxInfo(url,offline_info);
            }
        }

        //请求网络
        function ajaxInfo(url,data){
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(res){
                    // console.log(res)
                    layer.msg(res.msg);
                    // 清空
                    $('.online_choose,.offline_choose').text('请选择').css('color','#999');
                    $('.date_choose').text('选择日期').css('color','#999');
                    off_goods_img = [];
                    dates = '';
                    off_tit.val('');
                    off_introduce.val('');
                    off_describe.val('');
                    off_shop_price.val('');
                    off_market_price.val('');
                    $('.promotion_info').removeAttr('data-id');
                    $('.promotion_info').removeAttr('data-designation');
                    $('.promotion_info').removeAttr('data-universal');
                    $('.promotion_info').text('无');
                    // window.location.href = 'index.php/Admin/store/storeManage';
                    history.go(-1);
                },
                error: function(){
                    layer.open({content:'服务器连接出错，请重试'});
                }
            })
        }
    }
}

$(function(){
    var loadingFlag = layer.load();
    setTimeout(function(){
        layer.close(loadingFlag);
        $('#goods_release').show();
    },500)
    relsasea_goods(); // 发布商品
    onlineCustom(); // 自定义
    // 取消
    $('.back_btn').on('click',function(){
        history.go(-1);
    })

    // 确认发布
    $('#submit_btn').on('click',function(){
        submit_goods();
    })    
})