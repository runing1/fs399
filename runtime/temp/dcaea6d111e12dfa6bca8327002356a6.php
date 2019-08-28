<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"./application/admin/view/operator\admin_info.html";i:1565661864;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer"
     style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i
                class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <?php if($act == 'add_child'): ?>
                    <h3><?php echo $operator_name; ?>--添加子运营商</h3>
                  <?php else: ?>
                        <h3>管理员 - 添加运营商</h3>
                <?php endif; ?>

                <h5>招1个运营商给予<?php echo $operator_fee_reward; ?>%的奖励</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="adminHandle" method="post">
        <input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
        <!--<input type="hidden" name="admin_id" value="<?php echo $info['admin_id']; ?>">-->
        <!--<input type="hidden" name="auth_code" value="<?php echo \think\Config::get('AUTH_CODE'); ?>"/>-->
        <?php if($act == 'add_child'): ?>
            <input type="hidden" name="id" value="<?php echo \think\Request::instance()->get('id'); ?>">
            <!--<input type="hidden" name="acturl" value="add_child_operator">-->
        <?php endif; ?>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>用户名</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="user_name" value="<?php echo $info['user_name']; ?>" id="user_name" maxlength="20"
                           class="input-txt">
                    <span class="err" id="err_user_name"></span>
                    <p class="notic">用户名</p>
                </dd>
            </dl>
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label for="email"><em>*</em>Email地址</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" name="email" value="<?php echo $info['email']; ?>" id="email" class="input-txt" maxlength="40">-->
            <!--<span class="err" id="err_email"></span><p class="notic">Email地址</p>-->
            <!--</dd>-->

            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>登陆密码</label>
                </dt>
                <dd class="opt">
                    <input type="password" name="password" maxlength="18" value="<?php echo $info['password']; ?>" id="password"
                           class="input-txt">
                    <p class="notic">登陆密码</p>
                </dd>

            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label for="bindapp"><em>*</em>绑定app端手机号</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="text" onblur="checkbindphone()" name="bindapp" maxlength="18" value="<?php echo $info['bindapp']; ?>" id="bindapp"-->
                           <!--class="input-txt">-->
                    <!--<span class="yes" id="yes"></span>-->
                    <!--<p class="notic">成为运营商需绑定平台app手机号</p>-->
                <!--</dd>-->

            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="company"><em>*</em>公司名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="company" id="company" maxlength="20" class="input-txt">
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="id_card"><em>*</em>身份证</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="id_card" value="<?php echo $info['id_card']; ?>" id="id_card" maxlength="20"
                           class="input-txt">
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="registered_capital"><em>*</em>注册资金</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="registered_capital" id="registered_capital" maxlength="20"
                           class="input-txt">
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="mobile"><em>*</em>联系电话</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="mobile" id="mobile" maxlength="20" class="input-txt">
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">申请区域：</dt>
                <dd class="opt">
                    <select onchange="get_city(this,0)" id="province" name="province_id">
                        <option value="0">选择省份</option>
                    </select>
                    <select onchange="get_area(this)" id="city" name="city_id">
                        <option value="0">选择城市</option>
                    </select>
                    <select id="district" name="district_id">
                        <option value="0">选择区域</option>
                    </select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="yys">申请区域等级</label>
                </dt>
                <dd class="opt">
                    <!--<input id="sex" name="sex" type="radio" value="0" checked>保密  &nbsp;&nbsp;&nbsp;&nbsp;-->
                    <label><input name="operator_type" type="radio" value="1" checked>县运营商</label>
                    <label><input name="operator_type" type="radio" value="2">股东</label>
                    <label><input name="operator_type" type="radio" value="3">核心股东</label>
                </dd>
            </dl>
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label for="articleForm">发布时间</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" class="input-txt" id="publish_time" name="publish_time"  value="<?php echo date("Y-m-d",$info['publish_time']); ?>">-->
            <!--<span class="add-on input-group-addon">-->
            <!--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>-->
            <!--</span>-->
            <!--<span class="err"></span>-->
            <!--</dd>-->
            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label>营业执照上传</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="<?php echo $info['thumb']; ?>">
                                <i id="img_i" class="fa fa-picture-o"
                                   onmouseover="layer.tips('<img src=<?php echo $info['business_license']; ?>>',this,{tips: [1, '#fff']});"
                                   onmouseout="layer.closeAll();"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="business_license" name="business_license" value="<?php echo $info['thumb']; ?>"
                                   class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','operator','img_call_back1')"
                                   size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>组织机构代码证副本</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
            <span class="show">
            <a id="img_organizational_code" target="_blank" class="nyroModal" rel="gal" href="<?php echo $info['thumb']; ?>">
            <i id="img_2" class="fa fa-picture-o"
               onmouseover="layer.tips('<img src=<?php echo $info['organizational_code']; ?>>',this,{tips: [1, '#fff']});"
               onmouseout="layer.closeAll();"></i>
            </a>
            </span>
                        <span class="type-file-box">
            <input type="text" id="organizational_code" name="organizational_code" value="<?php echo $info['organizational_code']; ?>"
                   class="type-file-text">
            <input type="button" name="button" id="button2" value="选择上传..." class="type-file-button">
            <input class="type-file-file" onClick="GetUploadify(1,'','operator','img_call_back2')"
                   size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label>法人代表身份证</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
            <span class="show">
            <a id="img_representative_card" target="_blank" class="nyroModal" rel="gal" href="<?php echo $info['representative_card']; ?>">
            <i id="img_3" class="fa fa-picture-o"
               onmouseover="layer.tips('<img src=<?php echo $info['representative_card']; ?>>',this,{tips: [1, '#fff']});"
               onmouseout="layer.closeAll();"></i>
            </a>
            </span>
                        <span class="type-file-box">
            <input type="text" id="representative_card" name="representative_card" value="<?php echo $info['representative_card']; ?>"
                   class="type-file-text">
            <input type="button" name="button" id="button3" value="选择上传..." class="type-file-button">
            <input class="type-file-file" onClick="GetUploadify(1,'','operator','img_call_back3')"
                   size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>

            <!--<dl class=>-->
                <!--<dt class="tit">-->
                    <!--<label for="payment_status"><em>*</em>缴费情况</label>-->
                <!--</dt>-->
                <!--<dd>-->
                    <!--<label><input name="payment_status[]" type="radio" value="" > 订金</label>-->
                    <!--<label><input name="payment_status[]" type="radio" value="" >补交 </label>-->
                    <!--<label><input name="payment_status[]" type="radio" value="" >尾款 </label>-->
                    <!--<label><input name="payment_status[]" type="radio" value="">全款 </label>-->
                <!--</dd>-->
                <!--<dd>-->
                    <!---->
                <!--</dd>-->
            <!--</dl>-->

            <dl class="row">
                <dt class="tit">
                    <label for="user_name">缴费金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="payment_amount" id="payment_amount" maxlength="20" class="input-txt">元
                    <span class="err" id="err_payment_amount"></span>
                </dd>

            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="remarks">备注</label>
                </dt>
                <dd class="opt">
                    <textarea name="remarks" style="width:456px; padding: 10px"></textarea>
                </dd>

            </dl>


            <div class="bot"><a href="JavaScript:void(0);" onclick="adsubmit();" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#publish_time').layDate();
        get_province();
    });
    function img_call_back1(fileurl_tmp) {
        $("#business_license").val(fileurl_tmp);
        $("#img_1").attr('href', fileurl_tmp);
        $("#img_1").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }
    function img_call_back2(fileurl_tmp) {
        $("#organizational_code").val(fileurl_tmp);
        $("#img_2").attr('href', fileurl_tmp);
        $("#img_2").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }
    function img_call_back3(fileurl_tmp) {
        $("#representative_card").val(fileurl_tmp);
        $("#img_3").attr('href', fileurl_tmp);
        $("#img_3").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }

    // 判断输入框是否为空
    function adsubmit() {
        $('.err').show();
        var password = $('#password').val();
        var user_name = $('#user_name').val();
        var id_card = $('#id_card').val();
        var mobile = $('#mobile').val();
        var act = $('#act').val();

        if (user_name == '') {
            layer.msg('用户名不能为空', {icon: 2, time: 1000});
            return false;
        }
        if ((password.length < 6 || password.length > 18) && act == 'add') {
            layer.msg('密码长度应该在6-18位！', {icon: 2, time: 1000});
            return false;
        }
        if (id_card == '') {
            layer.msg('身份证不能为空', {icon: 2, time: 1000});
            return false;
        }
//        if (bindApp = '') {
//            layer.msg('绑定手机号不能为空 ', {icon: 2, time: 1000});
//            return false;
//        }
        if (mobile = '') {
            layer.msg('手机号不能为空 ', {icon: 2, time: 1000});
            return false;
        }
        var s=isCardID(id_card);
        if(s==false){
            return false;
        }
        $.ajax({
            async: false,
            url: '/index.php?m=Admin&c=Operator&a=adminHandle&t=' + Math.random(),
            data: $('#adminHandle').serialize(),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.status != 1) {
                    layer.msg(data.msg, {icon: 2, time: 2000})
                    $.each(data.result, function (index, item) {
                        $('#err_' + index).text(item)
                    })
                } else {
                    layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = data.url;
                    })
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#error').html('<span class="error">网络失败，请刷新页面后重试!</span>');
            }
        });



    }



</script>
</body>
</html>