<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view/operator\detail.html";i:1566282483;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<style>
    td {
        height: 40px;
        line-height: 40px;
        padding-left: 20px;
    }

    .span_1 {
        float: left;
        margin-left: 0px;
        height: 130px;
        line-height: 130px;
    }

    .span_1 ul {
        list-style: none;
        padding: 0px;
    }

    .span_1 ul li {
        border: 1px solid #CCC;
        height: 40px;
        padding: 0px 10px;
        margin-left: -1px;
        margin-top: -1px;
        line-height: 40px;
    }
</style>
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
                <h3>会员管理 - 运营商信息</h3>
                <h5>网站系统会员管理会员信息</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="user_form" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>会员昵称</label>
                </dt>
                <dd class="opt">
                    <input class="input-txt valid" name="nickname" value="<?php echo $operator['user_name']; ?>" readonly=""
                           type="text">
                    <p class="notic">会员昵称不可修改。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员资产</label>
                </dt>
                <dd class="opt">
                    <strong class="red"></strong>&nbsp;余额 <em>￥</em><?php echo $operator['user_money']; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>店铺资金</label>
                </dt>
                <dd class="opt">
                    <strong class="red"></strong>&nbsp;余额 <em>￥</em><?php echo $operator['funds']; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="mobile"><em>*</em>身份证</label>
                </dt>
                <dd class="opt">
                    <input id="id_card" name="id_card" value="<?php echo $operator['id_card']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic">请输入运营商负责人的身份证号。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="mobile"><em>*</em>手机号码</label>
                </dt>
                <dd class="opt">
                    <input id="mobile" name="mobile" value="<?php echo $operator['mobile']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic">请输入常用的手机号码，将用来找回密码、接受订单通知等。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password">新密码</label>
                </dt>
                <dd class="opt">
                    <input id="password" name="password" class="input-txt" type="password">
                    <span class="err"></span>
                    <p class="notic">留空表示不修改密码</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password2">确认密码</label>
                </dt>
                <dd class="opt">
                    <input id="password2" name="password2" class="input-txt" type="password">
                    <span class="err"></span>
                    <p class="notic">留空表示不修改密码</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">所在地址：</dt>
                <dd class="opt">
                    <select onchange="get_city(this)" id="province" name="province_id">
                        <option value="0">选择省份</option>
                        <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['region_id']; ?>"
                            <?php if($operator['province_id'] == $vo['region_id']): ?>selected<?php endif; ?>
                            ><?php echo $vo['region_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select onchange="get_area(this)" id="city" name="city_id">
                        <option value="0">选择城市</option>
                        <?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['region_id']; ?>"
                            <?php if($operator['city_id'] == $vo['region_id']): ?>selected<?php endif; ?>
                            ><?php echo $vo['region_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="district" name="district_id">
                        <option value="0">选择区域</option>
                        <?php if(is_array($district) || $district instanceof \think\Collection || $district instanceof \think\Paginator): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['region_id']; ?>"
                            <?php if($operator['district_id'] == $vo['region_id']): ?>selected<?php endif; ?>
                            ><?php echo $vo['region_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="yys">运营商</label>
                </dt>
                <dd class="opt">
                    <!--<input id="sex" name="sex" type="radio" value="0" checked>保密  &nbsp;&nbsp;&nbsp;&nbsp;-->
                    <input name="operator_type" type="radio" value="1"
                    <?php if($operator['operator_type'] == 1): ?>checked<?php endif; ?>
                    >市运营商 &nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="operator_type" type="radio" value="2"
                    <?php if($operator['operator_type'] == 2): ?>checked<?php endif; ?>
                    >股东
                    <input name="operator_type" type="radio" value="3"
                    <?php if($operator['operator_type'] == 3): ?>checked<?php endif; ?>
                    >核心股东
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>营业执照上传</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_business_license" target="_blank" class="nyroModal" rel="gal"
                               href="<?php echo $operator['business_license']; ?>">
                                <i id="img_1" class="fa fa-picture-o"
                                   onmouseover="layer.tips('<img src=<?php echo $operator['business_license']; ?>>',this,{tips: [1, '#fff']});"
                                   onmouseout="layer.closeAll();"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="business_license" name="business_license"
                                   value="<?php echo $operator['business_license']; ?>"
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
            <a id="img_organizational_code" target="_blank" class="nyroModal" rel="gal" href="<?php echo $operator['organizational_code']; ?>">
            <i id="img_2" class="fa fa-picture-o"
               onmouseover="layer.tips('<img src=<?php echo $operator['organizational_code']; ?>>',this,{tips: [1, '#fff']});"
               onmouseout="layer.closeAll();"></i>
            </a>
            </span>
                        <span class="type-file-box">
            <input type="text" id="organizational_code" name="organizational_code" value="<?php echo $operator['organizational_code']; ?>"
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
            <a id="img_representative_card" target="_blank" class="nyroModal" rel="gal" href="<?php echo $operator['representative_card']; ?>">
            <i id="img_3" class="fa fa-picture-o"
               onmouseover="layer.tips('<img src=<?php echo $operator['representative_card']; ?>>',this,{tips: [1, '#fff']});"
               onmouseout="layer.closeAll();"></i>
            </a>
            </span>
                        <span class="type-file-box">
            <input type="text" name="representative_card" value="<?php echo $operator['representative_card']; ?>" class="type-file-text">
            <input type="button" name="button" id="button3" value="选择上传..." class="type-file-button">
            <input class="type-file-file" onClick="GetUploadify(1,'','operator','img_call_back3')"
                   size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="payment_amount"><em>*</em>缴费</label>
                </dt>
                <dd class="opt">
                    <input id="payment_amount" name="payment_amount" value="<?php echo $operator['payment_amount']; ?>"
                           class="input-txt" type="text">元
                    <span class="err"></span>
                    <p class="notic">运营商已缴费金额</p>
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
            <div class="bot"><a href="JavaScript:void(0);" onclick="checkUserUpdate();"
                                class="ncap-btn-big ncap-btn-green">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    //        $(document).ready(function(){
    //            get_province();
    //        });
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
        alert(fileurl_tmp);
        $("input[name='representative_card']").val(fileurl_tmp);
        $("#img_3").attr('href', fileurl_tmp);
        $("#img_3").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }
    function checkUserUpdate() {
        // var email = $('input[name="email"]').val();
        var mobile = $('input[name="mobile"]').val();
        var password = $('input[name="password"]').val();
        var password2 = $('input[name="password2"]').val();
        var id_card = $('input[name="id_card"]').val();

        var error = '';
        if (password != password2) {
            error += "两次密码不一样\n";
        }
        if (!checkMobile(mobile) && mobile != '') {
            error += "手机号码填写有误\n";
        }
        var s=isCardID(id_card);
        if(s==false){
            error += "身份证号格式错误\n";
           // return false;
        }
        if (error) {
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        $('#user_form').submit();
    }
</script>
</body>
</html>