<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:40:"./application/admin/view/store\shop.html";i:1566208987;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>店铺</title>
    <link rel="stylesheet" href="/public/static/css/base.css">
    <link rel="stylesheet" href="/public/static/css/shop.css">
</head>
<style>
    #apply_store_btn {
        width: 148px;
        height: 44px;
        line-height: 44px;
        text-align: center;
        border-radius: 10px;
        background: #50d7fc;
        color: #fff;
        font-size: 14px;
        position: absolute;
        bottom: 20%;
        left: 50%;
        margin: 0 0 0 -74px;
        cursor: pointer;
    }
</style>
<body>
<div class="shop_con">
    <div class="show_con">
    </div>
    <div id="apply_store_btn" style="display: none;">申请店铺</div>
</div>
<script src="/public/static/js/jquery.js"></script>
<script src="/public/static/js/store/shop.js"></script>
<script type="text/javascript" src="/public/static/js/layui/layui.all.js"></script>
<script>
    $('#apply_store_btn').click(function () {
        window.location.href = "<?php echo U('Admin/store/publishGoods'); ?>";

    });
</script>
</body>
</html>