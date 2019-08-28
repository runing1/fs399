<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./application/admin/view/user\team.html";i:1566877967;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<link href="/public/static/css/myaccount.css" rel="stylesheet" type="text/css" />
<script src="/public/static/js/layer/laydate/laydate.js"></script>

<script type="text/javascript" src="/public/static/js/admincp.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>团队信息</h3>
                <h5></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation"
         style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>团队信息</li>
            <li>团队信息</li>
            <li>团队信息</li>
        </ul>
    </div>
<?php if($act_list != 'all'): ?>
    <div class="shopcard frozen-cha ma-to-20 p">
        <div class="cuschan">
            <span class="kycha"><i class="money"></i>可用余额</span><br>
            <span class="co"><em>￥</em><?php echo $user_money; ?></span>
        </div>
        <div class="cuschan">
            <span class="kycha"><i class="frozen"></i>冻结金额</span><br>
            <span class="co"><em>￥</em><?php echo $frozen_money_amount; ?></span>
        </div>
        <div class="cuschan">
            <span class="kycha"><i class="frozen"></i>今日收入</span><br>
            <span class="co"><em>￥</em><?php echo $today_amount; ?></span>
        </div>
        <div class="cuschan">
            <span class="kycha"><i class="frozen"></i>昨日收入</span><br>
            <span class="co"><em>￥</em><?php echo $yesterday_amount; ?></span>
        </div>
        <!--<div class="cuschan">-->
            <!--<span class="kycha"><i class="frozen"></i>总收入</span><br>-->
            <!--<span class="co"><em>￥</em><?php echo $total_amount; ?></span>-->
        <!--</div>-->
        <!--<div class="cuschan">-->
            <!--<span class="kycha"><i class="frozen"></i>总提现</span><br>-->
            <!--<span class="co"><em>￥</em><?php echo $total_withdrawal; ?></span>-->
        <!--</div>-->

    </div>
<?php endif; ?>

    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>团队信息列表</h3>
                <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><a href=""><i class="fa fa-refresh"></i></a></div>
            <form class="navbar-form form-inline" id="search-form" method="get" action="<?php echo U('team'); ?>">
                <!--<input type="hidden" name="create_time" id="create_time"  value="<?php echo $create_time; ?>">-->
                <div class="sDiv">
                    <div class="sDiv2" style="margin-right: 10px;">
                        <input type="text" size="30" name="start_time" id="start_time" value="" placeholder="起始时间"
                               class="qsbox">
                        <input type="button" class="btn" value="起始时间">
                    </div>
                    <div class="sDiv2" style="margin-right: 10px;">
                        <input type="text" size="30" name="end_time" id="end_time" value="" placeholder="截止时间"
                               class="qsbox">
                        <input type="button" class="btn" value="截止时间">
                    </div>
                    <div class="sDiv2" style="margin-right: 10px;border: none;">
                        <select id="status" name="type" class="form-control">
                            <option value="">状态</option>
                            <option value="1">提现</option>
                            <option value="2">缴纳管理费</option>
                            <option value="4">营利分红</option>
                        </select>
                    </div>
                    <!--<div class="sDiv2" style="margin-right: 10px;">-->
                    <!--<input size="30" id="user_id" name="user_id" value="<?php echo $_GET['user_id']; ?>" placeholder="用户ID"-->
                    <!--class="qsbox" type="text">-->
                    <!--</div>-->
                    <div class="sDiv2" style="margin-right: 10px;">
                        <input size="30" placeholder="用户昵称" value="" name="user_name" class="qsbox"
                               type="text">
                    </div>
                    <div class="sDiv2">
                        <!--<input size="30" value="<?php echo $_GET['bank_card']; ?>" name="bank_card" placeholder="收款账号" class="qsbox"-->
                        <!--type="text">-->
                        <!--<input class="btn" value="搜索" type="submit">-->
                        <input type="button" class="btn" onclick="ajax_get_table('search-form',1)" value="搜索">
                    </div>
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th align="center" abbr="article_title" axis="col3" class="">
                            <div style="text-align: center; width: 50px;" class="">
                                <input type="checkbox"
                                       onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                            </div>
                        </th>
                        <!--<th align="center" abbr="article_title" axis="col3" class="">-->
                        <!--<div style="text-align: center; width: 50px;" class="">申请ID</div>-->
                        <!--</th>-->
                        <!--<th align="center" abbr="ac_id" axis="col4" class="">-->
                        <!--<div style="text-align: center; width: 50px;" class="">用户id</div>-->
                        <!--</th>-->
                        <th align="center" abbr="article_show" axis="col5" class="">
                            <div style="text-align: center; width: 140px;" class="">昵称</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">星级</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">类型</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">联系电话</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">金额</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">日期</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 140px;" class="">奖励</div>
                        </th>
                        <?php if($act_list == 'all'): ?>
                            <th align="center" abbr="article_time" axis="col6" class="">
                                <div style="text-align: center; width: 80px;" class="">运营商名称</div>
                            </th>
                            <th align="center" abbr="article_time" axis="col6" class="">
                                <div style="text-align: center; width: 80px;" class="">运营商类型</div>
                            </th>
                        <?php endif; ?>
                        <th align="center" axis="col1" class="handle">
                            <div style="text-align: center; width: 400px;">操作</div>
                        </th>
                        <th style="width:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;" id="ajax_return">
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        // 表格行点击选中切换
        $('#flexigrid > table>tbody >tr').click(function () {
            $(this).toggleClass('trSelected');
        });
        // 点击刷新数据
        $('.fa-refresh').click(function () {
            location.href = location.href;
        });
        laydate.render({
            theme: "molv",
            type: "date",
            elem: "#start_time"
        })
        laydate.render({
            theme: "molv",
            type: "date",
            elem: "#end_time"
        })
        /* laydate.render({
         elem: '#start_time',
         theme: 'molv', //主题
         format: 'yyyy-MM-dd', //自定义格式
         calendar: true, //显示公历节日
         min: '1970-01-01', //最小日期
         max: '2099-12-31', //最大日期
         });
         laydate.render({
         elem: '#end_time',
         theme: 'molv', //主题
         format: 'yyyy-MM-dd', //自定义格式
         calendar: true, //显示公历节日
         min: '1970-01-01', //最小日期
         max: '2099-12-31', //最大日期
         });*/
        ajax_get_table('search-form', 1);
    });

    function check_form() {
        var start_time = $.trim($('#start_time').val());
        var end_time = $.trim($('#end_time').val());
        if (start_time == '' ^ end_time == '') {
            layer.alert('请选择完整的时间间隔', {icon: 2});
            return false;
        }
        if (start_time !== '' && end_time !== '') {
            $('#create_time').val(start_time + "," + end_time);
        }
        if (start_time == '' && end_time == '') {
            $('#create_time').val('');
        }

        return true;
    }

    //批量操作提交
    function act_submit(wst) {
        var chks = [];
        $('input[name*=selected]').each(function (i, o) {
            if ($(o).is(':checked')) {
                chks.push($(o).val());
            }
        })
        if (chks.length == 0) {
            layer.alert('少年，请至少选择一项', {icon: 2});
            return;
        }
        var can_post = false;
        var remark = "审核通过";
        if (wst != 1) {
            layer.prompt({title: '请填写备注(必填)', formType: 2}, function (text, index) {
                remark = text;
                audit(chks, wst, remark);
                layer.close(index);
            });
        } else {
            audit(chks, wst, remark);
        }
    }

    //    function audit(chks, wst, remark) {
    //        $.ajax({
    //            type: "POST",
    //            url: "/index.php?m=Admin&c=User&a=withdrawals_update",//+tab,
    //            data: {id: chks, status: wst, remark: remark},
    //            dataType: 'json',
    //            success: function (data) {
    //                if (data.status == 1) {
    //                    layer.alert(data.msg, {icon: 1, closeBtn: 0}, function () {
    //                        window.location.reload();
    //                    });
    //                } else {
    //                    layer.alert(data.msg, {icon: 2, time: 3000});
    //                }
    //            },
    //            error: function () {
    //                layer.alert('网络异常', {icon: 2, time: 3000});
    //            }
    //        });
    //    }

    // ajax 抓取页面
    function ajax_get_table(tab, page) {
        var search_key = $.trim($('#search_key').val());
        var search_type = $.trim($('#search_type').val());
        // alert($('#'+tab).serialize());
        if (search_key.length > 0) {
            if (search_type == 'search_key') {
                $('#account').val(search_key);
                $('#user_name').val('');
            } else {
                $('#user_name').val(search_key);
                $('#account').val('');
            }
        }
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type: "POST",
            url: "/index.php/Admin/user/ajaxteam/p/" + page,//+tab,
            data: $('#' + tab).serialize(),// 你的formid
            success: function (data) {
                $("#ajax_return").html('');
                $("#ajax_return").append(data);
            }
        });
    }


</script>
</body>
</html>