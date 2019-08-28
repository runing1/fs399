<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"./application/admin/view/withdraw\withdrawal_pwd.html";i:1564651914;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>安全设置</title>
    <link rel="stylesheet" type="text/css" href="/public/static/css/tpshop.css"/>
    <link rel="stylesheet" type="text/css" href="/public/static/css/myaccount.css"/>
    <script src="/public/static/js/jquery.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script>
</head>
<body class="bg-f5">
<div class="home-index-middle">
    <div class="w1224">
        <div class="home-main">
            <div class="ri-menu fr">
                <div class="menumain">
                    <div class="goodpiece">
                        <h1>安全设置</h1>
                        <!--<a href=""><span class="co_blue">帮助</span></a>-->
                    </div>
                    <div class="accouun"></div>
                    <div class="thirset ma-to-20">
                        <div class="wshef <?php if($step == 1): ?>yellc<?php endif; ?>">1.验证身份<i class="spassw"></i></div>
                        <div class="wshef <?php if($step == 2): ?>yellc<?php endif; ?>">2.设置支付密码<i class="spassw"></i></div>
                        <div class="wshef <?php if($step == 3): ?>yellc<?php endif; ?>">3.完成</div>
                    </div>
                        <?php if($step == 1): ?>
                        <div class="personerinfro verifyi">
                            <form action="" method="post">

                                <ul class="name_jz ischecked">

                                        <li class="infor_wi_le"><a href="javascript:void(0);">已验证手机号码：</a></li>
                                        <li><a href="javascript:void(0);" class="sender" style="color:#333333;"><?php echo $admin_operator['mobile']; ?></a></li>

                                </ul>
                                <ul class="name_jz checode">
                                    <li class="infor_wi_le"><a href="javascript:void(0);">验证码：</a></li>
                                    <li class="teaeu">
                                        <a href="javascript:void(0);">
                                            <input class="name_zjxs" type="text" name="tpcode" id="tpcode" value="">
                                        </a>
                                        <a href="javascript:void(0);">
                                            <input class="button_yzm" type="button" name="" onclick="sendcode(this)" value="获取验证码" />
                                        </a>
                                    </li>
                                </ul>
                                <ul class="hobby_jz">
                                    <li class="infor_wi_le"></li>
                                    <div class="save_s">
                                        <input class="save" type="button" id="" onclick="nextstep()" name="" value="下一步">
                                    </div>
                                </ul>
                            </form>

                        </div>
                        <?php endif; if($step == 2): ?>
                        <div class="personerinfro verifyi-next">
                            <form action="" method="post" id="pwdform">
                                <ul class="name_jz">
                                    <li class="infor_wi_le"><a href="javascript:void(0);">设置提现密码：</a></li>
                                    <li class="teaeu">
                                        <a href="javascript:void(0);">
                                            <input class="name_zjxs" type="password" name="new_password" id="new_password" value=""placeholder="6-16位字母、数字或符号组合" onkeyup="securityLevel(this.value)">
                                            <i class="qrzf"></i>
                                        </a>
                                        <a class="safebil" href="javascript:void(0);">
                                            <span>安全程度：</span>
                                            <span class="lowzg red">低</span>
                                            <span class="lowzg">中</span>
                                            <span class="lowzg">高</span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="name_jz">
                                    <li class="infor_wi_le"><a href="javascript:void(0);">确认提现密码：</a></li>
                                    <li class="teaeu">
                                        <a href="javascript:void(0);">
                                            <input class="name_zjxs" type="password" name="confirm_password" id="confirm_password" value=""placeholder="6-16位字母、数字或符号组合">
                                            <i class="qrzf"></i>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="hobby_jz">
                                    <li class="infor_wi_le"></li>
                                    <div class="save_s">
                                        <input type="hidden" name="step" value="3">
                                        <input class="save" type="button" onclick="checkSubmit()" value="下一步">
                                    </div>
                                </ul>
                            </form>

                        </div>
                    <?php endif; if($step == 3): ?>
                        <div class="oversuccen">
                            <div class="zaiebox">
                                <div class="fljair">
                                    <img src="../../../../public/static/images/flj.png"/>
                                </div>
                                <div class="fljfon">
                                    <p>提现密码设置成功</p>
                                    <p>请牢记您设置的支付密码。</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    //显示密码安全等级
    function securityLevel(sValue) {
        var modes = 0;
        //正则表达式验证符合要求的
        if (sValue.length < 6 ) return modes;
        if (/\d/.test(sValue)) modes++; //数字
        if (/[a-z]/.test(sValue)) modes++; //小写
        if (/[A-Z]/.test(sValue)) modes++; //大写
        if (/\W/.test(sValue)) modes++; //特殊字符
        $('.lowzg').eq(modes-1).addClass('red').siblings('.lowzg').removeClass('red');
    };
    function modify_sender(obj){
        $('.ischecked .infor_wi_le').children().html('已验证'+$(obj).val());
        $('.sender').html($(obj).find("option:selected").attr('rel'));
    }
    function sendcode(o){
        var mobile="<?php echo $admin_operator['mobile']; ?>";
        var work=1;
        var role=4;
        $.ajax({
            //url:'/index.php?m=Home&c=Api&a=send_validate_code&scene=6&t='+Math.random(),
            url:'<?php echo U("Admin/Api/sendSms"); ?>',
            type:'post',
            dataType:'json',
            data:{mobile:mobile,work:work,role:role},
            success:function(res){
                if(res.status==1){
                    layer.alert(res.msg, {icon: 1});
                    timer(o);
                }else{
                    layer.alert(res.msg, {icon: 2});
                }
            }
        })
    }

    var wait=60;
    function timer(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value="获取验证码";
           // wait = <?php echo (isset($tpshop_config['sms_sms_time_out']) && ($tpshop_config['sms_sms_time_out'] !== '')?$tpshop_config['sms_sms_time_out']:60); ?>;
            wait=60;
        } else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                timer(o)
            }, 1000)
        }
    }

    function nextstep(){
        var tpcode = $('#tpcode').val();
        var mobile="<?php echo $admin_operator['mobile']; ?>";
      //  alert(tpcode);
        if(tpcode == ''){
            layer.alert('验证码不能为空', {icon: 2});
            return false;
        }
        if(tpcode.length != 6){
            layer.alert('验证码错误', {icon: 2});
            return false;
        }
        $.ajax({
            //url:'/index.php?m=Home&c=Api&a=check_validate_code&t='+Math.random(),
            url:'<?php echo U("Admin/User/check_validate_code"); ?>',
            type:'post',
            dataType:'json',
            data:{mobile:mobile,code:tpcode},
            success:function(res){
                if(res.status==1){
                    //is_check = true;
                    window.location.href='/index.php?m=Admin&c=Withdraw&a=withdrawal_pwd&step=2&t='+Math.random();
                }else{
                    layer.alert(res.msg, {icon: 2});
                    return false;
                }
            }
        })
    }

    function checkSubmit(){
        var new_password = $('#new_password').val();
        var confirm_password = $('#confirm_password').val();
        if(new_password == ''){
            layer.alert('新支付密码不能为空', {icon: 2});
            return false;
        }
        if(new_password.length<6 || new_password.length>18){
            layer.alert('密码长度不符合规范', {icon: 2});
            return false;
        }
        if(new_password != confirm_password){
            layer.alert('两次密码不一致', {icon: 2});
            return false;
        }
        $('#pwdform').submit();
    }
</script>
</html>