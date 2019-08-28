<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:47:"./application/admin/view/store\goodsManage.html";i:1564211355;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>商品管理</title>
    <link rel="stylesheet" type="text/css" href="/public/static/js/layui/css/layui.css" />
    <link rel="stylesheet" type="text/css" href="/public/static/css/shoplist.css" />
    <script src="/public/static/js/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/public/static/js/store/clipboard.min.js"></script>
</head>
<body>
    <div class="mainContent">
        <div class="title">
            <h1>商品管理</h1>
        </div>
        <div class="tabmenu shoptab">
            <ul class="tab">
                <li class="active"><a href="javascript:;" data-value="onlin">销售商品</a></li>
                <li><a href="javascript:;" data-value="uncheck">审核中</a></li>
                <li><a href="javascript:;" data-value="obtained">下架</a></li>
                <li><a href="javascript:;" data-value="reject">未通过</a></li>
            </ul>
        </div>
        <div class="shop-head clearfix">
            <div class="btn-info">
                <a href="javascript:;" class="sc-btn sc-blue-btn btn-see" data-type="add">添加新商品</a>
                <a href="javascript:;" class="sc-btn sc-blue-btn btn-see" data-type="batchdel">删除</a>
                <a href="javascript:;" class="sc-btn sc-blue-btn btn-see" data-type="on_sale" style="display: none;">上架</a>
                <a href="javascript:;" class="sc-btn sc-blue-btn btn-see" data-type="not_on_sale">下架</a>
                <a href="javascript:;" class="sc-btn sc-blue-btn btn-see" data-type="reaudit" style="display: none;">重新审核</a>
            </div>
            <div class="btn-search">
                <input type="text" class="text" name="goods_name" value="" placeholder="请输入商品名称" autocomplete="off" />
                <button class="btn-see submit" data-type="getSearch">搜索</button>
            </div>
        </div>
        <div class="shop-content">
            <div class="flexgrid">
                <div class="tabitem">
                    <table id="goods-list" lay-filter="goods-list"></table>
                </div>
                <div class="tabitem">
                    <table id="goods-list2" lay-filter="goods-list2"></table>
                </div>
                <div class="tabitem">
                    <table id="goods-list3" lay-filter="goods-list3"></table>
                </div>
                <div class="tabitem">
                    <table id="goods-list4" lay-filter="goods-list4"></table>
                </div>
                <script type="text/html" id="goods_tag">
                    <div class="tlist">
                        <div class="titem">
                            <span>上架：</span>
                            {{# if(d.goods_status == 'onlin'){ }}
                            <div class="switch active" title="是" onclick="listTableSwitch(this,'obtained',{{d.goods_id}})">
                                <div class="circle"></div>
                            </div>
                            <input type="hidden" name="goods_status" value="1">
                            {{# }else{ }}
                            <div class="switch" title="否" onclick="listTableSwitch(this,'onlin',{{d.goods_id}})">
                                <div class="circle"></div>
                            </div>
                            <input type="hidden" name="goods_status" value="0">
                            {{# } }}
                        </div>
                    </div>
                </script>
                <script type="text/html" id="goods_info">
                    <div class="goods_info clearfix">
                        <div class="img"><img src="{{d.goods_thumb}}" width="68" height="68"></div>
                        <div class="desc">
                            <p>{{d.goods_name}}</p>
                            <p class="brief">{{d.goods_brief == null ? '' : d.goods_brief}}</p>
                        </div>
                        <i class="icon-tj{{d.is_recommend == 1 ? '' : ' hides'}}">推</i>
                    </div>
                </script>
                <script type="text/html" id="goods_status">
                    {{# if(d.goods_status == 'onlin'){ }}
                    <label style="color:#e74c3c;">已上架</label>
                    {{# } else if(d.goods_status == 'obtained'){ }}
                    <label style="color:#FF5722;">已下架</label>
                    {{# } else if(d.goods_status == 'uncheck'){ }}
                    <label style="color:#fc0000;">审核中</label>
                    {{# } else{ }}
                    <label style="color:#2F4056;">未通过</label>
                    {{# } }}
                </script>
                <script type="text/html" id="handle">
                    <div class="handle">
                        <a href="javascript:;" class="btn-operation" data-clipboard-text="http://www.fs399.cn{{d.goods_url}}">链接</a>
                        {{# if(d.is_recommend == '0'){ }}
                        <a href="javascript:;" class="btn-see btn-booth" lay-event="booth" data-value="1">设为展位</a>
                        {{# } else{ }}
                        <a href="javascript:;" class="btn-see btn-booth" lay-event="booth" data-value="0">取消展位</a>
                        {{# } }}
                        <a href="javascript:;" class="btn-editone" onclick="goodsEdit('{{d.goods_id}}')">编辑</a>
                        <a href="javascript:;" class="btn-delone" onclick="goodsDel(this,'{{d.goods_id}}')">删除</a>
                    </div>
                </script>
                <script type="text/html" id="handle2">
                    <div class="handle">
                        <a href="javascript:;" class="btn-see btn-auditone" lay-event="audits">重新审核</a>
                        <a href="javascript:;" class="btn-editone" onclick="goodsEdit('{{d.goods_id}}')">编辑</a>
                        <a href="javascript:;" class="btn-delone" onclick="goodsDel(this,'{{d.goods_id}}')">删除</a>
                    </div>
                </script>
                <script type="text/html" id="handle3">
                    <div class="handle">
                        <a href="javascript:;" class="btn-operation" data-clipboard-text="http://www.fs399.cn{{d.goods_url}}">链接</a>
                        <a href="javascript:;" class="btn-editone" onclick="goodsEdit('{{d.goods_id}}')">编辑</a>
                        <a href="javascript:;" class="btn-delone" onclick="goodsDel(this,'{{d.goods_id}}')">删除</a>
                    </div>
                </script>
                <script type="text/html" id="xuhao">
                    {{d.LAY_TABLE_INDEX+1}}
                </script>
                <input type="hidden" name="filter" />
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/public/static/js/store/shoplist.js"></script>
</body>
</html>