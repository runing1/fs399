<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"./application/admin/view/operator\operator_child.html";i:1564537170;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<script src="/public/static/js/layer/laydate/laydate.js"></script>

<script type="text/javascript" src="/public/static/js/admincp.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
<style>
    .shopcard {
        overflow: hidden;
        padding: 20px 20px;
        border: 1px solid #dadada;
    }

    .ma-to-20 {
        margin-top: 20px;
    }

    .frozen-cha .cuschan {
        width: 25%;
    }

    .shopcard .cuschan {
        float: left;
    / / width: 50 %;
        text-align: center;
    }

    .shopcard .cuschan span {
        font-size: 14px;
        text-align: right;
    }

    .money, .frozen {
        background: url(../images/money.png) no-repeat;
        width: 41px;
        height: 36px;
        display: inline-block;
        margin-right: 12px;
        vertical-align: middle;
    }
    .shopcard .cuschan .co {
        margin-left: 37px;
        color: #e23435;
    }
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $operator_name; ?>--团队成员</h3>
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

    <div class="flexigrid">
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
                        <th align="center" abbr="article_show" axis="col5" class="">
                            <div style="text-align: center; width: 200px;" class="">成员昵称</div>
                        </th>
                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 200px;" class="">星级</div>
                        </th>

                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 200px;" class="">联系电话</div>
                        </th>

                        <th align="center" abbr="article_time" axis="col6" class="">
                            <div style="text-align: center; width: 200px;" class="">加入日期</div>
                        </th>

                        <th style="8:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody>
                    <!--<volist name="userList" id="list">-->
                    <?php if(empty($team_list) == true): ?>
                        <tr data-id="0">
                            <td class="no-data-operatorchild" align="center" axis="col0" colspan="50">
                                <i class="fa fa-exclamation-circle"></i>该团队下暂时没有记录
                            </td>
                        </tr>
                        <?php else: if(is_array($team_list) || $team_list instanceof \think\Collection || $team_list instanceof \think\Paginator): $i = 0; $__LIST__ = $team_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                            <tr data-id="<?php echo $list['user_id']; ?>">
                                <!--<td class="sign">-->
                                    <!--<div style="width: 24px;"><i class="ico-check"></i></div>-->
                                <!--</td>-->
                                <td class="" align="center">
                                    <label>
                                        <div style="text-align: center; width: 50px;">
                                            <input type="checkbox" name="selected[]" value="1">
                                        </div>
                                    </label>
                                </td>
                                <td align="left" class="">
                                    <div style="text-align: center; width: 200px;"><?php echo $list['username']; ?></div>
                                </td>
                                <td align="center" class="">
                                    <div style="text-align: center; width: 200px;"><?php echo $list['level']; ?></div>
                                </td>
                                <td align="left" class="">
                                    <div style="text-align: center; width: 200px;"><?php echo $list['mobile']; if(($list['mobile_validated'] == 0) AND ($list['mobile'])): ?>
                                            (未验证)
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td align="left" class="">
                                    <div style="text-align: center; width: 200px;"><?php echo date("Y-m-d
                                        H:i:s",$list['createtime']); ?>
                                    </div>
                                </td>

                                <td align="" class="" style="width: 100%;">
                                    <div>&nbsp;</div>
                                </td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
            </div>
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


</script>
</body>
</html>