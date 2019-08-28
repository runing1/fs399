<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./application/admin/view/user\tuiguang.html";i:1566027131;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
    <link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
    <![endif]-->
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>

    <style type="text/css">html, body {
        overflow: visible;
    }</style>
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script>
    <!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script type="text/javascript" src="/public/static/js/admin.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="/public/static/js/common.js"></script>
    <script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <script src="/public/js/myFormValidate.js"></script>
    <script src="/public/js/myAjax2.js"></script>
    <script src="/public/js/global.js"></script>
    <script type="text/javascript">
        function delfunc(obj) {
            layer.confirm('确认删除？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        type: 'post',
                        url: $(obj).attr('data-url'),
                        data: {act: 'del', del_id: $(obj).attr('data-id')},
                        dataType: 'json',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1) {
                                layer.msg(data.msg, {icon: 1, time: 2000}, function () {
                                    location.href = '';
                               $(obj).parent().parent().parent().remove();
                                });
                            } else {
                                layer.msg(data, {icon: 2, time: 2000});
                            }
                        }
                    })
                }, function (index) {
                    layer.close(index);
                    return false;// 取消
                }
            );
        }

        function selectAll(name, obj) {
            $('input[name*=' + name + ']').prop('checked', $(obj).checked);
        }

        function get_help(obj) {

            window.open("http://www.tp-shop.cn/");
            return false;

            layer.open({
                type: 2,
                title: '帮助手册',
                shadeClose: true,
                shade: 0.3,
                area: ['70%', '80%'],
                content: $(obj).attr('data-url'),
            });
        }

        function delAll(obj, name) {
            var a = [];
            $('input[name*=' + name + ']').each(function (i, o) {
                if ($(o).is(':checked')) {
                    a.push($(o).val());
                }
            })
            if (a.length == 0) {
                layer.alert('请选择删除项', {icon: 2});
                return;
            }
            layer.confirm('确认删除？', {btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: 'get',
                        url: $(obj).attr('data-url'),
                        data: {act: 'del', del_id: a},
                        dataType: 'json',
                        success: function (data) {
                            layer.closeAll();
                            if (data == 1) {
                                layer.msg('操作成功', {icon: 1});
                                $('input[name*=' + name + ']').each(function (i, o) {
                                    if ($(o).is(':checked')) {
                                        $(o).parent().parent().remove();
                                    }
                                })
                            } else {
                                layer.msg(data, {icon: 2, time: 2000});
                            }
                        }
                    })
                }, function (index) {
                    layer.close(index);
                    return false;// 取消
                }
            );
        }

        /**
         * 全选
         * @param obj
         */
        function checkAllSign(obj) {
            $(obj).toggleClass('trSelected');
            if ($(obj).hasClass('trSelected')) {
                $('#flexigrid > table>tbody >tr').addClass('trSelected');
            } else {
                $('#flexigrid > table>tbody >tr').removeClass('trSelected');
            }
        }
        /**
         * 批量公共操作（删，改）
         * @returns {boolean}
         */
        function publicHandleAll(type) {
            var ids = '';
            $('#flexigrid .trSelected').each(function (i, o) {
//            ids.push($(o).data('id'));
                ids += $(o).data('id') + ',';
            });
            if (ids == '') {
                layer.msg('至少选择一项', {icon: 2, time: 2000});
                return false;
            }
            publicHandle(ids, type); //调用删除函数
        }
        /**
         * 公共操作（删，改）
         * @param type
         * @returns {boolean}
         */
        function publicHandle(ids, handle_type) {
            layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type: 'post',
                        data: {ids: ids, type: handle_type},
                        dataType: 'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1) {
                                layer.msg(data.msg, {icon: 1, time: 2000}, function () {
                                    location.href = data.url;
                                });
                            } else {
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
            );
        }
    </script>

</head>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>推广 - 扫码</h3>
                <h5>团队信息</h5>
            </div>
        </div>
    </div>
    <div id="container">


        <div class=" ">
            <!--<div class="page_topbar">-->

                <!--<div class="title">我的团队()</div>-->

            <!--</div>-->

            <div class="team_list_head_tj">
                <div class="">推荐链接（点击复制）</div>
                <button onclick="copyText()">复制</button>
                <p id="text" style="font-size: 16px;text-align: left;padding: 0px 15px;""><?php echo $tjurl; ?></p>
                <textarea id="input"></textarea>

            </div>

                <!--<div class="team_list_head_tj">-->
                    <!--<div class="">推荐链接（点击复制）</div>-->
                    <!--<div class="" id="copy">-->
                        <!--<div id="article" style="font-size: 16px;text-align: left;padding: 0px 15px;"><?php echo $tjurl; ?></div>-->
                    <!--</div>-->
                <!--</div>-->
                <div class="team_list_head_tj">

                    <div>推荐二维码</div>
                    <div class="img" style="text-align:center;">
                        <a href="<?php echo $tjimg; ?>"><img src="<?php echo $tjimg; ?>"/></a>

                    </div>
                </div>

        </div>
    </div>

</div>

</body>
<script type="text/javascript">
    function copyText() {
        var text = document.getElementById("text").innerText;
        var input = document.getElementById("input");
        input.value = text; // 修改文本框的内容
        input.select(); // 选中文本
        document.execCommand("copy"); // 执行浏览器复制命令
        alert("复制成功");
    }
</script>

<style type="text/css">

    /*.wrapper {position: relative;}*/
    #input {position: absolute;top: 0;left: 0;opacity: 0;z-index: -10;}
    .mui-active:active{color:#007aff}
    .mui-active:hover{color:#007aff}
    .mui-active:visited{color:#007aff}
    .mui-active:link{color:#007aff}

    .mui-pull-bottom-pocket {
        background: #ddd;
    }

    .mui-pull-caption {
        line-height: 20px;
        display: block;
    }

    .team_list_head {
        height: 46px;
        width: 100%;
        background: #fff;
        padding: 13px 3% 10px 3%;
        border-bottom: 1px solid #eaeaea;
    }

    .team_list_head .info {
        height: 20px;
        float: left;
        font-size: 14px;
        color: #666;
        line-height: 20px;
        text-align: left;
    }

    .team_list_head .num2 {
        height: 20px;
        float: right;
        text-align: right;
        font-size: 14px;
        color: #666;
        line-height: 20px;
        font-size: 14px;
        color: #989898;
    }

    .team_list {
        height: 104px;
        width: 100%;
        padding: 15px 3%;
        background: white;
        border-bottom: 1px solid #eaeaea;
    }

    .team_list .img {
        width: 16%;
        height: 40px;
        float: left;
        text-align: left;
    }

    .team_list .img img {
        height: 40px;
        width: 40px;
    }

    .team_list .info {
        height: 40px;
        width: 47%;
        float: left;
        font-size: 14px;
        color: #666;
        line-height: 25px;
        text-align: left;
    }

    .team_list .info span {
        font-size: 12px;
        color: #989898;
    }

    .team_list .num2 {
        height: 40px;
        width: 49%;
        float: right;
        text-align: right;
        font-size: 14px;
        color: #666;
        line-height: 20px;
        margin-right: 10px;
    }

    .team_list .num2 span {
        font-size: 12px;
        color: #989898;
    }

    .team_list_head_tj {
        height: auto;
        width: 100%;
        background: #fff;
        padding: 13px 3% 10px 3%;
        border-bottom: 1px solid #eaeaea;
        word-wrap: break-word;
        overflow: hidden;
        color: #989898;
    }

    .tjmcenter {
        text-align: center;
    }

    #pullrefresh {
        height: 50px;
    }
</style>
</html>