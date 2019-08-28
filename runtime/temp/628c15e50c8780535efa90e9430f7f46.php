<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./application/admin/view/index\welcome.html";i:1566463046;}*/ ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="/public/static/css/index.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/purebox.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <style>
        .contentWarp_item .section_select .item_comment{
            padding: 83px 0 31px 38px;
        }
        .contentWarp_item .section_select .item {
            padding: 83px 0 38px 38px;
        }
        .contentWarp_item .section_order_select li{
            width: 23%;
        }
        .contentWarp_item .section_select .item_comment{
            padding: 83px 0 31px 38px;
        }
        .contentWarp_item .section_select .item {
            padding: 83px 0 38px 38px;
        }
        @media only screen and (min-width: 900px) and (max-width: 1761px) {
            .contentWarp_item{
                margin-right: 1%;
            }
            .contentWarp_item .section_select .icon img{
                max-width: 74px;
                max-height: 74px;
            }
            .contentWarp_item:nth-child(1){
                margin-bottom: 10px;
            }
        }
        @media only screen and (min-width: 900px) and (max-width: 1312px) {
            .contentWarp_item .section_select .item{
                width: 35%;
                margin-bottom: 10px;
            }

        }
    </style>
</head>
<body class="iframe_body">
<div class="warpper">
    <div class="title">管理中心</div>
    <div class="content start_content">
        <div class="contentWarp">
            <div class="contentWarp_item clearfix">
                <div class="section_select">
                    <div class="item item_price">
                        <i class="icon"><img src="/public/static/images/1.png" width="71" height="74"></i>
                        <div class="desc">
                            <div class="tit"><?php echo $count['new_order']; ?></div>
                            <span>今日订单总数</span>
                        </div>
                    </div>
                    <div class="item item_order">
                        <i class="icon"><img src="/public/static/images/2.png"></i>
                        <div class="desc">
                            <div class="tit"><?php echo $count['new_users']; ?></div>
                            <span>团队人员总数</span>
                        </div>
                        <i class="icon"></i>
                    </div>
                    <div class="item item_comment">
                        <i class="icon"><img src="/public/static/images/3.png" width="90" height="86"></i>
                        <div class="desc">
                            <div class="tit"><?php echo $count['comment']; ?></div>
                            <span>今日新增人数</span>
                        </div>
                    </div>
                    <div class="item item_flow">
                        <i class="icon"><img src="/public/static/images/4.png" width="86"></i>
                        <div class="desc">
                            <div class="tit"><?php echo $count['today_login']; ?></div>
                            <span>上次登录时间</span>
                        </div>
                        <i class="icon"></i>
                    </div>
                </div>
            </div>
            <div class="contentWarp_item clearfix">
                <div class="section_order_select">
                    <ul>
                        <li>
                            <a href="javascript:void(0)" onClick="openItem('shenhe|store')">
                                <i class="ice ice_w"></i>
                                <div class="t">待审核订单</div>
                                <span class="number"><?php echo $uncheck_num; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onClick="openItem('storeManage|Store')">
                                <i class="ice ice_y"></i>
                                <div class="t">待付款</div>
                                <span class="number"><?php echo $unpay_order; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onClick="openItem('storeManage|Store')">
                                <i class="ice ice_q"></i>
                                <div class="t">待发货</div>
                                <span class="number"><?php echo $ungive_order; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onClick="openItem('storeManage|Store')">
                                <i class="ice ice_n"></i>
                                <div class="t">待收货</div>
                                <span class="number"><?php echo $unget_order; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onClick="openItem('storeManage|Store')">
                                <i class="ice ice_w"></i>
                                <div class="t">被投诉</div>
                                <span class="number"><?php echo $unget_order; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
                <!--<div class="section section_order_count">-->
                    <!--<div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">-->
                        <!--<i class="sc_icon"></i>-->
                        <!--<h3>版本信息</h3>-->
                    <!--</div>-->
                    <!--<div class="sc_warp" style="padding-bottom: 30px;">-->
                        <!--<table cellpadding="0" cellspacing="0" class="system_table">-->
                            <!--<tbody><tr>-->
                                <!--<td class="gray_bg">程序版本:</td>-->
                                <!--<td>TPshop <?php echo $sys_info['version']; ?></td>-->
                                <!--<td class="gray_bg">更新时间:</td>-->
                                <!--<td><?php echo date('Y-m-d');?></td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">程序开发:</td>-->
                                <!--<td><?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:'TPshop商城'); ?></td>-->
                                <!--<td class="gray_bg">版权所有:</td>-->
                                <!--<td>盗版必究</td>-->
                            <!--</tr>-->
                            <!--&lt;!&ndash;<tr>&ndash;&gt;-->
                                <!--&lt;!&ndash;<td class="gray_bg">官方授权:</td>&ndash;&gt;-->
                                <!--&lt;!&ndash;<td><a href="http://www.tp-shop.cn/" target="_blank">商业授权</a></td>&ndash;&gt;-->
                                <!--&lt;!&ndash;<td class="gray_bg">官方论坛:</td>&ndash;&gt;-->
                                <!--&lt;!&ndash;<td><a href="http://bbs.tp-shop.cn" target="_blank">TPshop交流论坛</a></td>&ndash;&gt;-->
                            <!--&lt;!&ndash;</tr>&ndash;&gt;-->
                            <!--</tbody></table>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>
        <!--<div class="contentWarp">-->
            <!--<div class="section system_section" style="float: none;width: inherit;">-->
                <!--<div class="system_section_con">-->
                    <!--<div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">-->
                        <!--<i class="sc_icon"></i>-->
                        <!--<h3>系统信息</h3>-->
                        <!--&lt;!&ndash;<span class="stop stop_jia" id="system_section" title="展开详情"></span>&ndash;&gt;-->
                    <!--</div>-->
                    <!--<div class="sc_warp" id="system_warp" style="display: block;padding-bottom: 30px;">-->
                        <!--<table cellpadding="0" cellspacing="0" class="system_table">-->
                            <!--<tbody><tr>-->
                                <!--<td class="gray_bg">服务器操作系统:</td>-->
                                <!--<td><?php echo $sys_info['os']; ?></td>-->
                                <!--<td class="gray_bg">服务器域名/IP:</td>-->
                                <!--<td><?php echo $sys_info['domain']; ?> [ <?php echo $sys_info['ip']; ?> ]</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">服务器环境:</td>-->
                                <!--<td><?php echo $sys_info['web_server']; ?></td>-->
                                <!--<td class="gray_bg">PHP 版本:</td>-->
                                <!--<td><?php echo $sys_info['phpv']; ?></td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">Mysql 版本:</td>-->
                                <!--<td><?php echo $sys_info['mysql_version']; ?></td>-->
                                <!--<td class="gray_bg">GD 版本:</td>-->
                                <!--<td><?php echo $sys_info['gdinfo']; ?></td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">文件上传限制:</td>-->
                                <!--<td><?php echo $sys_info['fileupload']; ?></td>-->
                                <!--<td class="gray_bg">最大占用内存:</td>-->
                                <!--<td><?php echo $sys_info['memory_limit']; ?></td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">最大执行时间:</td>-->
                                <!--<td><?php echo $sys_info['max_ex_time']; ?></td>-->
                                <!--<td class="gray_bg">安全模式:</td>-->
                                <!--<td><?php echo $sys_info['safe_mode']; ?></td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                                <!--<td class="gray_bg">Zlib支持:</td>-->
                                <!--<td><?php echo $sys_info['zlib']; ?></td>-->
                                <!--<td class="gray_bg">Curl支持:</td>-->
                                <!--<td><?php echo $sys_info['curl']; ?></td>-->
                            <!--</tr>-->
                            <!--</tbody></table>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>
</div>
<!--<div id="footer" style="position: static; bottom: 0px; font-size:14px;">-->
    <!--<p><b>版权所有 © 2012-2027 <?php echo (isset($tpshop_config['shop_info_store_name']) && ($tpshop_config['shop_info_store_name'] !== '')?$tpshop_config['shop_info_store_name']:'fs399'); ?>，并保留所有权利。</b></p>-->
<!--</div>-->
<script type="text/javascript">
    $(function(){
        $("*[data-toggle='tooltip']").tooltip({
            position: {
                my: "left top+5",
                at: "left bottom"
            }
        });
    });
</script>
<script type="text/javascript" src="/public/static/js/jquery.purebox.js"></script>
<script type="text/javascript" src="/public/static/js/echart/echarts.min.js"></script>
<script type="text/javascript">
    // 点击菜单，iframe页面跳转
    function openItem(param) {
        var data_str = param.split('|');
        $.cookie('workspaceParam', data_str[0] + '|' + data_str[1], { expires: 1 ,path:"/"});
        top.location.reload();
    }
</script>
</body>

</html>