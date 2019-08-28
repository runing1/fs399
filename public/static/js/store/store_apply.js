// 获取入驻属性
function storeAttribute(){
    $('.apply_attribute_content').on('click',function(){
        layer.open({
            type: '1',
            title: ['入驻属性','font-size:14px;font-weight:normal;color:#666;'],
            area: ['380px','134px'],
            content: $('#apply_attribute'),
            success: function(index,layero){
                $('#apply_attribute .attribute_item').on('click',function(){
                    $(this).find('span').addClass('selected');
                    $(this).siblings().find('span').removeClass('selected');
                    var msg = $(this).find('p').text();
                    var type = $(this).attr('type');
                    $('.apply_attribute .apply_attribute_txt').text(msg).css('color','#333');
                    $('.apply_attribute .apply_attribute_txt').attr('type',type); // 属性
                    layer.close(layero);
                    // console.log(index);
                    // 店铺类别
                    if (msg == '线下商家'){
                        $('.apply_category').slideDown(200);
                    } else {
                        $('.apply_category').slideUp(200);
                    }
                })
            }
        })
    })
}

// 获取线下店铺的类别
function storeCategory(){
    $('.apply_category_content').on('click',function(){
        $.ajax({
            type: 'post',
            url: '/admin/UnderLineIndex/category',
            data: {'token':'d8b40af2832da68f2dd767bc116cb27c5d1c73262ac5b7.03801007'},
            dataType: 'json',
            success: function(res){
                if (res.code == 200) {
                    var msg = res.data;
                    for (var i=0; i< msg.length; i++){
                        $('.one_bar .one_bar_ul').append('<li class="one_bar_li"><p class="one_bar_tit" data-id="'+ msg[i].cat_id +'">'+ msg[i].cat_name +'</p></li>');
                    }
                    layer.open({
                        type: '1',
                        title: ['店铺类别','font-size:14px;font-weight:normal;color:#666;'],
                        area: ['322px','314px'],
                        content: $('#apply_category'),
                        skin: 'category-class',
                        success: function(index,layero){
                            $(".one_bar_tit").click(function(){
                                var dataId = $(this).attr('data-id');
                                $('.apply_category .apply_category_txt').text($(this).text()).css('color','#333');
                                $('.apply_category .apply_category_txt').attr('data-id',dataId);
                                layer.close(layero);  // 关闭弹窗
                                // console.log(index);
                            })
                        }
                    })
                }
            },
            error: function(err){
                console.log(err);
            }
        })
    })
}

// 获取省、市、区
function cityPicker(){
    $('.apply_region_content').on('click',function(){
        layer.open({
            type: '1',
            title: ['选择省市区','font-size:14px;font-weight:normal;color:#666;'],
            area: ['380px','356px'],
            content: $('#sjld'),
            btn: '确认',
            success: function(){
                $("#sjld").sjld("#shenfen","#chengshi","#quyu");
                // 城市
                if($('#shenfen').find('p').text() == '请选择'){
                    $('#chengshi').find('.m_zlxg2').slideUp(200);
                    // 区、县
                    if($('#shenfen').find('p').text() == '请选择' && $('#chengshi').find('p').text() == '请选择'){
                        $('#quyu').find('.m_zlxg2').slideUp(200);
                        return;
                    }else{
                        $('#quyu').find('.m_zlxg2').slideDown(200);
                    }
                    return;
                }else{
                    $('#chengshi').find('.m_zlxg2').slideDown(200);
                }
            },
            yes: function(index){
                if ($('#chengshi').find('p').text() == '请选择') {
                    return layer.msg('请选择城市');
                } else if ($('#quyu').find('p').text() == '请选择') {
                    return layer.msg('请选择区域');
                } else {
                    var content = $('#shenfen').find('p').text() + "  " + $('#chengshi').find('p').text() + "  " + $('#quyu').find('p').text();
                    $('.apply_region_txt').text(content).css('color','#333');
                    layer.close(index);
                }
            }
        })
    })
}

// 获取街道
function streetName(){
    $('.apply_street_content').on('click',function(){
        var cityTxt = $('.apply_region_txt').text();
        var pid = $('.apply_region_txt').attr('district-id');
        $.ajax({
            type: 'post',
            url: '/admin/index/get_region',
            data: {'pid':pid},
            dataType: 'json',
            success: function(res){
                if (cityTxt == '请选择省市区') {
                    return layer.msg('请先选择省市区');
                }
                // console.log(res)
                if (res.code == 200) {
                    var msg = res.data;
                    for (var i=0; i< msg.length; i++){
                        $('.street_bar .street_bar_ul').append('<li class="street_bar_li"><p class="street_bar_tit" data-id="'+ msg[i].id +'">'+ msg[i].name +'</p></li>');
                    }
                    layer.open({
                        type: '1',
                        title: ['请选择街道','font-size:14px;font-weight:normal;color:#666;'],
                        area: ['322px','314px'],
                        content: $('#street'),
                        skin: 'street-class',
                        success: function(index,layero){
                            $(".street_bar_tit").click(function(){
                                var streetId = $(this).attr('data-id');
                                $('.apply_street_txt').text($(this).text()).css('color','#333');
                                $('.apply_street_txt').attr('street-id',streetId); // street-id
                                layer.close(layero);  // 关闭弹窗
                                // console.log(index);
                            })
                        }
                    })
                } else {
                    console.log(error);
                }
            },
            error: function(error){
                console.log(error);
            }
        })
    })
}

// 倒计时
var countdown = 60;
function settime(obj) {
    if (countdown == 0) {
        obj.prop('disabled', false);
        obj.val("重新发送");
        countdown = 60;
        return;
    } else {
        obj.prop('disabled', true);
        obj.val(countdown + "s");
        countdown--;
    }
    setTimeout(function () {
        settime(obj)
    }
    , 1000)
}

// 获取验证码
function validationCode(){
    $('.validation_code_btn').on('click', function () {
        var phone = $('.mobile_phone_input').val();
        var pattern = /^1[3-9]\d{9}$/; 
        if(!phone){
            return layer.msg('请输入手机号码');
        }else{
            if (!pattern.test(phone)){
                return layer.msg('请输入正确的手机号码');
            }
        }
        var dataobj = {mobile: phone, work: 1, role: 'PC'}
        _this = $(this);
        $.ajax({
            url: "/admin/Api/sendSms",
            type:'post',
            data: dataobj,
            dataType:'json',
            success: function (res) {
                console.log(res)
                if (res.status == 1) {
                    settime(_this);
                } else {
                    return layer.msg('res.msg');
                }
            },
            error: function(error){
                console.log(error);
            }
        });
    })
}

// 点击提交
function submitStore(){
    var logo_image = $('.logo_content input[type="hidden"]').val(), // logo
        store_name = $('.store_name_input').val(), // store_name
        type = $('.apply_attribute_txt').attr('type'), // 入驻属性
        region_txt = $('.apply_region_txt').text(), // 地区
        province_id = $('.apply_region_txt').attr('province-id'), // 省份id
        city_id = $('.apply_region_txt').attr('city-id'), // 城市id
        district_id = $('.apply_region_txt').attr('district-id'), // 区、县id
        street_txt = $('.apply_street_txt').text(), // 街道
        street_id = $('.apply_street_txt').attr('street-id'), // 街道id
        qq = $('.apply_QQ_input').val(), // QQ
        wx_number = $('.apply_wechat_input').val(),  // 店家微信
        wechat_code_img = $('.right_pic input[type="hidden"]').val(), // 二维码
        face_spot = 'finish', // face_spot
        realname = $('.real_name_input').val(), // 真实姓名
        id_card = $('.id_card_input').val(), // 身份证
        bankcard = $('.bank_card_input').val(), // 银行卡号
        mobile = $('.mobile_phone_input').val(), // 手机号
        code = $('.validation_code_input').val(), // 验证码
        card_img_front = $('.positive_img input[type="hidden"]').val(), // 身份证正面
        card_img_back = $('.reverse_img input[type="hidden"]').val(); // 身份证反面

    // 店铺类别
    if(type == 'offlin') {
        var cat_id = $('.apply_category_txt').attr('data-id'); 
    }

    var apply_info = {
        logo_image:logo_image,
        store_name:store_name,
        type:type,
        cat_id:cat_id,
        province_id:province_id,
        city_id:city_id,
        district_id:district_id,
        street_id:street_id,
        qq:qq,
        wx_number:wx_number,
        wechat_code_img:wechat_code_img,
        face_spot:face_spot,
        realname:realname,
        id_card:id_card,
        bankcard:bankcard,
        mobile:mobile,
        code:code,
        card_img_front:card_img_front,
        card_img_back:card_img_back,
        user_id:63 // 用户id
    };

    // 验证
    var nameRg=/^[a-zA-Z]|[\u4e00-\u9fa5]|[.]+$/;  // 姓名正则
    var cardRg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; // 身份证正则
    var bankRg = /(^\d{13}$)|(^\d{14}$)|(^\d{15}$)|(^\d{16}$)|(^\d{17}$)|(^\d{18}$)|(^\d{19}$)/;  // 银行卡正则
    
    if (logo_image == '' || logo_image == undefined) {
        return layer.msg('请选择头像LOGO');
    } else if (store_name == '' || store_name == undefined) {
        return layer.msg('请输入商家名称');
    } else if (type == '' || type == undefined) {
        return layer.msg('请选择商家属性');
    } else if (type == 'offline' && (store_category == '请选择店铺类别' || store_category == undefined)) {
        return layer.msg('请选择店铺类别');
    } else if (region_txt == '请选择省市区' || region_txt == undefined) {
        return layer.msg('请选择所在地区');
    } else if (street_txt == '请选择街道' || street_txt == undefined) {
        return layer.msg('请选择街道');
    } else if (qq == '' || qq == undefined) {
        return layer.msg('请输入QQ');
    } else if (wx_number == '' || wx_number == undefined) {
        return layer.msg('请输入微信号');
    } else if (wechat_code_img == '' || wechat_code_img == undefined) {
        return layer.msg('请选择微信二维码');
    } else if (realname == '' || realname == undefined) {
        return layer.msg('请输入真实名字');
    } else if (id_card == '' || id_card == undefined) {
        return layer.msg('请输入本人身份证号');
    } else if (bankcard == '' || bankcard == undefined) {
        return layer.msg('请输入本人绑定卡号');
    } else if (mobile == '' || mobile == undefined) {
        return layer.msg('请输入本人手机号码');
    } else if (code == '' || code == undefined) {
        return layer.msg('请输入验证码');
    } else if (card_img_front == '' || card_img_front == undefined) {
        return layer.msg('请选择身份证正面');
    } else if (card_img_back == '' || card_img_back == undefined) {
        return layer.msg('请选择身份证反面');
    } else if (!$("input[name='favorite']:checkbox").prop("checked")) {
        return layer.msg('请阅读并同意商家入驻协议');
    } else if(!nameRg.test(realname)) {
        return layer.msg('请输入正确的姓名格式');
    } else if (!cardRg.test(id_card)) {
        return layer.msg('请输入正确的身份证号');
    } else if (!bankRg.test(bankcard)) {
        return layer.msg('请输入正确的银行卡号');
    }else {
        // ajax
        $.ajax({
            type: 'post',
            url: '/admin/Store/submit_store_certifications',
            data: apply_info,
            dataType: 'json',
            success: function(res){
                console.log(res)
                if (res.code == 200) {
                    layer.msg(res.msg);
                    window.location.href = '/Admin/store/storeManage';
                } else {
                    layer.msg(res.msg);
                }
            }
        })
        console.log(apply_info);
    }
}

// 协议弹层
function agreement(){
    $('.submit_approve .agreement span').click(function(){
        layer.open({
            type: 1,
            title: ['商家入驻协议','font-size:14px;font-weight:normal;color:#666;'],
            area: ['480px','560px'],
            content: $('#containter')
        })
    })
}

// 执行函数
$(function(){
    storeAttribute();
    storeCategory();
    cityPicker();
    streetName();
    validationCode();
    agreement();
    // 提交认证
    $('.submit_btn').on('click',function(){
        submitStore();
    })
})