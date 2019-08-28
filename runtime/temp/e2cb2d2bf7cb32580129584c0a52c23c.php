<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:45:"./application/admin/view/store\editStore.html";i:1566289208;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>店铺管理</title>
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_common.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_apply.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/layui/css/layui.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/layui/layui.all.js"></script>
    <style type="text/css">
        .right_content span {
            color: #333;
        }
        #apply_store_main .submit_approve {
            padding: 10px 20px;
        }
        .submit_approve .submit_btn {
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!--编辑店铺-->
    <div id="apply_store" style="display: none;">
        <div class="edit_store">
            <div class="edit_info">
                <div class="edit_back">&lt;</div>
                <div class="edit_txt">编辑店铺资料</div>
                <div class="edit_preview">预览</div>
            </div>
        </div>
        <!--head-->
        <div id="apply_store_head">
            <div class="apply_logo">
                <p class="left_content logo_txt">店铺LOGO</p>
                <div class="right_content logo_content choose_upload">
                    <input type="hidden" class="logo_hidden" value="">
                    <img src="" alt="">
                    <span>></span>
                    <div class="upload_file" id="logo_file"></div>
                </div>
            </div>
        </div>
        <div id="apply_store_main">
            <!--店铺信息-->
            <div class="main_store_info">
                <div class="store_name apply_store_item">
                    <p class="left_content store_name_txt">商家名称</p>
                    <div class="right_content store_name_content">
                        <input class="apply_store_input store_name_input" maxlength="15" type="text" placeholder="请输入商家名称">
                    </div>
                </div>
                <div class="apply_attribute apply_store_item">
                    <p class="left_content attribute_txt">商家属性</p>
                    <div class="right_content apply_attribute_content">
                        <span class="apply_attribute_txt"></span>
                    </div>
                </div>
                <div class="apply_category apply_store_item">
                    <p class="left_content category_txt">店铺类别</p>
                    <div class="right_content apply_category_content">
                        <span class="apply_category_txt"></span>
                    </div>
                </div>
                <div class="apply_region apply_store_item">
                    <p class="left_content region_txt">所在地区</p>
                    <div class="right_content apply_region_content">
                        <span class="apply_region_txt"></span>
                    </div>
                </div>
                <div class="apply_street apply_store_item">
                    <p class="left_content street_txt">选择街道</p>
                    <div class="right_content apply_street_content">
                        <span class="apply_street_txt"></span>
                    </div>
                </div>
                <div class="apply_address apply_store_item">
                    <p class="left_content address_txt">具体地址</p>
                    <div class="right_content apply_address_content">
                        <input class="apply_store_input apply_address_input" type="text" placeholder="请输入">
                    </div>
                </div>
                <div class="apply_location apply_store_item">
                    <p class="left_content location_txt">确定地图定位</p>
                    <div class="right_content apply_location_content">
                        <span class="apply_location_txt" style="cursor: pointer;">未定位</span>
                    </div>
                </div>
            </div>
            
            <div id="main_info_middle" style="background: #fff;margin-top: 24px">
                <div class="apply_QQ apply_store_item">
                    <p class="left_content QQ_txt">店家QQ</p>
                    <div class="right_content apply_QQ_content">
                        <span class="apply_store_txt apply_QQ_txt"></span>
                    </div>
                </div>
                <div class="apply_wechat apply_store_item">
                    <p class="left_content wechat_txt">店家微信</p>
                    <div class="right_content apply_wechat_content">
                        <span class="apply_store_txt apply_wechat_txt"></span>
                    </div>
                </div>
                <!--微信二维码-->
                <div class="main_wechat_code" style="border-bottom: 1px solid #e7e7e7;">
                    <div class="left_text">
                        <p>微信二维码</p>
                        <span>我的-个人信息-我的二维码 截图上传至平台</span>
                    </div>
                    <div class="right_pic" id="choose_code_btn">
                        <img src="" alt="" />
                    </div>
                </div>
                <div class="mobile_phone apply_store_item">
                    <p class="left_content mobile_phone_txt">手机号码</p>
                    <div class="right_content mobile_phone_content">
                        <span class="apply_store_txt apply_mobile_phone_txt"></span>
                    </div>
                </div>
                <div class="real_name apply_store_item">
                    <p class="left_content real_name_txt">真实姓名</p>
                    <div class="right_content real_name_content">
                        <span class="apply_store_txt apply_real_name_txt"></span>
                    </div>
                </div>
            </div>
            <!--个人信息-->
            <div class="main_personal_info" style="padding: 10px 20px;">
                <div class="store_tag">
                    <div class="tag_tit">店铺优势<span>自定义标签内容，增加店铺卖点</span></div>
                    <div class="tag_add_input">
                        <input type="text" placeholder="请输入关键字，不超过8个字" maxlength="8" />
                        <div class="tag_add_btn">添加</div>
                        <span class="tag_number"><em>0</em>/5</span>
                    </div>
                    <div class="tag_content"></div>
                </div>
            </div>
            <!--上传店铺图-->
            <div class="main_store_img" style="padding: 10px 20px;">
                <div class="store_upload">
                    <p class="tit">上传店铺图</p>
                    <!--图片上传-->
                    <div class="release_up_pic store_pic" style="margin: 15px 0 15px -10px;">
                        <div class="upload_file" id="store_goods_file"></div>
                    </div>
                </div>
            </div>
            <!--上传店铺图-->
            <div class="main_store_content" style="padding: 20px 20px;">
                <div class="store_message">
                    <p class="tit">商家信息</p>
                    <div class="msg_textarea">
                        <textarea type="text" maxlength="800" placeholder="输入您的店铺资料～"></textarea>
                        <span class="area_number"><em>0</em>/800</span>
                    </div>
                </div>
            </div>
            <!--提交-->
            <div class="submit_approve">
                <div class="submit_btn">确定</div>
            </div>
        </div>
    </div>
</body>
<!--地图定位-->
<div id="store_map">
    <div id="container"></div>
    <div class='address'>
        <div id='lng' style="display: none;"></div>
        <div id='address'></div>
    </div>
</div>
<style>
    /*地图*/
    #store_map {
        position: relative;
        width: 100%;
        height: 100%;
        display: none;
    }
    #container {
        width: 100%;
        height: 100%;
    }
    #store_map .address {
        position: absolute;
        bottom: 100px;
        left: 0;
        height: 32px;
        line-height: 32px;
        background: rgba(255,255,255,.8);
        border-radius: 14px; 
        padding: 0 10px;
        font-size: 12px;
        color: #50d7fc;
    }
    /**编辑店铺**/
    .edit_store {
        padding: 2px 20px 10px;
        margin-top: -20px;
        background: #fff;
        color: #333;
        font-size: 16px;
    }

    .edit_store .edit_info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 48px;
    }

    .edit_info .edit_back,.edit_info .edit_preview {
        color: #50d7fc;
        font-size: 14px;
        cursor: pointer;
    }
    /*店铺优势*/
    .store_tag .tag_tit {
        line-height: 36px;
    }
    .store_tag .tag_tit span {
        color: #999;
        font-size: 12px;
        margin-left: 10px;
    }
    .store_tag .tag_add_input {
        height: 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-top: 6px;
        position: relative;
    }
    .store_tag .tag_add_input input {
        width: 300px;
        height: 28px;
        line-height: 28px;
        background: #e7e7e7;
        border-radius: 14px 0 0 14px;
        text-indent: 10px;
        font-size:12px;
    }
    .store_tag .tag_add_input .tag_add_btn {
        width: 68px;
        height: 32px;
        line-height: 30px;
        background: #50d7fc;
        text-align: center;
        color: #fff;
        border-radius: 0 14px 14px 0;
        cursor: pointer;
    }
    .store_tag .tag_number {
        position: absolute;
        top: 8px;
        left: 280px;
        color: #999;
        font-size: 12px;
    }
    /*标签内容*/
    .tag_content {
        margin-top: 16px;
    }
    .tag_content .tag_item {
        display: inline-block;
        height: 22px;
        padding: 2px 24px 2px 12px;
        color: #50d7fc;
        border: 1px solid #50d7fc;
        border-radius: 14px;
        font-size: 12px;
        position: relative;
        margin-right: 10px;
    }
    .tag_content .tag_item .tag_txt {
        display: block;
        line-height: 23px;
    }
    .tag_content .tag_item .tag_close {
        position: absolute;
        top: 5px;
        right: 12px;
        cursor: pointer;
    }
    /*上传店铺图、商家信息*/ 
    .main_store_img,.main_store_content {
        background: #fff;
        margin-top: 24px;
    }
    .main_store_img .store_upload .tit {
        font-size: 12px;
    }
    .main_store_content .store_message .msg_textarea {
        position: relative;
    }
    .main_store_content .store_message .tit {
        line-height: 20px;
    }
    .main_store_content .store_message .msg_textarea textarea {
        font-size: 12px;
        width: 100%;
        min-height: 48px;
        resize: none !important;
        padding: 10px 0;
        color: #666;
    }
    .msg_textarea .area_number {
        position: absolute;
        right: 10px;
        color: #999;
        font-size: 12px;
    }
</style>
<script type="text/javascript" src="/public/static/js/store/public_function.js"></script>
<script type="text/javascript" src="/public/static/js/store/edit_store_info.js"></script>
<!-- PositionPicker -->
<!--<base href="https://webapi.amap.com/ui/1.0/ui/misc/PositionPicker/examples/" />-->
<!--<script type="text/javascript" src='https://webapi.amap.com/maps?v=1.4.15&key=56c427e56d3a91626433bab18df33e6d&plugin=AMap.ToolBar'></script>-->
<!--&lt;!&ndash; UI组件库 1.0 &ndash;&gt;-->
<!--<script type="text/javascript" src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>-->
<!--&lt;!&ndash; Geocoder &ndash;&gt;-->
<!--<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.15&key=56c427e56d3a91626433bab18df33e6d&plugin=AMap.Geocoder"></script>-->
</html>