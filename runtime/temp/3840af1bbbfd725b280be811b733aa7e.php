<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./application/admin/view/store\releaseGoods.html";i:1564825056;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>店铺管理</title>
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_common.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/release_goods.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/datepicker.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/layer_open.css" rel="stylesheet" type="text/css"/><!--layer.open弹框样式-->
    <link href="/public/static/css/calender.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/layui/css/layui.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        body {
            background: #f5f5f5;
            font-size: 14px;
            overflow: scroll;
        }
        .right_content span {
            color: #333;
        }
        #apply_store_main .submit_approve {
            padding: 10px 20px;
        }
        .submit_approve .submit_btn {
            margin: 0 auto;
        }
        .date_box{
            position: relative;
            cursor: pointer;
        }
        #firstSelect{
            position: absolute;
            top: 0;
            left: 0;
            height: 28px;
            overflow: hidden;
        }
    </style>
</head>
<body>
<!--发布商品-->
<div id="goods_release" style="display: none;">
    <div class="release_head">
        <h2 class="release">商品发布</h2>
        <div class="back_btn">取消</div>
    </div>
    <!--线上商品发布-->
    <div class="release_online release_wrap">
        <div class="online_head">
            <!--图片上传-->
            <div class="release_up_pic tit_pic">
                <div class="upload_file" id="tit_goods_file"></div>
            </div>
        </div>
        <div class="online_content">
            <div class="content_top bgcolor mt24">
                <div class="goods_title">
                    <input class="on_tit_input good_input" type="text" placeholder="标题""/>
                </div>
                <div class="goods_detail">
                    <textarea class="detail_textarea" type="text" placeholder="在这里详细描述一下宝贝商品吧～（最多60个字），也可以直接上传图片即可"></textarea>
                    <!--图片上传-->
                    <div class="release_up_pic on_detail_pic">
                        <div class="upload_file" id="on_detail_goods_file"></div>
                    </div>
                    <div class="goods_address">浙江省 温州市 鹿城区 南浦街道</div>
                </div>
            </div>
            <div class="content_middle bgcolor mt24">
                <div class="online_category release_category" style="cursor: pointer;">
                    <p class="c_tit">类别</p>
                    <div style="font-size:12px;">
                        <span class="c_choose online_choose">请选择</span>
                        <span style="color: #999;" class="c_arrow">></span>
                    </div>
                </div>
                <div class="online_custom release_category custom_tag" style="cursor: pointer;">
                    <p class="attr_tit">自定义</p>
                    <div style="font-size:12px;">
                        <span style="color: #999;" class="c_arrow">></span>
                    </div>
                    <div class="hidden_txt" style="display: none;"></div>
                </div>
                <div class="online_custom release_category price_tag" style="cursor: pointer;display: none;">
                    <p class="attr_tit">价格/库存</p>
                    <div style="font-size:12px;">
                        <span style="color: #999;" class="c_arrow">></span>
                    </div>
                    <div class="attr_txt" style="display: none;"></div>
                </div>
            </div>
            <div class="content_bottom bgcolor mt24">
                <div class="online_weight release_item">
                    <p>净含量</p>
                    <div class="item_right">
                        <input class="weight_input item_input" type="text" placeholder="0" oninput="clearNoNum(this)" />
                        <span>g</span>
                    </div>
                </div>
                <div class="online_inventory release_item">
                    <p>库存</p>
                    <div class="item_right">
                        <input class="inventory_input item_input" type="text" placeholder="0" oninput="clearNoNum(this)" />
                        <span>件</span>
                    </div>
                </div>
                <div class="online_price release_item">
                    <p>价格</p>
                    <div class="item_right" style="position: relative;right: -34px;">
                        <span style="color: #f15656;">¥</span>
                        <input class="price_input online_price_input" type="text" placeholder="0.00" style="color: #f15656;" oninput="clearNoNum(this)" />
                    </div>
                </div>
                <div class="online_freight release_item">
                    <p>运费</p>
                    <div class="item_right">
                        <input class="freight_input item_input" type="text" placeholder="0.00" oninput="clearNoNum(this)" />
                        <span>元</span>
                    </div>
                </div>
                <div class="online_preferential release_item" style="cursor: pointer;">
                    <p>优惠方式</p>
                    <div class="item_right" style="display: flex;justify-content: space-between;align-items:center;">
                        <div class="promotion_info" style="margin-right:5px;">无</div>
                        <span style="color: #999;" class="c_arrow">></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--线下商品发布-->
    <div class="release_offline release_wrap">
        <div class="offline_category bgcolor">
            <div class="category_wrap release_category">
                <p class="c_tit">发布类别</p>
                <div>
                    <span class="c_choose offline_choose">请选择</span>
                    <span style="color: #999;" class="c_arrow">></span>
                </div>
            </div>
        </div>
        <div class="offline_content bgcolor mt24">
            <div class="good_title release_border">
                <input class="off_tit_input good_input" type="text" maxlength="16" placeholder="标题 (套餐或者服务的名称)"/>
            </div>
            <div class="good_introduce release_border">
                <input class="off_introduce good_input" type="text" maxlength="56" placeholder="商品介绍 (简要描述套餐内容或者商品卖点)"/>
            </div>
            <div class="offline_good_detail">
                <textarea class="detail_textarea offline_detail" maxlength="300" placeholder="具体描述套餐或服务信息"></textarea>
            </div>
        </div>
        <div class="offline_img bgcolor mt24">
            <!--图片上传-->
            <div class="release_up_pic detail_pic">
                <div class="upload_file" id="off_detail_goods_file"></div>
            </div>
            <div class="goods_address">浙江省 温州市 鹿城区 南浦街道</div>
        </div>
        <div class="offline_price_content bgcolor mt24">
            <div class="category_wrap release_category release_border hotel_category">
                <p class="c_tit">房源日期</p>
                <div class="date_box">
                    <p class="c_choose date_choose">选择日期 ></p>
                    <div id="firstSelect" style="width:100%;">
                        <div class="date_select_bar" style="display: none;">
                            <input id="startDate" class="date_input" type="text" data-start='' value=""style="" readonly>
                            <input id="endDate" class="date_input" type="text" data-end='' value="" style="" readonly>
                            <span class="night_box">共<span class="number_night NumDate">1</span>晚<i class="right_go_ico"></i></span>
                            <input type="hidden" value="" id="date_value">
                        </div>
                    </div>
                    <div class="mask_calendar">
                        <div class="c_calendar"></div>
                    </div>
                </div>
                <input type="hidden" value="" class="hidden_value">
                <input type="hidden" value="" class="start_date">
                <input type="hidden" value="" class="end_date">
            </div>
            <div class="offline_price release_item release_border">
                <p>价格</p>
                <div class="item_right">
                    <span class="right_color">¥</span>
                    <input class="price_input shop_price right_color" type="text" placeholder="0.00" oninput="clearNoNum(this)" />
                    <div class="priceBox">
                        <span class="date_shop_price" style="visibility:hidden;cursor: pointer;" onclick="$('.hotel_category').find('span').length != 0 && $('.shop_price').val() != ''?AjaxTime():layer.msg('请选择日期并输入价格')">按日期定价 ></span>
                        <input type="hidden" value="" class="date_shop_input">
                        <div class="date_time_price" style="display:none;"></div>
                    </div>
                </div>
            </div>
            <div class="offline_original_price release_item release_border">
                <p>原价<span class="msg">可不填</span></p>
                <div class="item_right">
                    <span class="right_color">¥</span>
                    <input class="price_input market_price right_color" type="text" placeholder="0.00"  oninput="clearNoNum(this)" />
                    <span class="date_price" id="calendar" name="calendar" style="visibility:hidden;cursor: default;">按日期定价 ></span>
                </div>
            </div>
            <div class="offline_preferential release_item">
                <p>优惠方式</p>
                <div class="item_right" style="display: flex;justify-content: space-between;align-items:center;cursor: pointer;">
                    <div class="promotion_info" style="margin-right:5px;">无</div>
                    <span style="color: #999;" class="c_arrow">></span>
                </div>
            </div>
        </div>
    </div>
    <!--上传-->
    <div class="goods_submit_btn" id="submit_btn">确认上传</div>
</div>
</body>
<!--类别-->
<div id="category">
    <div class="one_bar">
        <ul class="one_bar_ul"></ul>
    </div>
</div>
<!--自定义-->
<div id="setting_spec">
    <div class="spec_content"></div>
    <div class="spec_button">
        <p>提示：点击标签即可对标签进行修改</p>
        <div id="add_btn">+</div>
    </div>
</div>
<!--添加-->
<div id="fill_box">
    <input class="add" type="text" maxlength="30" placeholder="请输入需要添加的标签内容" />
</div>
<!--修改-->
<div id="modify_box">
    <input class="modify" type="text" maxlength="30" placeholder="请输入需要修改的标签内容" />
</div>
<!--价格和规格-->
<div id="price">
    <div class="price_head">
        <p>价格库存设置</p>
        <span class="all_set">批量</span>
    </div>
    <div class="price_content"></div>
</div>
<!--批量-->
<div id="all_set">
    <div class="set_item">
        <p>批量设置价格</p>
        <input class="set_price" type="text" maxlength="30" placeholder="请输入" oninput="clearNoNum(this)" />
    </div>
    <div class="set_item">
        <p>批量设置库存</p>
        <input class="set_count" type="text" maxlength="30" placeholder="请输入" />
    </div>
</div>
<!--优惠方式-->
<div id="promotion">
    <div class="promotion_content">
        <div class="promotion_item promotion_designation">
            <div class="p_left">
                <input name="checkbox" type="checkbox" class="tui-checkbox" checked="checked">
                <span>指定店铺</span>
            </div>
            <div class="p_right">
                <input type="text" placeholder="请输入优惠价格" oninput="clearNoNum(this)" />
            </div>
        </div>
        <div class="promotion_item promotion_universal">
            <div class="p_left">
                <input name="checkbox" type="checkbox" class="tui-checkbox" checked="checked">
                <span>全网通用</span>
            </div>
            <div class="p_right">
                <input type="text" placeholder="请输入优惠价格" oninput="clearNoNum(this)" />
            </div>
        </div>
    </div>
</div>
<!--**线下**-->
<!--类别-->
<div id="category">
    <div class="one_bar offline_one_bar">
        <ul class="one_bar_ul offline_one_bar_ul"></ul>
    </div>
</div>
<!--日历价格设置-->
<div id="setPrice" style="display: none;padding: 10px;">
    <input type="text" id="xgPrice" onkeyup="value=value.replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1')" style="border-radius: 25px;border:1px solid #67c1fe; width:200px;height: 30px;padding: 0 10px" placeholder="请输入价格">
</div>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/layui/layui.all.js"></script>
<script type="text/javascript" src="/public/static/js/store/public_function.js"></script>
<script type="text/javascript" src="/public/static/js/store/release_goods.js"></script>
<script type="text/javascript" src="/public/static/js/store/date.js"></script>
<script type="text/javascript" src="/public/static/js/store/zlDate.js"></script>
<script type="text/javascript">
    var startDate = $('.start_date').val(),
        endDate = $('.end_date').val();
    // 日期选择
    $('#firstSelect').on('click',function () {
        $('.mask_calendar').show();
    });
    $('.mask_calendar').on('click',function (e) {
        if(e.target.className == "mask_calendar"){
            $('.c_calendar').slideUp(200);
            $('.mask_calendar').fadeOut(200);
        }
    })
    $('#firstSelect').calendarSwitch({
        selectors : {
            sections : ".c_calendar"
        },
        index : 4,      //展示的月份个数
        animateFunction : "slideToggle",        //动画效果
        controlDay:true,//知否控制在daysnumber天之内，这个数值的设置前提是总显示天数大于90天
        daysnumber : "90",     //控制天数
        comeColor : "#18beec",       //入住颜色
        outColor : "#18beec",      //离店颜色
        comeoutColor : "#50d7fc",        //入住和离店之间的颜色
        startDate: startDate,   // 入住时间
        endDate: endDate,   // 离店时间
        callback :function(){ //回调函数
            $('.mask_calendar').fadeOut(200);
            var startDate = $('#startDate').val();  //入住的天数
            var endDate = $('#endDate').val();      //离店的天数
            var NumDate = $('.NumDate').text();    //共多少晚
            var date_hidden = $('#date_hidden').val();
            if (startDate != '' && endDate != '') {
                $('.date_choose').text('开始时间'+' '+startDate +' '+' '+' '+'结束时间'+' '+endDate).css('color','#333');
            } else {
                $('.date_choose').text('选择日期 >').css('color','#999');
            }
            $('.hidden_value').val(date_hidden);
        },
        comfireBtn:'.comfires',//确定按钮的class或者id
        cancelBtn: '.cancels'//取消按钮的class或者id
    });
</script>
</html>