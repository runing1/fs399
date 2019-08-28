<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:44:"./application/admin/view/store\showCase.html";i:1564383453;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>橱窗位</title>
    <link rel="stylesheet" type="text/css" href="/public/static/js/layui/css/layui.css" />
    <link rel="stylesheet" type="text/css" href="/public/static/css/shoplist.css" />
    <script src="/public/static/js/layui/layui.js" charset="utf-8"></script>
</head>
<body class="layout">
    <div class="mainContent">
        <div class="title">
            <h1>橱窗位</h1>
        </div>
        <div id="listview"></div>
        <div id="layuipage"></div>
        <script type="text/html" id="record">
        <div class="section_select layui-row">
            <div class="layui-col-xs4">
                <div class="item clearfix">
                    <i class="icon"><img src="../../../../public/static/images/store/icon_money.png" width="71" height="74"></i>
                    <div class="desc">
                        <h3>总收入(元)</h3>
                        <p><em>¥</em>{{d.total_income}}</p>
                    </div>
                </div>
            </div>
            <!--<div class="layui-col-xs4">-->
                <!--<div class="item clearfix">-->
                    <!--<i class="icon"><img src="../../../../public/static/images/store/icon_money.png" width="71" height="74"></i>-->
                    <!--<div class="desc">-->
                        <!--<h3>租金收入(元)</h3>-->
                        <!--<p><em>¥</em>{{d.today_income}}</p>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <div class="layui-col-xs4">
                <div class="item clearfix">
                    <i class="icon"><img src="../../../../public/static/images/store/icon_money.png" width="71" height="74"></i>
                    <div class="desc">
                        <h3>昨日成交额(元)</h3>
                        <p><em>¥</em>{{d.yesterday_income}}</p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs4">
                <div class="item clearfix">
                    <i class="icon"><img src="../../../../public/static/images/store/icon_money.png" width="71" height="74"></i>
                    <div class="desc">
                        <h3>剩余橱窗位</h3>
                        <p>{{d.remainder_num}}</p>
                    </div>
                </div>
            </div>
            <!--<div class="layui-col-xs4">-->
                <!--<div class="item clearfix">-->
                    <!--<i class="icon"><img src="../../../../public/static/images/store/icon_money.png" width="71" height="74"></i>-->
                    <!--<div class="desc">-->
                        <!--<h3>转让/个</h3>-->
                        <!--<p><em>¥</em>{{d.transfer_fee}}</p>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        </div>
        {{# layui.each(d.detail, function(index, item){ }}
        <div class="record-card">
            <div class="record-card-head clearfix">
                <div class="hdtitle">
                    {{# if((new Date().getMonth()+1)==item.month){ }}
                    本月
                    {{# }else{ }}
                    {{layui.util.toDateString(item.year, 'yyyy年MM月')}}
                    {{# } }}
                </div>
                <div class="extra"><span>收入 ¥{{item.month_income}}</span></div>
            </div>
            <div class="record-card-list">
                <ul class="list-items">
                    {{# layui.each(item.month_detail, function(index, items){ }}
                    <li>
                        <div class="items-con">
                            <div class="item-left">
                                <p>{{items.operation_name}}</p>
                                <p class="desc">{{items.operation_time}}</p>
                            </div>
                            <div class="item-right">
                                <div class="account">
                                    {{# if(items.operation_type==2||items.operation_type==4||items.operation_type==6||items.operation_type==7||items.operation_type==8){ }}
                                    +{{items.operation_num}}
                                    {{# }else if(items.operation_type==1||items.operation_type==5){ }}
                                    -{{items.operation_num}}
                                    {{# }else{ }}
                                    {{parseFloat(d.transfer_fee)}}*{{items.operation_num}}
                                    {{# } }}
                                </div>
                                <div class="desc">
                                    {{# if(items.operation_type==1){ }}
                                    兑换成功
                                    {{# }else if(items.operation_type==3){ }}
                                    交易成功
                                    {{# } }}
                                </div>
                            </div>
                        </div>
                    </li>
                    {{# }) }}
                    {{# if(item.month_detail.length === 0){ }}
                    <div class="list-notFound">暂无数据</div>
                    {{# } }} 
                </ul>
            </div>
        </div>
        {{# }) }}
    </script>
    </div>
<script type="text/javascript" src="/public/static/js/store/record.js"></script>
</body>
</html>