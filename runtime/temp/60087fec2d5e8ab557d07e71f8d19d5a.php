<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./application/admin/view/store\order_manage.html";i:1566180707;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>商品状态</title>
    <link rel="stylesheet" href="/public/static/css/base.css">
    <link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
    <link rel="stylesheet" href="/public/static/css/status.css">
</head>
<body>
<div class="status_con">
    <div class="headline">订单管理</div>
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
            <!--<li  class="layui-this"  data-value="unpay">待付款</li>-->
            <li class="layui-this"><a href="javascript:;" data-value="unpay">待付款</a></li>

            <!--<li  data-value="shipped">待发货</li>-->
            <li><a href="javascript:;" data-value="shipped">待发货</a></li>
            <!--<li  data-value="received">待收货</li>-->
            <li><a href="javascript:;" data-value="received">待收货</a></li>
            <!--<li  data-value="complaint">被投诉</li>-->
            <li><a href="javascript:;" data-value="complaint">被投诉</a></li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show obligation">
                <div class="acquiesce">
                    <img src="../../../../public/static/images/store/frame_pager_empty.png" alt="">
                    <p class="font16">暂无数据</p>
                </div>
            </div>
            <div class="layui-tab-item Overhang">
                <div id="plist">
                    <div class="acquiesce">
                        <img src="../../../../public/static/images/store/frame_pager_empty.png" alt="">
                        <p class="font16">暂无数据</p>
                    </div>
                </div>
                <div id="overhangPage"></div>
            </div>
            <div class="layui-tab-item receiving">
                <div id="receList">
                    <div class="acquiesce">
                        <img src="../../../../public/static/images/store/frame_pager_empty.png" alt="">
                        <p class="font16">暂无数据</p>
                    </div>
                </div>
                <div id="recePage"></div>
            </div>
            <div class="layui-tab-item complained">
                <div class="acquiesce">
                    <img src="../../../../public/static/images/store/frame_pager_empty.png" alt="">
                    <p class="font16">暂无数据</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="material" style="display: none">
    <ul></ul>
</div>
<script src="/public/static/js/jquery.js"></script>
<script src="/public/static/js/layui/layui.all.js"></script>
<script src="/public/static/js/store/common.js"></script>
<script src="/public/static/js/store/status.js"></script>
</body>
</html>