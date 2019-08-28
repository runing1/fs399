<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"./application/admin/view/operator\team_member.html";i:1566010445;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
</head>

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<!--<div id="append_parent"></div>-->
<div id="ajaxwaitid"></div>
<div class="page">

    <!-- 操作说明 -->
    <div id="explanation" class="explanation"
         style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>运营商下的团队成员</li>

        </ul>
    </div>

    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">

        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <table id="team_member" lay-filter="test" class="hide"></table>
            </div>
        </div>
    </div>
    <script src="/public/static/js/jquery.js"></script>
    <script src="/public/static/js/layui/layui.all.js"></script>
    <!--<script type="text/javascript" src="/public/static/js/admincp.js"></script>-->
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <!--<script type="text/html" id="handle">-->
    <!--<div class="handle">-->
    <!--<a href="javascript:;" style="line-height: 28px;" class="layui-btn layui-btn-danger"  lay-event="delete">删除</a>-->
    <!--</div>-->
    <!--</script>-->

</div>
<script>
    layui.use('table', function(){
        var table = layui.table;
       // var id="<?php echo $id; ?>";
        //第一个实例
        table.render({
            elem: '#team_member'
            ,cellMinWidth: 80
            ,url: "<?php echo U('Operator/team_member_handle',array('id'=>$id)); ?>"//数据接口
            ,method: 'post' //如果无需自定义HTTP类型，可不加该参数
            ,page: {theme:"#1E9FFF",prev: '<em>上一页</em>'
                ,next: '<em>下一页</em>'} //开启分页
            ,limit: 10
            ,cols: [[ //表头
                {field: 'username', title: '成员昵称',align: 'center'}
                ,{field: 'level', title: '星级',align: 'center'}
                ,{field: 'mobile', title: '联系电话',align: 'center' }
                ,{field: 'createtime', title: '加入日期',align: 'center',templet:'<div>{{ layui.util.toDateString(d.createtime*1000, "yyyy-MM-dd HH:mm") }}</div>'}
//                ,{fixed: 'right', title:'操作', toolbar: '#handle', width:150,align: 'center'}
            ]]
        });



    });
</script>


<script>




</script>
</body>
