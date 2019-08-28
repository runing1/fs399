<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:47:"./application/admin/view/store\storeManage.html";i:1566445298;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>店铺管理</title>
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_common.css" rel="stylesheet" type="text/css"/>
    <link href="/public/static/css/store_manger.css" rel="stylesheet" type="text/css"/>
    <link href="/public/static/js/layui/css/layui.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_manger.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/store_apply.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!--弹窗js 参考文档 http://layer.layui.com/--> 
    <script type="text/javascript" src="/public/static/js/layui/layui.all.js"></script>

    <script type="text/javascript" src="/public/static/js/store/public_function.js"></script>
    <script type="text/javascript" src="/public/static/js/store/store_manger.js"></script>
    <style type="text/css">
        body {
            background: #f5f5f5;
            font-size: 14px;
            overflow: scroll;
            display: none;
            position: relative;
        }
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
        #text {
            width: 148px;
            height: 44px;
            line-height: 44px;
            text-align: center;
            color: #fff;
            position: absolute;
            bottom: 26%;
            left: 50%;
            margin: 0 0 0 -74px;
        }
    </style>
</head>
<body>
    <div id="store_manger">
        <div class="head_wrap">
            <!--店铺 head-->
            <div class="s_head">
                <div class="h_left">
                    <div class="circle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="author">
                        <!-- <img src="" alt="logo"> -->
                    </div>
                </div>
                <div class="h_middle"></div>
                <div class="h_right">
                    <p class="goods_release">商品发布</p>
                    <p class="add_bg"></p>
                </div>
            </div>
        </div>
        <!--订单/收入-->
        <div class="order_revenue">
            <!--店铺订单状态-->
            <div class="order_state">
                <div class="item">
                    <span class="item_num uncheck_num">0</span>
                    <a href="<?php echo U('store/shenhe'); ?>">待审核</a>
                </div>
                <div class="item">
                    <span class="item_num unpay_order">0</span>
                    <a href="<?php echo U('store/order_manage'); ?>?type=unpay">待付款</a>
                </div>
                <div class="item">
                    <span class="item_num ungive_order">0</span>
                    <a href="<?php echo U('store/order_manage'); ?>?type=shipped">待发货</a>
                </div>
                <div class="item">
                    <span class="item_num unget_order">0</span>
                    <a href="<?php echo U('store/order_manage'); ?>?type=received">待收货</a>
                </div>
                <div class="item">
                    <span class="item_num complaint_order">0</span>
                    <a href="<?php echo U('store/order_manage'); ?>?type=complaint">被投诉</a>
                </div>
            </div>
            <!--收入-->
            <div class="total_revenue">
                <div class="revenue_left">
                    <div class="rev_l_top">
                        <div class="rev_t_text">
                            <span class="txt_rev">总收入(元)</span>
                            <span class="txt_money">0</span>
                        </div>
                        <span style="cursor: pointer;" class="rev_t_btn">提现</span>
                        <!--<a href="<?php echo U('withdraw/storeWithdrawal'); ?>" target="workspace"></a>-->
                    </div>
                    <div class="rev_l_bottom">
                        <span class="rev_record"><?php if($count == 0): ?>最近无提现记录<?php else: endif; ?></span>
                        <a href="javascript:void(0)" data-url="<?php echo U('withdraw/withdrawal_list'); ?>" onclick="withdrawal_list(this)"> <span class="rev_detail">明细</span></a>
                    </div>
                </div>
                <div class="order_count">
                    <ul>
                        <li class="item_count">
                            <span class="count_txt">今日订单数</span>
                            <span class="count_num now_order">0</span>
                        </li>
                        <li class="item_count">
                            <span class="count_txt">今日成交额</span>
                            <span class="count_num now_amount">0</span>
                        </li>
                        <li class="item_count">
                            <span class="count_txt">昨日订单数</span>
                            <span class="count_num yesterday_order">0</span>
                        </li>
                        <li class="item_count">
                            <span class="count_txt">昨日成交额</span>
                            <span class="count_num yesterday_amount">0</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--商品状态-->
        <div class="store_shop_state">
            <div class="shop_item">
                <ul>
                    <li class="shop_item_wrap">
                        <a href="<?php echo U('admin/store/goodsManage'); ?>">
                            <p class="s_num onlin_goods">0</p>
                            <p class="s_tit">商品数量</p>
                        </a>
                    </li>
                    <li class="shop_item_wrap">
                        <a href="<?php echo U('admin/store/goodsManage'); ?>?type=obtained">
                        <p class="s_num obtained_goods">0</p>
                        <p class="s_tit">下架商品</p>
                        </a>
                    </li>
                    <li class="shop_item_wrap">
                        <a href="<?php echo U('admin/store/goodsManage'); ?>?type=uncheck">
                        <p class="s_num uncheck_goods">0</p>
                        <p class="s_tit">审核中</p>
                        </a>
                    </li>
                    <li class="shop_item_wrap">
                        <a href="<?php echo U('admin/store/goodsManage'); ?>?type=reject">
                        <p class="s_num reject_goods">0</p>
                        <p class="s_tit">未通过</p>
                        </a>
                    </li>
                    <li class="shop_item_wrap">
                        <a href="<?php echo U('admin/store/showCase'); ?>">
                        <p class="s_num num">0</p>
                        <p class="s_tit">橱窗位</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="apply_store_btn">申请店铺</div>
    <div id="text"></div>
</body>
<script type="text/javascript" src="/public/static/js/admincp.js"></script>

<script>

    function withdrawal_list(obj) {
        layer.open({
            type: 2,
            title: false,
            // content: "edit.html?id=" + id,
            content: $(obj).data('url'),
            area: ["1500px", "700px"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
        })
    }
    $(function(){
        $('#apply_store_btn').click(function(){
            window.location.href="<?php echo U('Admin/store/publishGoods'); ?>";

        });
        $('.h_right').click(function(){
            window.location.href="<?php echo U('Admin/store/releaseGoods'); ?>";

        });
        $('.rev_t_btn').click(function(){
            window.location.href="<?php echo U('Admin/Withdraw/storeWithdrawal'); ?>";
        });
        // 编辑店铺
        $('.h_left').click(function(){
            window.location.href="<?php echo U('Admin/store/editStore'); ?>";
        });
        // 发布商品
        var type = localStorage.getItem('type');
        $('.h_right').click(function(){
            if(type == "offlin") {
                $.ajax({
                    type: 'post',
                    url: '/admin/store/store_type',
                    // data: {user_id: 63},
                    dataType: 'json',
                    async: true,
                    success: function(res){
                        console.log(res)
                        if (res.code == 200) {
                            if (res.data.is_all == 0) {
                                layer.open({
                                    title: '提示',
                                    content: '请先完善店铺信息，再发布商品',
                                    btn: '确定',
                                    yes: function(index){
                                        layer.close(index);
                                        window.location.href="<?php echo U('Admin/store/editStore'); ?>";
                                    }
                                })
                            } else {
                                 window.location.href="<?php echo U('Admin/store/releaseGoods'); ?>";
//                                window.location.href="<?php echo U('Admin/store/editGoods'); ?>";
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
                 window.location.href="<?php echo U('Admin/store/releaseGoods'); ?>";
//                window.location.href="<?php echo U('Admin/store/editGoods'); ?>";
            }
        });

        $('.apply_btn').on('click',function(){
            window.location.href="<?php echo U('Admin/store/publishGoods'); ?>";
        })



    });

</script>


</html>