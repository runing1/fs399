var number;
var dataobj = {user_id: localStorage.getItem('userId')};
// 处理输入金额
function clearNoNum(obj){ 
    obj.value = obj.value.replace(/[^\d.]/g,"");  // 清除“数字”和“.”以外的字符  
    obj.value = obj.value.replace(/\.{2,}/g,"."); // 只保留第一个. 清除多余的  
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$","."); 
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'); // 只能输入两个小数  
    if(obj.value.indexOf(".")< 0 && obj.value !=""){ // 以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额 
        obj.value= parseFloat(obj.value); 
    } 
} 

// 获取店铺地址
function address_info(){
    $.ajax({
        type: 'post',
        url : '/admin/store/store_address',
        data : dataobj,
        dataType: 'json',
        success: function(res){
            if(res.code == 200) {
                // address
                $('.goods_address').html(res.data);
            }
        },
        error: function(error){
            // 未获取到信息
            console.log(error.msg);
        }
    });
}

// 优惠方式
function discountMethod(name){
    $(name).click(function(){
        layer.open({
            type: 1,
            title: ['优惠方式','font-size:14px;font-weight:normal;color:#666;'],
            area: ['480px','280px'],
            skin: 'preferential-class',
            content: $('#promotion'),
            btn: '确认',
            yes: function(index,layero){
                var title = [];
                var value = [];
                var isFlag = true;
                if($("input[name='checkbox']:checkbox:checked").length > 0){
                    $("input[name='checkbox']:checkbox:checked").each(function(){  // 选中
                        var txt = $(this).parent().find('span').text();
                        var val = $(this).parent().parent().find('input[type="text"]').val();
                        title.push(txt);
                        value.push(val);
                        if(val == ''){ // 价格不能为空
                            isFlag = false;
                            layer.msg(txt + '优惠价格不能为空');
                            return false;
                        } else if(value[0] < value[1]){
                            title = [];
                            value = [];
                            isFlag = false;
                            return layer.msg('指定店铺优惠价格必须高于全网通用优惠价格');
                        }
                    }) 
                    
                    // 判断isFlag关闭弹窗
                    if (isFlag) {
                        // 判断value数组的length，根据length输出不同的值
                        if(value == null || value == ''){
                            $('.promotion_info').html('无');
                            $('.promotion_info').removeAttr('data-id');
                            $('.promotion_info').removeAttr('data-designation');
                            $('.promotion_info').removeAttr('data-universal');
                        } else {
                            var str = '<span class="promotion_txt">'+ title[0] +'</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ value[0] +'</span>\
                            <span style="margin-left:20px" class="promotion_txt">'+ title[1] +'</span>¥\
                            <span class="promotion_price promotion_designation_price">'+ value[1] +'</span>';
                            $('.promotion_info').html(str).css('color','#333');
                            $('.promotion_info').attr('data-id','3');
                            $('.promotion_info').attr('data-designation',value[0]);
                            $('.promotion_info').attr('data-universal',value[1]);
                        }
                        layer.close(index);
                    } 
                } else {
                    $('input[type="text"]').val(''); 
                    $('.promotion_info').html('无');
                    layer.close(index);
                }
            }   
        })
    })
}

// 线上选择类别
function onlineCategory(){
    $('.online_category').on('click',function(){
        var txt_one = '',
            txt_two = '',
            txt_three = '',
            dataId = '';
        $.ajax({
            type: 'post',
            url: '/admin/store/offlin_category',
            data: {'select_cat':0},
            dataType: 'json',
            success: function(res){
                if (res.code == 200) {
                    var data = res.data;
                    if ($('.one_bar_ul').children().length < data.length){
                        for (var i=0; i< data.length; i++){
                            $('.one_bar .one_bar_ul').append('<li class="one_bar_li"><p class="one_bar_tit" data-id="'+ data[i].cat_id +'">'+ data[i].cat_name +'</p><div class="two_bar" ><ul class="two_bar_ul"></ul></div></li>');
                        }
                    }
                    layer.open({
                        type: 1,
                        title: ['选择类别','font-size:14px;font-weight:normal;color:#666;'],
                        area: ['481px','660px'],
                        content: $('#category'), 
                        skin: 'category-class',
                        btn: '确定',
                        success:function(){
                            // 点击显示二级分类
                            $(".one_bar_tit").on('click',function(){
                                txt_one = $(this).text();
                                $(this).addClass('selected');
                                $(this).parent().siblings().children("p").removeClass('selected');
                                var msg = $(this).attr('data-id');
                                _this = $(this); 
                                $.ajax({
                                    type: 'post',
                                    url: '/admin/store/get_under_category',
                                    data: {'select_cat':0,cat_id: msg},
                                    dataType: 'json',
                                    success:function(res){
                                        var data = res.data;
                                        if(_this.parent().find('.two_bar_ul').children().length < data.length){
                                            for(let i=0; i<data.length; i++){
                                                _this.parent().find('.two_bar_ul').append('<li class="two_bar_li"><p class="two_bar_tit" data-id="'+ data[i].cat_id +'">'+ data[i].cat_name +'</p><div class="three_bar"><ul class="three_bar_ul"></ul></div></li>');
                                            }
                                        }
                                        _this.parent().siblings().children(".two_bar").hide();
                                        _this.parent().children(".two_bar").show();
                                        // 点击显示三级分类
                                        $('.two_bar_tit').on('click',function(){
                                            txt_two = $(this).text();
                                            $(this).addClass('selected');
                                            $(this).parent().siblings().children("p").removeClass('selected');
                                            var msg = $(this).attr('data-id');
                                            _this = $(this); 
                                            $.ajax({
                                                type: 'post',
                                                url: '/admin/store/get_under_category',
                                                data: {'select_cat':0,'cat_id':msg},
                                                dataType: 'json',
                                                success:function(res){
                                                    var data = res.data;
                                                    if(_this.parent().find('.three_bar_ul').children().length < data.length){
                                                        for(let k=0; k<data.length; k++){
                                                            _this.parent().find('.three_bar_ul').append('<li class="three_bar_li"><p class="three_bar_tit" data-id="'+ data[k].cat_id +'">'+ data[k].cat_name +'</p></li>');
                                                        }
                                                    }
                                                    _this.parent().siblings().children(".three_bar").hide();
                                                    _this.parent().children(".three_bar").show();
                                                    event.stopPropagation();
                                                    $('.three_bar_tit').on('click',function(){
                                                        $(this).addClass('selected');
                                                        $(this).parent().siblings().children("p").removeClass('selected');
                                                        txt_three = $(this).text();
                                                        dataId = $(this).attr('data-id');
                                                    })
                                                }
                                            })
                                        })
                                    }
                                })
                            });
                        },
                        yes: function(index){
                            if (txt_three != '') {
                                layer.close(index);  // 关闭弹窗
                                // 保存选中的值
                                $('.online_choose').text(txt_three).css('color','#333');
                                $('.online_choose').attr('data-id',dataId);
                            } else {
                                return layer.msg('请选择完整分类');
                            }
                        }
                    });
                }
            }
        })
    })
}

// 线上优惠方式
function onlineDiscount() {
    discountMethod('.online_preferential');
}

// 线下类别选择
function offlineCategory(){
    $('.offline_choose').on('click',function(){
        var txt_one = '',
            txt_two = '',
            dataId = '';
        $.ajax({
            type: 'post',
            url: '/admin/store/store_info',
            data: dataobj,
            dataType: 'json',
            success: function(res){
                console.log(res)
                if (res.code == 200) {
                    var data = res.data;
                    if($('.one_bar_ul').children().length < 1){
                        $('.one_bar .one_bar_ul').append('<li class="one_bar_li offline_one_bar_li"><p class="one_bar_tit offline_one_bar_tit" data-id="'+ data.cat_id +'">'+ data.category +'</p><div class="two_bar offline_two_bar" ><ul class="two_bar_ul offline_two_bar_ul"></ul></div></li>');
                    }
                    
                    layer.open({
                        type: 1,
                        title: ['选择类别','font-size:14px;font-weight:normal;color:#666;'],
                        area: ['320px','415px'],
                        content: $('#category'), 
                        skin: 'category-class',
                        btn: '确定',
                        success:function(){
                            // 点击显示二级分类
                            $(".one_bar_tit").on('click',function(){
                                txt_one = $(this).text();
                                $(this).addClass('selected');
                                $(this).parent().siblings().children("p").removeClass('selected');
                                var msg = $(this).attr('data-id');
                                _this = $(this); 
                                $.ajax({
                                    type: 'get', 
                                    url: '/admin/UnderLineIndex/getChildCat',
                                    data: {'cat_id':msg},
                                    dataType: 'json',
                                    success:function(res){
                                        var data = res.data;
                                        if (res.code == 200) {
                                            for(let i=0; i<data.length; i++){
                                                if(_this.parent().find('.two_bar_ul').children().length < data.length){
                                                    _this.parent().find('.two_bar_ul').append('<li class="two_bar_li offline_two_bar_li"><p class="two_bar_tit offline_two_bar_tit" data-id="'+ data[i].cat_id +'">'+ data[i].cat_name +'</p></li>');
                                                }
                                            }
                                            _this.parent().siblings().children(".two_bar").hide();
                                            _this.parent().children(".two_bar").show();
                                            // 保存选中的值
                                            $('.offline_two_bar_tit').on('click',function(){
                                                txt_two = $(this).text();
                                                dataId = $(this).attr('data-id');
                                                $(this).addClass('selected');
                                                $(this).parent().siblings().children("p").removeClass('selected');
                                            })
                                        } else {
                                            console.log(res.msg);
                                        }
                                    }
                                })
                            });
                        },
                        yes: function(index){
                            if (txt_one != '' && txt_two != '') {
                                var text = $('one_bar_tit').text();
                                layer.close(index);  // 关闭弹窗
                                // 保存选中的值
                                $('.offline_choose').text(text + ' ' + txt_two);
                                $('.offline_choose').attr('data-id',dataId);
                            } else {
                                return layer.msg('请选择类别');
                            }
                        }
                    });
                }
            }
        })
    })
}

// 线下优惠方式
function offlineDiscount(){
    discountMethod('.offline_preferential');
}

// 普通图片上传
function singleUploadImg(id){
    layui.use('upload', function(){
        var upload = layui.upload;
        upload.render({
            elem: id,
            method: 'post',
            url: '/admin/store/oss_upload_file_logoimage',
            size: 1024 * 1,  // 上传大小
            choose: function(obj){
                obj.preview(function(index, file, result){
                    $(id).parent().find('img').attr('src', result).show(); //图片链接（base64）直接将图片地址赋值给img的src属性
                    $(id).parent().find('span').hide();
                    $(id).parent().css('background-image','url()');
                });
            },
            done: function(res, index, upload){
                if (res.code == 200) {
                    $(id).parent().find('input[type="hidden"]').val(res.data);
                }
            },
            error: function(){
                layer.msg('图片上传失败，请稍后重试')
            }
        });
    });
}

// 获取图片的数量
$.ajax({
    type: 'GET',
    url: '/admin/store/store_info',
    // data: dataobj,
    dataType: 'json',
    async: true,
    success: function(res){
        console.log(res);
        if (res.code == 200) {
            if (res.data.type == 'offlin') {
                if (res.data.store_img != '') {
                    number = res.data.store_img.length;
                } else {
                    number = 0;
                }
            }
            return number;
        }
    }
})
// 多图上传图片
function moreUploadImg(id,count,num){
    layui.use('upload', function(){
        var upload = layui.upload;
        upload.render({
            elem: id,
            method: 'post',
            url: '/admin/store/oss_upload_file_logoimage',
            accept: 'image',
            size: 1024 * 1, // 限定大小
            choose: function(obj){ 
                obj.preview(function(index, file, result){
                    if($(id).parent().find('.pic').length > count - num) {
                        return layer.msg('做多上传5张图片');
                    } else {
                        $(id).before('<div class="pic"><img src="'+ result +'" alt="" /><p class="close"></p></div>');
                    }
                });
            },
            done: function(res, index, upload){ // 每个文件提交一次触发一次
                if($(id).parent().find('.pic').length > count-num) {
                    return;
                } else {
                    $('.pic img').before('<input type="hidden" value="'+res.data+'" />');
                }
            },       
            error: function(){
                layer.msg('图片上传失败，请稍后重试')
            }
        });
    });
    $('.pic .close').live('click',function(){
        $(this).parent().remove();
    })
}



$(function(){
    address_info();
    onlineCategory(); // 类别
    onlineDiscount(); // 优惠方式
    offlineCategory(); // 类别
    offlineDiscount(); // 优惠方式
    // 头像上传
    singleUploadImg('#logo_file');
    // 二维码上传
    singleUploadImg('#code_file');
    // 身份证上传
    singleUploadImg('#positive_card_file');  // 正面
    singleUploadImg('#reverse_card_file');  // 反面

    moreUploadImg('#tit_goods_file',5,number); // 线上商品图
    moreUploadImg('#on_detail_goods_file',8,number); // 线上详情图
    moreUploadImg('#off_detail_goods_file',8,number); // 线下商品图
    moreUploadImg('#store_goods_file',5,number); // 店铺编辑
})