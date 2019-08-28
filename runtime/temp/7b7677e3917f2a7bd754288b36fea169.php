<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:42:"./application/admin/view/store\shenhe.html";i:1566024589;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>升级审核</title>
    <link rel="stylesheet" href="/public/static/css/base.css">
    <link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
    <link rel="stylesheet" href="/public/static/css/examine.css">
</head>
<body>
<div class="layui-card-header button">升级审核</div>
<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
    <ul class="layui-tab-title">
        <li class="layui-this">待审核</li>
        <li>审核记录</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <table id="examine" lay-filter="test" class="hide"></table>
        </div>
        <div class="layui-tab-item">
            <table id="history" lay-filter="test" class="hide"></table>
        </div>
    </div>
</div>

<div id="adopt" style="display: none; padding:20px;">
    <p style="padding:10px; font-size: 16px; text-align: center">去点亮平台审核通道以确保审核顺利</p>
</div>
<div id="reason" style="display: none; padding:10px;">
    <div style="padding:20px;">
        <textarea class="tarea" placeholder="填写拒绝理由"></textarea>
    </div>
</div>
<div id="pay" style="display: none">
    <ul>
        <li>
            <p class="fl">点亮审核通道</p>
            <p class="fr RMB"></p>
            <div class="clearfix"></div>
        </li>
        <li>
            <div>
                <div class="fl">
                    <p>账户余额</p>
                    <p class="user_money"></p>
                </div>
                <div class="fr">
                    <p  class="surplus font"></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </li>
        <li>
            <div>
                <div class="fl">
                    <p>店铺余额</p>
                    <p class="store_money"></p>
                </div>
                <div class="fr">
                    <p  class="shopsum font"></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </li>
        <!--<li>-->
            <!--<div>选择支付方式</div>-->
            <!--<hr>-->
            <!--<div class="fl payment">-->
                <!--<img src="../../../../public/static/images/store/alipay_big.png" alt="" class="zPay">-->
                <!--<div class="payTitle">-->
                    <!--<p>支付宝</p>-->
                    <!--<p>"简单、安全、快速"的支付方式</p>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="fr">-->
                <!--<p class="Alipay font select">-->
            <!--</div>-->
            <!--<div class="clearfix"></div>-->
        <!--</li>-->
    </ul>
</div>
<div id="payment">
    <div class="gdw-syt-zfmm simple-password-wrap">
        <input id="simplePasswordInput" autocomplete="off" disableautocomplete="disableautocomplete" class="simple-password-input" name="payPassword_rsainput" maxlength="6" type="text" data-busy="0" type="hidden">
        <div class="facade-wrap clearfix">
            <ul class="facade" id="simplePassword" autocomplete="off" maxlength="6">
                <li class="facade-item">
                    <i class="current"></i>
                </li>
                <li class="facade-item">
                    <i class="current"></i>
                </li>
                <li class="facade-item">
                    <i class="current"></i>
                </li>
                <li class="facade-item">
                    <i class="current"></i>
                </li>
                <li class="facade-item ">
                    <i class="current"></i>
                </li>
                <li class="facade-item">
                    <i class="current"></i>
                </li>
            </ul>
        </div>
    </div>
    <div id="set_pwd" data-url="<?php echo U('Withdraw/withdrawal_pwd'); ?>" onclick="set_pwd(this)" style="text-align: right;color:red;margin: 20px 0 0 0;cursor: pointer;display: none;" class="setPWD">点击去设置支付密码</div>
</div>
<script type="text/html" id="bar">
    <a class="layui-btn  layui-btn-normal layui-btn-xs" class="adopt"  lay-event="adopt">通过</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" class="reason" lay-event="reason">拒绝</a>
</script>
<script src="/public/static/js/jquery.js"></script>
<script src="/public/static/js/layui/layui.all.js"></script>
<script src="/public/static/js/store/examine.js"></script>
<script>
    function set_pwd(obj){
        layer.open({
            type: 2,
            title: "支付设置",
            // content: "edit.html?id=" + id,
            content: $(obj).data('url'),
            area: ["1100px", "800px"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
            // ,yes:function(index,layero){
            //     window.location.reload();
            // }


        });
    }



</script>
</body>
</html>