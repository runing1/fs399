var dataobj = {user_id: localStorage.getItem('userId')};
var type = localStorage.getItem('type');
var loadingFlag;
// 编辑店铺
var store_logo = $('.logo_content input[type="hidden"]'),
    store_name = $('.store_name_input'),
    store_type = $('.apply_attribute_txt'),
    store_category = $('.apply_category_txt'),
    store_region = $('.apply_region_txt'),
    store_street = $('.apply_street_txt'),
    store_address = $('.apply_address_input'),
    store_location = $('.apply_location_txt'),
    store_qq = $('.apply_QQ_txt'),
    store_wx = $('.apply_wechat_txt'),
    store_wechat_code = '',
    store_phone = '',
    store_realname = $('.apply_real_name_txt'),
    store_tag = $('.tag_item'),
    store_msg = $('.msg_textarea textarea');

// 未编辑店铺
function unEditInfo(){
    $.ajax({
        type: 'post',
        url: '/admin/store/store_info',
        dataType: 'json',
        success: function(res){
            if (res.code == 200) {
                $('body').show();
                var msg = res.data;
                type = msg.type;
                store_wechat_code = msg.wechat_code_img;
                store_phone = msg.mobile;
                store_realname = msg.realname;
                var subMobile = store_phone.substr(0, 3) + '****' + store_phone.substring(7, 11);
                var subName = '*' + store_realname.substr(1);

                $('.logo_content img').show();
                $('.logo_content span').hide();
                $('.logo_content img').attr('src',msg.img_url+msg.logo_image); // logo
                $('.logo_content input[type="hidden"]').val(msg.logo_image);
                $('.store_name_input').val(msg.store_name); // store_name
                // 省市区、街道id
                $('.apply_region_txt').attr('province_id',msg.province_id);
                $('.apply_region_txt').attr('city_id',msg.city_id);
                $('.apply_region_txt').attr('district_id',msg.district_id);
                $('.apply_street_txt').attr('street_id',msg.street_id);

                $('.apply_region_txt').text(msg.province+msg.city+msg.district); // 所在地区
                $('.apply_street_txt').text(msg.street); // 选择街道
                $('.apply_QQ_txt').text(msg.qq); // QQ
                $('.apply_wechat_txt').text(msg.wx_number);  // 店家微信
                $('#choose_code_btn img').attr('src',msg.img_url+msg.wechat_code_img); // 二维码
                $('.apply_mobile_phone_txt').text(subMobile); // 联系方式
                $('.apply_real_name_txt').text(subName); // 真实姓名

                if (msg.type == 'offlin') {
                    $('.apply_attribute_txt').text('线下商家'); // 商家属性
                    $('.apply_attribute_txt').attr('data-type',msg.type);
                    $('.apply_category_txt').text(msg.category); // 店铺类型
                    $('.apply_category_txt').attr('data-id',msg.cat_id);

                    // 地图定位
                    $('.apply_location_txt').on('click',function(){
                        if ($('.apply_address_input').val() == '') {
                            return layer.msg('请填写详细地址');
                        } else {
                            layer.open({
                                type: '1',
                                title: '定位',
                                area: ['600px','480px'],
                                content: $('#store_map'),
                                btn: '确定',
                                success: function(){
                                    var map = new AMap.Map('container', {
                                        zoom: 14,
                                        scrollWheel: true
                                    });
                                    
                                    // 根据 地址 => 坐标
                                    var geocoder,marker;
                                    function geoCode() {
                                        if(!geocoder){
                                            geocoder = new AMap.Geocoder({
                                                city: "全国", //城市设为北京，默认：“全国”
                                            });
                                        }
                                        var address_info = $('.apply_region_txt').text() + $('.apply_street_txt').text() + $('.apply_address_input').val();
                                        geocoder.getLocation(address_info, function(status, result) {
                                            if (status === 'complete'&&result.geocodes.length) {
                                                var lnglat = result.geocodes[0].location;
                                                if(!marker){
                                                    marker = new AMap.Marker();
                                                    map.add(marker);
                                                }
                                                marker.setPosition(lnglat);
                                                map.setFitView(marker);
                                            }else{
                                                log.error('根据地址查询位置失败');
                                            }
                                        });
                                    }
                                
                                    geoCode();

                                    AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
                                        // 拖拽模式
                                        var positionPicker = new PositionPicker({
                                            mode: 'dragMarker',
                                            map: map
                                        });
                            
                                        positionPicker.on('success', function(positionResult) {
                                            var lat = positionResult.position.lat;
                                            var lng = positionResult.position.lng;
                                            $('#lng').text(positionResult.position);
                                            $('#address').html(positionResult.address);
                                            $('.apply_location_txt').attr('lat',lat);
                                            $('.apply_location_txt').attr('lng',lng);
                                            localStorage.setItem('lat',lat);
                                            localStorage.setItem('lng',lng);
                                            
                                            map.remove(marker);
                                        });
                                        
                                        positionPicker.on('fail', function(positionResult) {
                                            // console.log(positionResult)
                                            $('#lng').text('');
                                            $('#address').html('');
                                        });
                                        positionPicker.start();
                                        // map.panBy(0, 1);
                                    });
                                },
                                yes: function(index){
                                    $('.apply_location_txt').text('已定位');
                                    layer.close(index);
                                }
                            })
                        }
                    })

                    // 店铺优势标签
                    var num = 0;
                    var count;
                    // 添加标签
                    function addTag(){
                        $('.tag_add_btn').on('click',function(){
                            var val = $.trim($(this).parent().find('input[type="text"]').val());
                            if ($(this).parent().find('input[type="text"]').val() != '') {
                                if ($('.tag_content').children().length < 5) {
                                    $('.tag_content').append('<span class="tag_item">\
                                        <em class="tag_txt">'+ val +'</em>\
                                        <em class="tag_close">x</em>\
                                    </span>');
                                    count = $('.tag_content').children().length;
                                    num++;
                                    if (num > 5) {
                                        num = 5;
                                    }
                                    $('.tag_number em').text(num);
                                    $(this).parent().find('input[type="text"]').val('');
                                } else {
                                    $(this).parent().find('input[type="text"]').val('');
                                    return layer.msg('最多这能添加5个自定义标签');
                                }
                            } else {
                                return layer.msg('请输入关键字');
                            }
                        })
                    }
                    // 移除标签
                    $(document).ready(function(){
                        addTag();
                        $('.tag_close').live('click',function(){
                            $(this).parent().remove();
                            if (count != null || count != undefined || count != 0) {
                                count--;
                                if (count <= 0) {
                                    count = 0
                                }
                                $('.tag_number em').text(count);
                            }
                        })
                    })

                    // 输入店铺资料
                    $('.msg_textarea textarea').bind('input propertychange', function(){  
                        var length = $(this).val().length;
                        $('.area_number em').text(length);
                    });
                } else {
                    $('.apply_attribute_txt').text('线上商家'); // 商家属性
                    $('.apply_category,.apply_address,.apply_location,.main_personal_info,.main_store_img,.main_store_content').remove();
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
}
// 已编辑店铺
function editInfo(){
    $.ajax({
        type: 'post',
        url: '/admin/store/store_info',
        data: dataobj,
        dataType: 'json',
        success: function(res){
            if (res.code == 200) {
                $('body').show();
                var msg = res.data;
                type = msg.type;
                store_wechat_code = msg.wechat_code_img;
                store_phone = msg.mobile;
                store_realname = msg.realname;
                var subMobile = store_phone.substr(0, 3) + '****' + store_phone.substring(7, 11);
                var subName = '*' + store_realname.substr(1);

                $('.logo_content img').show();
                $('.logo_content span').hide();
                $('.logo_content img').attr('src',msg.img_url+msg.logo_image); // logo
                $('.logo_content input[type="hidden"]').val(msg.logo_image);
                $('.store_name_input').val(msg.store_name).attr('disabled','disabled'); // store_name
                
                // 省市区、街道id
                $('.apply_region_txt').attr('province_id',msg.province_id);
                $('.apply_region_txt').attr('city_id',msg.city_id);
                $('.apply_region_txt').attr('district_id',msg.district_id);
                $('.apply_street_txt').attr('street_id',msg.street_id);

                $('.apply_region_txt').text(msg.province+msg.city+msg.district); // 所在地区
                $('.apply_street_txt').text(msg.street); // 选择街道
                $('.apply_address_input').val(msg.house_number); // 具体地址
                $('.apply_QQ_txt').text(msg.qq); // QQ
                $('.apply_wechat_txt').text(msg.wx_number);  // 店家微信
                $('#choose_code_btn img').attr('src',msg.img_url+msg.wechat_code_img); // 二维码
                $('.apply_mobile_phone_txt').text(subMobile); // 联系方式
                $('.apply_real_name_txt').text(subName); // 真实姓名

                if (res.data.type == 'offlin') {
                    $('.apply_attribute_txt').text('线下商家'); // 商家属性
                    $('.apply_attribute_txt').attr('data-type',msg.type);
                    $('.apply_category_txt').text(msg.category); // 店铺类型
                    $('.apply_category_txt').attr('data-id',msg.cat_id);

                    $('.apply_location_txt').text('已定位'); 
                    $('.apply_location_txt').attr('lat',msg.latitude); // lat
                    $('.apply_location_txt').attr('lng',msg.longitude); // lng
                    $('.msg_textarea textarea').val(msg.store_desc); // 店铺资料

                    // 地图定位
                    $('.apply_location_txt').on('click',function(){
                        if ($('.apply_address_input').val() == '') {
                            return layer.msg('请填写详细地址');
                        } else {
                            layer.open({
                                type: '1',
                                title: '定位',
                                area: ['600px','480px'],
                                content: $('#store_map'),
                                btn: '确定',
                                success: function(){
                                    var map = new AMap.Map('container', {
                                        zoom: 14,
                                        scrollWheel: true
                                    });
                                    
                                    // 根据 地址 => 坐标
                                    var geocoder,marker;
                                    function geoCode() {
                                        if(!geocoder){
                                            geocoder = new AMap.Geocoder({
                                                city: "全国", //城市设为北京，默认：“全国”
                                            });
                                        }
                                        var address_info = $('.apply_region_txt').text() + $('.apply_street_txt').text() + $('.apply_address_input').val();
                                        // console.log(address_info)
                                        geocoder.getLocation(address_info, function(status, result) {
                                            if (status === 'complete'&&result.geocodes.length) {
                                                var lnglat = result.geocodes[0].location
                                                // console.log(lnglat);
                                                if(!marker){
                                                    marker = new AMap.Marker();
                                                    map.add(marker);
                                                }
                                                marker.setPosition(lnglat);
                                                map.setFitView(marker);
                                            }else{
                                                log.error('根据地址查询位置失败');
                                            }
                                        });
                                    }
                                
                                    geoCode();

                                    AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
                                        // 拖拽模式
                                        var positionPicker = new PositionPicker({
                                            mode: 'dragMarker',
                                            map: map
                                        });
                            
                                        positionPicker.on('success', function(positionResult) {
                                            // console.log(positionResult);
                                            var lat = positionResult.position.lat;
                                            var lng = positionResult.position.lng;
                                            $('#lng').text(positionResult.position);
                                            $('#address').html(positionResult.address);
                                            $('.apply_location_txt').attr('lat',lat);
                                            $('.apply_location_txt').attr('lng',lng);
                                            localStorage.setItem('lat',lat);
                                            localStorage.setItem('lng',lng);
                                            map.remove(marker);
                                        });
                                        
                                        positionPicker.on('fail', function(positionResult) {
                                            // console.log(positionResult)
                                            $('#lng').text('');
                                            $('#address').html('');
                                        });
                                        positionPicker.start();
                                        // map.panBy(0, 1);
                                    });
                                },
                                yes: function(index){
                                    $('.apply_location_txt').text('已定位');
                                    layer.close(index);
                                }
                            })
                        }
                    })

                    // 店铺优势标签
                    var num = msg.store_advantage.length;
                    var count;

                    for(var i=0; i<num; i++) {
                        $('.tag_content').append('<span class="tag_item">\
                            <em class="tag_txt">'+ msg.store_advantage[i].tag +'</em>\
                            <em class="tag_close">x</em>\
                        </span>');
                    }       
                    count = $('.tag_content').children().length;              
                    // 添加标签
                    function addTag(){
                        $('.tag_add_btn').on('click',function(){
                            var val = $.trim($(this).parent().find('input[type="text"]').val());
                            if ($(this).parent().find('input[type="text"]').val() == '') {
                                return layer.msg('请输入关键字');
                            } else {
                                if ($('.tag_content').children().length < 5) {
                                    $('.tag_content').append('<span class="tag_item">\
                                        <em class="tag_txt">'+ val +'</em>\
                                        <em class="tag_close">x</em>\
                                    </span>');
                                    count = $('.tag_content').children().length;
                                    num++;
                                    if (num > 5) {
                                        num = 5;
                                    }
                                    $('.tag_number em').text(num);
                                    $(this).parent().find('input[type="text"]').val('');
                                } else {
                                    $(this).parent().find('input[type="text"]').val('');
                                    return layer.msg('最多这能添加5个自定义标签');
                                }
                            }
                        })
                    }
                    $('.tag_number em').text(num);
                    // 移除标签
                    $(document).ready(function(){
                        addTag();
                        $('.tag_close').live('click',function(){
                            $(this).parent().remove();
                            if (count != null || count != undefined || count != 0) {
                                count--;
                                num--;
                                if (count <= 0) {
                                    count = 0
                                    num = 0;
                                }
                                $('.tag_number em').text(count);
                            }
                        })
                    })
                    var store_img = msg.store_img;
                    var img_url = msg.img_url;
                    // 店铺图片
                    function storeImg(){
                        for(var i=0; i<store_img.length; i++) {
                            var url = img_url + $.trim(msg.store_img[i].image);
                            $('#store_goods_file').before('<div class="pic"><input type="hidden" value="'+ msg.store_img[i].image +'" /><img src="'+ url +'" alt="" /><p class="close"></p></div>');
                        }
                    }

                    // 删除店铺图片
                    $(document).ready(function(){
                        storeImg();
                        $('.pic .close').live('click',function(){
                            $(this).parent().remove();
                        })
                    })
                    
                    $('.area_number em').text(msg.store_desc.length);
                    // 输入店铺资料
                    $('.msg_textarea textarea').bind('input propertychange', function(){  
                        var length = $(this).val().length;
                        $('.area_number em').text(length);
                    });
                } else {
                    $('.apply_attribute_txt').text('线上商家'); // 商家属性
                    $('.apply_category,.apply_address,.apply_location,.main_personal_info,.main_store_img,.main_store_content').remove();
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
}
// 店铺编辑
function editStoreInfo(){
    var tag_arr = []; // 标签
    var img_store_array = []; // 店铺图片
    // 获取tag
    var leng = $('.tag_content').children().length;
    if (leng != 0) {
        $('.tag_content .tag_item').each(function(){
            tag_arr.push($(this).find('.tag_txt').text());
        })
    }
    // 获取店铺图片src 
    $('.store_pic .pic').each(function(){
        img_store_array.push($(this).find('input[type="hidden"]').val());
    })

    
    if (type == 'offlin') {
        var store_info = {
            logo_image: store_logo.val(),
            store_name: store_name.val(),
            type: store_type.attr('data-type'),
            cat_id: store_category.attr('data-id'),
            province_id: store_region.attr('province_id'),
            city_id: store_region.attr('city_id'),
            district_id: store_region.attr('district_id'),
            street_id: store_street.attr('street_id'),
            house_number: store_address.val(), // 具体地址
            longitude: store_location.attr('lng'), // 经度
            latitude: store_location.attr('lat'), // 纬度
            qq: store_qq.text(),
            wx_number: store_wx.text(),
            wechat_code_img: store_wechat_code,
            mobile: store_phone,
            realname: store_realname,
            tag: tag_arr,
            images: img_store_array,
            store_desc: store_msg.val(),
            user_id: localStorage.getItem('userId') 
        }

        if (store_name.val() == '' || store_name.val() == null) {
            return layer.msg('请输入店铺名称');
        } else if (store_address.val() == '' || store_address.val() == null) {
            return layer.msg('请输入具体地址');
        } else if (store_location.attr('lng') == '' || store_location.attr('lng') == '') {
            return layer.msg('请确认地图定位');
        } else if (img_store_array == '' || img_store_array == null) {
            return layer.msg('请上传店铺图片');
        } else if (store_msg.val() == '' || store_msg.val() == null) {
            return layer.msg('请输入您的店铺资料');
        } else {
            ajaxInfo(store_info);
        }
    } else {
        var store_info = {
            logo_image: store_logo.val(),
            store_name: store_name.val(),
            type: store_type.attr('data-type'),
            cat_id: store_category.attr('data-id'),
            province_id: store_region.attr('province_id'),
            city_id: store_region.attr('city_id'),
            district_id: store_region.attr('district_id'),
            street_id: store_street.attr('street_id'),
            qq: store_qq.text(),
            wx_number: store_wx.text(),
            wechat_code_img: store_wechat_code,
            mobile: store_phone,
            realname: store_realname,
            user_id: localStorage.getItem('userId') 
        }
        ajaxInfo(store_info);
    }

    function ajaxInfo(store_info){
        $.ajax({
            type: 'post',
            url: '/admin/store/edit_store_info',
            data: store_info,
            dataType: 'json',
            success: function(res){
                tag_arr = [];
                img_store_array = []; 
                $('.tag_content .tag_item').remove();
                $('.store_pic .pic').remove();
                layer.msg(res.msg);
                history.go(-1);
                // window.location.href = 'storeManage';
            },
            error: function(){
                layer.open({content:'服务器连接出错，请重试'});
            }
        })
    }
}


$(function(){
    loadingFlag = layer.load();
    $.ajax({
        type: 'post',
        url: '/admin/store/store_type',
        dataType: 'json',
        success: function(res){
            if (res.code == 200) {
                layer.close(loadingFlag);
                $('#apply_store').show();
                if (res.data.type == 'offlin') {
                    if (res.data.is_all == 0) { // 未编辑过
                        unEditInfo();  
                    } else { // 已编辑过
                        editInfo();
                    }
                } else {
                    if (res.data.is_all == 0) { // 未编辑过
                        unEditInfo();  
                    } else { // 已编辑过
                        editInfo();
                    }
                }
            }
        },
        error: function(res){
            console.log(res)
            console.log(123);
            layer.open({
                title: '提示',
                time: 2000,
                content: '抱歉，请刷新重试！'
            })
        }
    })
    // 返回
    $('.edit_back').on('click',function(){
        history.go(-1);
    })
    // 确认
    $('.submit_btn').on('click',function(){
        editStoreInfo();
    })
})