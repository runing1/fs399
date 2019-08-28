<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view/admin\modify_pwd.html";i:1558062480;s:98:"D:\PhpStudy20180211\PHPTutorial\WWW\TPshop\TPshop_V2.5.2\application\admin\view\public\layout.html";i:1558323298;}*/ ?>
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
<div class="ncap-form-default">
  <form id="admin_form" method="post" action="" name="admin_form">
    <input type="hidden" name="admin_id" value="<?php echo $info['admin_id']; ?>">
    <dl class="row">
      <dt class="tit"><label for="old_pw"><em>*</em>原密码</label><!-- 原密码 --></dt>
      <dd class="opt"><input id="old_pw" name="old_pw" class="txt valid" type="password">
          <span class="err"></span></dd>
    </dl>
    <dl class="row">
      <dt class="tit"><label for="new_pw"><em>*</em>新密码</label><!-- 新密码 --></dt>
      <dd class="opt"><input id="new_pw" name="new_pw" class="txt" type="password">
          <span class="err"></span></dd>
    </dl>
    <dl class="row">
      <dt class="tit"><label for="new_pw2"><em>*</em>确认密码</label><!-- 确认密码--></dt>
      <dd class="opt"><input id="new_pw2" name="new_pw2" class="txt" type="password">
          <span class="err"></span></dd>
    </dl>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><span>确认提交</span></a></div>
  </form>
</div>
<script type="text/javascript">
$(function () {
 
	$('#submitBtn').click(function(){
		$('#admin_form').submit();
	});
	
	$("#admin_form").validate({
		debug: false, //调试模式取消submit的默认提交功能   
		focusInvalid: false, //当为false时，验证无效时，没有焦点响应  
	    onkeyup: false,   
	    submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form   
	    	$.ajax({
				url:'/index.php?m=Admin&c=Admin&a=modify_pwd',
				type:'post',
				dataType:'json',
				data: $("#admin_form").serialize(),
				success:function(obj){
					  if(obj.status !=1){
						layer.alert(obj.msg, {icon: 3});
					}else{
						var dialog = DialogManager.get('modifypw');
						dialog.close();
						layer.alert('密码修改成功!', {icon: 3});
						window.location.reload();
					}  
				}
			});
	    },  
	    ignore:":button",	//不验证的元素
	    rules:{
	    	old_pw:{
	    		required:true,
	    		minlength:6
	    	},
	    	new_pw:{
	    		required:true,
	    		minlength:6
	    	},
	    	new_pw2:{
	    		required:true,
	    		minlength:6,
	    		equalTo: "#new_pw"
	    	}
	    },
	    messages:{
	    	old_pw:{
	    		required:"请输入原始密码",
	    		minlength:"原始密码长度不能少于6位"
	    	},
	    	new_pw:{
	    		required:"请输入新密码",
	    		minlength:"新密码长度不能少于6位"
	    	},
	    	new_pw2:{
	    		required:"请输入确认密码",
	    		minlength:"确认密码长度不能少于6位",
	    		equalTo:"两次密码输入不一致"
	    	}
	    }
	});
	
});

</script>