var type = localStorage.getItem('type'),
    user_id = localStorage.getItem('userId'),
    store_id = localStorage.getItem('storeId'),
    cat_id = localStorage.getItem('catId'),
    goods_id = $('input[name="goods_id"]').val(),
    goods_data = {goods_id:$('input[name="goods_id"]').val()},
    attr_state; // 0 未修改  1 修改价格、规格  2 修改属性
// 线上商品
var on_tit = $('.on_tit_input'), // 标题
    on_detail_textarea = $('.detail_textarea'), // 详细描述
    on_category = $('.online_choose'), // 类别
    on_custom = $('.online_custom'), // 自定义
    on_weight = $('.weight_input'), // 净含量
    on_inventory = $('.inventory_input'), // 库存
    on_price = $('.online_price_input'), // 价格
    on_freight = $('.freight_input'); // 运费
// 线下商品
var off_choose = $('.offline_choose'),
    off_tit = $('.off_tit_input'),
    off_introduce = $('.off_introduce'),
    off_desc = $('.offline_detail'),
    off_date = $('.date_choose'),
    off_shop_price = $('.shop_price'),
    off_market_price = $('.market_price'),
    off_pro_info = $('.promotion_info');
// 获取自定义属性
var pro_title = new Array(),
    pro_price = new Array(),
    pro_count = new Array(),
    pro_id = new Array(),
    goods_attr = new Array(),
    dataArray = new Array(),
    attrArr = new Array(),
    attr_id,
    // 编辑之前
    oldTitle = new Array(),
    oldPrice = new Array(),
    oldCount = new Array(),
    // 编辑之后
    newTitle = new Array(),
    newPrice = new Array(),
    newCount = new Array();
// 日期、价格
var one_day = '',day_price = '';
var oldDate,newDate;

// 判断两个数组是否相等
function equar(a, b) {
    // 判断数组的长度
    if (a.length != b.length) {
        return false
    } else {
        // 循环遍历数组的值进行比较
        for (let i = 0; i < a.length; i++) {
            if (a[i] != b[i]) {
                return false
            }
        }
        return true;
    }
}

//获取自定义标签
function defineTag(){
    var leng = $('.hidden_txt').children().length;
    if (leng == 0) {
        dataArray = [];
    } else {
        pro_title = [];
        pro_price = [];
        pro_count = [];
        pro_id = [];
        goods_attr= [];
        $('.hidden_txt .data_value').each(function(){
            var tit = $(this).find('.pro_title').text(),
                price = $(this).find('.pro_price').text(),
                count = $(this).find('.pro_count').text(),
                ids = $(this).find('.pro_id').text(),
                attrs = $(this).find('.pro_attr').text();
            pro_title.push(tit);
            pro_price.push(price);
            pro_count.push(count);
            if (ids == '' || attrs == '' || ids == undefined || attrs == undefined) {
                pro_id = [];
                goods_attr= [];
            } else {
                pro_id.push(ids);
                goods_attr.push(attrs);
            }
        })
        dataArray = [];
        for(var i=0; i<pro_title.length; i++){ 
            if (pro_id.length != 0 || goods_attr.length != 0) {
                var json = {'product_name':pro_title[i],'product_price':pro_price[i],'product_number':pro_count[i],'product_id':pro_id[i],'goods_attr':goods_attr[i]};
            } else {
                var json = {'product_name':pro_title[i],'product_price':pro_price[i],'product_number':pro_count[i]};
            }
            dataArray.push(json);
        }
    } 
}

// 获取标签属性
function attrList(){
    var attr_leng = $('.attr_list').length,
        attr_name = new Array(),
        attr_value = new Array();

    if (attr_leng == 0) {
        attrArr = [];
    } else {
        attr_name = [];
        $('.attr_txt .attr_name').each(function(){
            var name = $.trim($(this).text());
            attr_name.push(name);
        })
        console.log(attr_name)
        attr_value = [];
        $('.spec_item').each(function(){
            var value = [];
            $(this).find('.spec_tag').each(function(){
                var id = $.trim($(this).attr("data-id"));
                var text = $.trim($(this).text());
                if (id == '') {
                    var json = {'attr_value':text};
                } else {
                    var json = {'attr_value':text,'goods_attr_id':id};
                }
                value.push(json);
            })
            attr_value.push(value);
        })

        attrArr = [];
        for (var i = 0; i < attr_name.length; i++) {
            var json = {'attr_name':attr_name[i],'attr_value':attr_value[i]};
            attrArr.push(json);
        }
        console.log(attrArr);
    }
}

// 商品信息
function goodsInfo(){
    var loadingFlag = layer.load();
    if (localStorage.getItem('type') == 'offlin') {
        if (cat_id == 1223) {
            $('.date_shop_price').css('visibility','visible');
            $.ajax({
                type: 'GET',
                url: '/admin/UnderLine/editHotel',
                data: goods_data,
                dataType: 'json',
                async: true,
                success: function(res){
                    var arr = [],
                        str = '';
                    console.log(res)
                    if (res.code == 200) {
                        layer.close(loadingFlag);
                        $('#goods_release').show();
                        $('.release_online').html('');

                        var msg = res.data,
                            promotion_method = res.data.promotion_method,
                            universal_price = res.data.promotion_universal_price,
                            designation_price = res.data.promotion_designation_price;

                        $.each(res.data.time_and_price,function(index,element){
                            str = element.on_day.split('-')[2];
                            arr.push(str);
                        })

                        var startTime = msg.start_time.replace(/-/g, '/'); // 设置的开始时间
                        var starttime = new Date(startTime);
                        var nowTime = new Date(); // 当前时间
                        starttime = starttime.getTime(); // 设置的开始时间的时间戳
                        nowtime = nowTime.getTime(); // 当前时间的时间戳
                        var days = parseInt((nowtime - starttime) / 1000 / 60 / 60 / 24); // 天数
                        var leng;
                        var new_time_and_price = [];
                        new_time_and_price = msg.time_and_price.slice(days,msg.time_and_price.length-1);
                        // console.log(new_time_and_price)

                        if (nowtime > starttime) {
                            leng = msg.time_and_price.length-1-days;
                        }

                        if ($('.hotel_category').find('.dateItem').length == 0) {
                            for(var i=0; i<leng; i++) {
                                $('.hotel_category').append('<span class="dateItem" style="display:none;">'+ new_time_and_price[i].on_day +'</span>')
                            }
                        }

                        // 按日期的价格
                        var str = '';
                        $.each(msg.time_and_price,function(index,element){
                            // console.log(element)
                            str += '<div class="item">\
                                        <div class="item_time">'+ element.on_day +'</div>\
                                        <div class="item_price">'+ element.day_price +'</div>\
                                    </div>'
                        })
                        $('.date_time_price').html(str);

                        oldDate = '开始时间'+' '+msg.start_time +' '+'结束时间'+' '+msg.end_time;
                        off_choose.text(msg.cat_name).css('color','#333');
                        off_choose.attr('data-id',msg.cat_id);
                        off_tit.val(msg.goods_name);
                        off_introduce.val(msg.goods_brief);
                        off_desc.val(msg.goods_desc);
                        // 商品图片
                        var off_img = msg.imgs;
                        var path = msg.path;
                        function storeImg(){
                            for(var i=0; i<off_img.length; i++) {
                                var url = path + off_img[i].img_original;
                                $('#off_detail_goods_file').before('<div class="pic"><input type="hidden" value="'+ off_img[i].img_original +'" /><img src="'+ url +'" alt="" /><p class="close"></p></div>');
                            }
                        }
                        // 删除商品图片
                        $(document).ready(function(){
                            storeImg();
                            $('.pic .close').live('click',function(){
                                $(this).parent().remove();
                            })
                        })
                        off_date.text('开始时间'+' '+msg.start_time +' '+'结束时间'+' '+msg.end_time).css('color','#333');
                        
                        $('.start_date').val(msg.start_time);
                        $('.end_date').val(msg.end_time);
                        $('#startDate').val(msg.start_time);  //入住的天数
                        $('#endDate').val(msg.end_time);      //离店的天数
                        off_shop_price.val(msg.shop_price);
                        off_market_price.val(msg.market_price);
                        // 优惠
                        if (msg.promotion_method == 3) {
                            off_pro_info.html('<span class="promotion_txt">指定店铺</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ msg.promotion_designation_price +'</span>\
                            <span style="margin-left:20px" class="promotion_txt">全网通用</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ msg.promotion_universal_price +'</span>').css('color','#333');
                            off_pro_info.attr('data-id','3');
                            off_pro_info.attr('data-designation',msg.promotion_designation_price);
                            off_pro_info.attr('data-universal',msg.promotion_universal_price);
                        }
                        // 优惠弹框
                        promotionBox(promotion_method,universal_price,designation_price);
                    } else {
                        return layer.msg(res.msg);
                    }
                },
                error: function(){
                    layer.open({content:'服务器连接出错，请重试'});
                }
            })
        } else {
            $('.hotel_category').hide();
            $.ajax({
                type: 'GET',
                url: '/admin/UnderLine/editProduct',
                data: goods_data,
                dataType: 'json',
                async: true,
                success: function(res){
                    console.log(res)
                    var msg = res.data,
                        promotion_method = res.data.promotion_method,
                        universal_price = res.data.promotion_universal_price,
                        designation_price = res.data.promotion_designation_price;
                    if (res.code == 200) {
                        layer.close(loadingFlag);
                        $('#goods_release').show();
                        $('.release_online').html('');
                        off_choose.text(msg.cat_name).css('color','#333');
                        off_choose.attr('data-id',msg.cat_id);
                        off_tit.val(msg.goods_name);
                        off_introduce.val(msg.goods_brief);
                        off_desc.val(msg.goods_desc);
                        // 商品图片
                        var off_img = msg.imgs;
                        var path = msg.path;
                        function storeImg(){
                            for(var i=0; i<off_img.length; i++) {
                                var url = path + off_img[i].img_original;
                                $('#off_detail_goods_file').before('<div class="pic"><input type="hidden" value="'+ off_img[i].img_original +'" /><img src="'+ url +'" alt="" /><p class="close"></p></div>');
                            }
                        }
                        // 删除商品图片
                        $(document).ready(function(){
                            storeImg();
                            $('.pic .close').live('click',function(){
                                $(this).parent().remove();
                            })
                        })
                        off_shop_price.val(msg.shop_price);
                        off_market_price.val(msg.market_price);
                        // 优惠
                        if (msg.promotion_method == 3) {
                            off_pro_info.html('<span class="promotion_txt">指定店铺</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ msg.promotion_designation_price +'</span>\
                            <span style="margin-left:20px" class="promotion_txt">全网通用</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ msg.promotion_universal_price +'</span>').css('color','#333');
                            off_pro_info.attr('data-id','3');
                            off_pro_info.attr('data-designation',msg.promotion_designation_price);
                            off_pro_info.attr('data-universal',msg.promotion_universal_price);
                        }
                        // 优惠弹框
                        promotionBox(promotion_method,universal_price,designation_price);
                    } else {
                        return layer.msg(res.msg);
                    }
                },
                error: function(){
                    layer.open({content:'服务器连接出错，请重试'});
                }
            })
        }
        
    } else {
        // 自定义标签
        var resArr = new Array(),
            newArr = new Array(),
            priceArr = new Array(),
            countArr = new Array(),
            save_price = new Array(),
            save_count = new Array(),
            save_title = new Array();
        $.ajax({
            type: 'POST',
            url: '/admin/store/goods_info',
            data: goods_data,
            dataType: 'json',
            async: true,
            success: function(res){
                console.log(res)
                var msg = res.data,
                    promotion_method = res.data.promotion_method,
                    universal_price = res.data.promotion_universal_price,
                    designation_price = res.data.promotion_designation_price;
                if (res.code == 200) {
                    var data = msg.product; // 价格、库存
                    attrArr = msg.attr; // 自定义标签
                    if (msg.attr){
                        $('.price_tag').show();
                        $('.online_inventory,.online_price').hide();

                        // 自定义属性有值
                        if ($('#setting_spec .spec_content').children().length == 0) {
                            var attr = msg.attr;
                            console.log(msg)
                            $.each(attr, function (id, value) {
                                $('#setting_spec .spec_content').append('<div class="spec_item">\
                                    <input class="spec_tit" type="text" placeholder="自定义标题" value="'+ value.attr_name +'">\
                                    <div class="tag_main">\
                                        <button>添加</button>\
                                    </div>\
                                    <span class="close">x</span>\
                                </div');
                                $.each(value.attr_value,function(num,val){
                                    $('#setting_spec .spec_item:eq('+id+')').find('button').before('<div class="spec_tag_box"><span class="spec_tag" data-id="'+ val.goods_attr_id +'">'+ val.attr_value +'</span></div>')
                                })
                            });
                        }
                        // 价格、库存有值
                        $.each(msg.product,function(id,value){
                            save_price.push(value.product_price);
                            save_count.push(value.product_number);
                            save_title.push(value.product_name);

                            oldPrice.push(value.product_price);
                            oldCount.push(value.product_number);
                            oldTitle.push(value.product_name);
                        })
                        var tag_item = $('.spec_item'),
                            arrTit = new Array(),
                            arrCon = new Array();
                        // 属性标题
                        tag_item.each(function(){
                            var val = $(this).find('.spec_tit').val();
                            arrTit.push(val);
                        })
                        // 属性内容

                        newArr = [];
                        tag_item.each(function(){
                            var tag_box = $(this).find(".spec_tag_box");
                            var subArr = new Array();
                            tag_box.each(function(){
                                var txt = $(this).find('.spec_tag').text(),
                                    attr_id = $(this).find('.spec_tag').attr('goods-attr-id');
                                arrCon.push(txt);
                                subArr.push(txt);
                            })
                            newArr.push(subArr);
                        })
                        computed(newArr);
                        // 添加自定义标签到html
                        var attr = '';
                        for(var i=0; i<$('.spec_tit').length; i++){
                            attr += '<div class="attr_list">\
                                <p class="attr_name">'+ arrTit[i] +'</p>\
                            </div>'
                        }
                        $('.attr_txt').html(attr);
                        // 添加标签名、价格、库存到html
                        var dataValue = '';
                        pro_id = [];
                        goods_attr = [];
                        for(var i=0; i<data.length; i++){
                            pro_id.push(data[i].product_id);
                            goods_attr.push(data[i].goods_attr);
                            dataValue += '<div class="data_value">\
                                <p class="pro_title">'+ data[i].product_name +'</p>\
                                <p class="pro_price">'+ data[i].product_price +'</p>\
                                <p class="pro_count">'+ data[i].product_number +'</p>\
                                <p class="pro_id">'+ data[i].product_id +'</p>\
                                <p class="pro_attr">'+ data[i].goods_attr +'</p>\
                            </div>'
                        }
                        $('.hidden_txt').html(dataValue);
                    }

                    // 点击
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
                                // 添加标签
                                var spec_child = $('#setting_spec .spec_content').children('.spec_item');
                                if (spec_child.length == 0) {
                                    $('#setting_spec .spec_content').append('<div class="spec_item">\
                                        <input class="spec_tit" type="text" placeholder="自定义标题">\
                                        <div class="tag_main">\
                                            <button>添加</button>\
                                        </div>\
                                        <span class="close">x</span>\
                                    </div');
                                }
                                $('#add_btn').on('click',function(){
                                    $('#setting_spec .spec_content').append('<div class="spec_item">\
                                        <input class="spec_tit" type="text" placeholder="自定义标题">\
                                        <div class="tag_main">\
                                            <button>添加</button>\
                                        </div>\
                                        <span class="close">x</span>\
                                    </div');
                                })
                                num = $('.spec_tag_box .spec_tag').length;
                            },
                            yes: function(index){
                                var tag_item = $('.spec_item'),
                                    arrTit = new Array(),
                                    arrCon = new Array();
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
                                    var tag_box = $(this).find(".spec_tag_box");
                                    var subArr = new Array();
                                    tag_box.each(function(){
                                        var txt = $(this).find('.spec_tag').text();
                                        arrCon.push(txt)
                                        subArr.push(txt)
                                    })
                                    newArr.push(subArr);
                                })
                                for(var i=0; i<arrCon.length; i++) {
                                    if (arrCon[i] == '') {
                                        return layer.msg('属性内容不能为空');
                                    }
                                }
                                computed(newArr);
                                newTitle = resArr;
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
                        var str = '',
                            priceArr = new Array(),
                            countArr = new Array();
                        var price,count;
                        layer.open({
                            type: '1',
                            title: '',
                            skin: 'price_box',
                            area: ['480px','617px'],
                            content: $('#price'),
                            closeBtn: 0,
                            btn: '完成',
                            success: function(){
                                console.log(save_title);
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
                                priceArr = [];
                                $('.price_item .price_inp').each(function(){
                                    return priceArr.push($(this).val());
                                })
                                countArr = [];
                                $('.price_item .count_inp').each(function(){
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
                                console.log(resArr);

                                newPrice = priceArr;
                                newCount = countArr;
                                // 添加标签名、价格、库存到html
                                var dataValue = '';
                                console.log(equar(oldTitle,newTitle));
                                if (newTitle.length != 0) {
                                    if (equar(oldTitle,newTitle)) {
                                        for(var i=0; i<resArr.length; i++) {
                                            dataValue += '<div class="data_value">\
                                                <p class="pro_title">' + save_title[i] + '</p>\
                                                <p class="pro_price">' + save_price[i] + '</p>\
                                                <p class="pro_count">' + save_count[i] + '</p>\
                                                <p class="pro_id">' + pro_id[i] + '</p>\
                                                <p class="pro_attr">' + goods_attr[i] + '</p>\
                                            </div>'
                                        }
                                    } else {
                                        for(var i=0; i<resArr.length; i++){
                                            dataValue += '<div class="data_value">\
                                                <p class="pro_title">'+ save_title[i] +'</p>\
                                                <p class="pro_price">'+ save_price[i] +'</p>\
                                                <p class="pro_count">'+ save_count[i] +'</p>\
                                                <p class="pro_id"></p>\
                                                <p class="pro_attr"></p>\
                                            </div>'
                                        }
                                    }
                                } else {
                                    for(var i=0; i<resArr.length; i++){
                                        dataValue += '<div class="data_value">\
                                            <p class="pro_title">'+ save_title[i] +'</p>\
                                            <p class="pro_price">'+ save_price[i] +'</p>\
                                            <p class="pro_count">'+ save_count[i] +'</p>\
                                            <p class="pro_id">' + pro_id[i] + '</p>\
                                            <p class="pro_attr">' + goods_attr[i] + '</p>\
                                        </div>'
                                    }
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
                                $('.spec_tag').each(function(){
                                    $(this).removeAttr('data-id');
                                })
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
                                $('.spec_tag').each(function(){
                                    $(this).removeAttr('data-id');
                                })
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
                        $('.spec_tag').each(function(){
                            $(this).removeAttr('data-id');
                        })
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
                    // 保存两位小数
                    function toDecimal(x) { 
                        var f = parseFloat(x); 
                        if (isNaN(f)) { 
                            return; 
                        } 
                        f = Math.round(x*100)/100; 
                        return f; 
                    }
                    layer.close(loadingFlag);
                    $('#goods_release').show();
                    $('.release_offline').html('');
                    on_tit.val(msg.goods_name);
                    if (msg.goods_brief == null || msg.goods_brief == undefined) {
                        on_detail_textarea.val('');
                    } else {
                        on_detail_textarea.val(msg.goods_brief);
                    }
                    on_category.text(msg.cat_name).css('color','#333');
                    on_category.attr('data-id',msg.cat_id);
                    on_weight.val(toDecimal(msg.goods_weight));
                    on_inventory.val(msg.goods_number);
                    on_price.val(msg.shop_price);
                    on_freight.val(msg.fare);
                    // 商品图片
                    var on_tit_img = msg.gallery;
                    var on_detail_img = msg.goods_img;
                    var path = msg.img_url;
                    function onlinTitImg(){
                        for(var i=0; i<on_tit_img.length; i++) {
                            var url = path + on_tit_img[i].img_url;
                            $('#tit_goods_file').before('<div class="pic"><input type="hidden" value="'+ on_tit_img[i].img_url +'" /><img src="'+ url +'" alt="" /><p class="close"></p></div>');
                        }
                    }
                    function onlinDetailImg(){
                        for(var i=0; i<on_detail_img.length; i++) {
                            var url = path + on_detail_img[i];
                            $('#on_detail_goods_file').before('<div class="pic"><input type="hidden" value="'+ on_detail_img[i] +'" /><img src="'+ url +'" alt="" /><p class="close"></p></div>');
                        }
                    }
                    // 删除商品图片
                    $(document).ready(function(){
                        onlinTitImg();
                        onlinDetailImg();
                        $('.pic .close').live('click',function(){
                            $(this).parent().remove();
                        })
                    })
                    
                    off_shop_price.val(msg.shop_price);
                    off_market_price.val(msg.market_price);
                    // 优惠
                    if (msg.promotion_method == 3) {
                        off_pro_info.html('<span class="promotion_txt">指定店铺</span>¥\
                        <span class="promotion_price promotion_designation_price">'+ msg.promotion_designation_price +'</span>\
                        <span style="margin-left:20px" class="promotion_txt">全网通用</span>¥\
                        <span class="promotion_price promotion_designation_price">'+ msg.promotion_universal_price +'</span>').css('color','#333');
                        off_pro_info.attr('data-id','3');
                        off_pro_info.attr('data-designation',msg.promotion_designation_price);
                        off_pro_info.attr('data-universal',msg.promotion_universal_price);
                    }
                    promotionBox(promotion_method,universal_price,designation_price);
                } else {
                    return layer.msg(res.msg);
                }
            },
            error: function(){
                layer.open({content:'服务器连接出错，请重试'});
            }
        })
    }
}

// 确认编辑
function edit_goods(){
    // 获取优惠方式
    var promotion_method = $('.promotion_info').attr('data-id'),
        promotion_designation = $('.promotion_info').attr('data-designation'),
        promotion_universal = $('.promotion_info').attr('data-universal'),
        address = $('.goods_address').text();
        goods_address = $.trim(address),
        on_goods_img = [],
        on_goods_detal_img = [],
        off_goods_img = [];
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

        var eqTit = equar(oldTitle,newTitle),
            eqPrice = equar(oldPrice,newPrice),
            eqCount = equar(oldCount,newCount);
        if (newTitle == '' && newPrice == '' && newCount == '') {
            attr_state = 0;
        } else if (newTitle == '' && eqPrice && eqCount) {
            attr_state = 0;
        } else {
            if (oldTitle.length == newTitle.length) {
                if (eqTit) {
                    if (eqPrice && eqCount) {
                        attr_state = 0;
                    } else {
                        attr_state = 1;
                    }
                }

            } else if (newTitle.length == 0) {
                if (eqPrice && eqCount) {
                    attr_state = 0;
                } else {
                    attr_state = 1;
                }
            } else {
                attr_state = 2;
            }
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
            attr_state: attr_state, // 是否修改
            product: dataArray, // 自定义属性名
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
            goods_id: goods_id
        };
        console.log(online_info)

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
                type: 'POST',
                url: '/admin/store/edit_goods_info',
                data: online_info,
                dataType: 'json',
                async: true,
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
                        off_pro_info.removeAttr('data-id');
                        off_pro_info.removeAttr('data-designation');
                        off_pro_info.removeAttr('data-universal');
                        history.go(-1);
                    } else {
                        return layer.msg(res.msg);
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

        newDate = $('.date_choose').text();
        // 获取酒店日期、价格
        $('.date_time_price .item').each(function(){
            one_day += $(this).find('.item_time').text() + ',';
            day_price += $(this).find('.item_price').text() + ',';
        })
        
        // 美食
        var offline_info = {
            goods_id: goods_id,
            cat_id: off_choose.attr('data-id'),
            goods_name: off_tit.val(),
            goods_brief: off_introduce.val(),
            goods_desc: off_desc.val(),
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
            goods_id: goods_id,
            cat_id: off_choose.attr('data-id'),
            goods_name: off_tit.val(),
            goods_brief: off_introduce.val(),
            goods_desc: off_desc.val(),
            goods_img: off_goods_img,
            location: goods_address,
            start_time: $('.start_date').val(),
            end_time: $('.end_date').val(),
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
            if (off_choose.attr('data-id') == '' || off_choose.attr('data-id') == null) {
                return layer.msg('请选择类别');
            } else if (off_tit.val() == '' || off_tit.val() == null) {
                return layer.msg('请输入标题(套餐或者服务的名称)');
            } else if (off_introduce.val() == '' || off_introduce.val() == null) {
                return layer.msg('商品介绍(简要描述套餐内容或者商品卖点)');
            } else if (off_desc.val() == '' || off_desc.val() == null) {
                return layer.msg('具体描述套餐或服务信息');
            } else if (off_goods_img == '' || off_goods_img == null) {
                return layer.msg('请添加商品图片');
            } else if (off_shop_price.val() == '' || off_shop_price.val() == null || off_shop_price.val() == 0) {
                return layer.msg('请输入商品价格');
            } else if (oldDate != newDate) {
                oldDate = newDate;
                return layer.msg('请按日期定价');
            } else if (off_market_price.val() == '' || off_market_price.val() == null) {
                return layer.msg('请输入商品原价');
            } else {
                var url = '/admin/UnderLine/editHotel';
                ajaxInfo(url,offline_hotel_info);
            }
        } else {
            if (off_choose.attr('data-id') == '' || off_choose.attr('data-id') == null) {
                return layer.msg('请选择类别');
            } else if (off_tit.val() == '' || off_tit.val() == null) {
                return layer.msg('请输入标题(套餐或者服务的名称)');
            } else if (off_introduce.val() == '' || off_introduce.val() == null) {
                return layer.msg('商品介绍(简要描述套餐内容或者商品卖点)');
            } else if (off_desc.val() == '' || off_desc.val() == null) {
                return layer.msg('具体描述套餐或服务信息');
            } else if (off_goods_img == '' || off_goods_img == null) {
                return layer.msg('请添加商品图片');
            } else if (off_shop_price.val() == '' || off_shop_price.val() == null) {
                return layer.msg('请输入商品价格');
            } else if (off_market_price.val() == '' || off_market_price.val() == null) {
                return layer.msg('请输入商品原价');
            } else {
                var url = '/admin/UnderLine/editProduct';
                ajaxInfo(url,offline_info);
            }
        }
        //请求网络
        function ajaxInfo(url,data){
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                async: true,
                success: function(res){
                    if (res.code == 200) {
                        // console.log(res)
                        layer.msg(res.msg);
                        // 清空
                        $('.online_choose,.offline_choose').text('请选择').css('color','#999');
                        $('.date_choose').text('选择日期').css('color','#999');
                        off_goods_img = [];
                        dates = '';
                        off_tit.val('');
                        off_introduce.val('');
                        off_desc.val('');
                        $('.detail_pic .pic').remove();
                        off_shop_price.val('');
                        off_market_price.val('');
                        off_pro_info.removeAttr('data-id');
                        off_pro_info.removeAttr('data-designation');
                        off_pro_info.removeAttr('data-universal');
                        off_pro_info.html('无');
                        history.go(-1);
                    } else {
                        return layer.msg(res.msg);
                    }
                },
                error: function(){
                    layer.open({content:'服务器连接出错，请重试'});
                }
            })
        }
    }
}
// 优惠弹框
function promotionBox(promotion_method,universal_price,designation_price){
    off_pro_info.on('click',function(){
        if (promotion_method == 3) {
            $('.promotion_designation input[name="checkbox"]').prop('checked',true);
            $('.promotion_universal input[name="checkbox"]').prop('checked',true);
            $('.promotion_designation input[type="text"]').val(designation_price);
            $('.promotion_universal input[type="text"]').val(universal_price);
        }
    })
}

$(function(){
    // 商品信息
    goodsInfo();
    // 编辑提交
    $('#edit_btn').on('click',function(){
        edit_goods();
    })
    
})