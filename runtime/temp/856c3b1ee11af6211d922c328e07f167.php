<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"./application/admin/view/withdraw\storeWithdrawal.html";i:1566467417;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>申请提现</title>
    <link rel="stylesheet" type="text/css" href="/public/static/css/base.css"/>
    <link rel="stylesheet" type="text/css" href="/public/static/css/tpshop.css"/>
    <link rel="stylesheet" type="text/css" href="/public/static/css/myaccount.css"/>
    <link rel="stylesheet" type="text/css" href="/public/static/css/examine.css"/>
    <link rel="stylesheet" type="text/css" href="/public/static/js/layui/css/layui.css"/>
    <link rel="shortcut  icon" type="image/x-icon"
          href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>"
          media="screen"/>
    <script src="/public/static/js/jquery.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/public/static/js/layui/layui.all.js"></script>
    <script type="text/javascript" src="/public/static/js/layer/layer.js"></script>
    <!-- 弹窗js 参考文档 http://layer.layui.com/-->
</head>
<div class="bg-f5">
    <div class="home-index-middle">
        <div class="w1224">
            <div class="home-main">
                <div class="ri-menu fr">
                    <div class="menumain p">
                        <div class="goodpiece border-bottom-1">
                            <h1>店铺提现申请</h1>
                            <!--<a href=""><span class="co_blue">账户余额说明</span></a>-->
                            <!--<input type="hidden" id="openid" value="<?php echo $user['openid']; ?>">-->
                        </div>

                        <div class="personerinfro tixbox">
                            <form action="" method="post" id="returnform">
                                <!--选择提现方式切换 s-->
                                <div class="withdraw-select withdraw-select-tx">
                                    <p class="withdraw-select-p">选择提现方式</p>
                                    <dl class="withdraw-select-dl p">
                                        <dd class="fl withdraw-select-dl-color">
                                            <label class="withdraw-select-labels">
                                            </label>
                                            <b class="withdrawimg1"><img src="/public/images/tx-zhifb.png"/></b>
                                            <span>支付宝</span>
                                        </dd>
                                        <dd class="fl ">
                                            <label for="female3">
                                            </label>
                                            <b class="withdrawimg3"><img src="/public/images/tx-yinlian.png"/></b>
                                            <span>银行卡</span>
                                        </dd>
                                    </dl>
                                </div>
                                <!--选择提现方式切换 e-->
                                <!--支付宝提现 模块s-->
                                <div class="withdraw-cont-wrap">
                                    <!--已绑定 s-->
                                    <div class="withdraw-Bindings p" id="ali_1" <?php if(!$user['ali_account']): ?> style="display: none;"<?php endif; ?> >
                                    <div class="Bindings-img fl">
                                        <img src="/public/images/tx-zhifb.png"/>
                                    </div>
                                    <div class="Bindings-name fl">
                                        <div class="Bindings-hone" id="ali_card"><?php echo $user['ali_account']; ?></div>
                                        <div class="Bindings-dev"><?php echo $user['ali_username']; ?></div>
                                    </div>
                                    <div class="Bindings-edit fl">
                                        <i></i>修改
                                    </div>
                                </div>
                                <!--已绑定 e-->

                                <!--未绑定 s-->
                                <div class="withdraw-Bindings p" id="ali_0" <?php if($user['ali_account']): ?> style="display:none;"<?php endif; ?> >
                                <div class="Be_careful fl">注意：</div>
                                <div class="Bindings-img fl">
                                    <img src="/public/images/tx-zhifb.png"/>
                                </div>
                                <div class="Bindings-none-cont fl" id="bind_zfb">
                                    你还未绑定支付宝，<a href="javascript:void(0);">立即绑定</a>
                                </div>
                        </div>
                        <!--未绑定 e-->
                    </div>
                </div>
                <!--银行卡提现  模块  s-->
                <div class="withdraw-cont-wrap">
                    <!--已绑定 s-->
                    <div class="withdraw-Bindings p" id="bank_1" <?php if(!$user['bank_card']): ?> style="display:none;"<?php endif; ?> >

                    <div class="Bindings-img Bindings-img-yl-bank fl">
                        <img id="img_bank"
                             src="https://i.alipayobjects.com/combo.png?d=cashier&t=<?php echo $user['bank_code']; ?>_s"/>
                    </div>
                    <div class="Bindings-name fl">
                        <div class="Bindings-bankcard" id="bank_card"><?php echo $user['bank_card']; ?></div>
                        <div class="Bindings-dev-bankuname" id="bank_bname"><?php echo $user['bank_name']; ?></div>
                        <div class="Bindings-dev-bankusername" id="bank_username"><?php echo $user['bank_username']; ?></div>

                    </div>
                    <div class="Bindings-edit fl">
                        <i></i>修改
                    </div>
                </div>
                <!--已绑定 e-->
                <!--未绑定 s-->
                <div class="withdraw-Bindings p " id="bank_0" <?php if($user['bank_card']): ?> style="display:none;"<?php endif; ?> >
                <div class="Be_careful fl">注意：</div>
                <div class="Bindings-img  Bindings-img-yl  fl">
                    <img src="/public/images/tx-yinlian.png"/>
                </div>
                <div class="Bindings-none-cont fl" id="bind_bank">
                    你还未绑定银行卡，<a href="javascript:void(0);">立即绑定</a>
                </div>
            </div>

        </div>
        <!--未绑定 e-->



        <!--银行卡 模块  d-->
        <!--可提现金额s-->
        <div class="withdraw-Amounts">
            <p class="Amounts-p">可提现金额：<em>￥<?php if($user['funds'] != 0): ?><?php echo $user['funds']; else: ?>0.00<?php endif; ?></em></p>
            <div class="withdraw-Amounts-input">
                <i>￥</i>
                <ul>
                    <li class="Amounts-li-tx p">
                        <input class="Amounts-input fl" type="text" placeholder="<?php echo $user['funds']; ?>" name="money"
                               id="money" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"
                               onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onblur="get_service();"/>
                        <input id="all_cash" type="button" class="Amounts-btn fr" value="全部提现">
                        <input type="hidden" name="taxfee" value="" id="taxfee">
                        <input type="hidden" name="user_money" value="<?php echo $user['funds']; ?>" id="user_money">
                    </li>
                    <li class="Amounts-li-box p"
                    <?php if($operator_config['cash_open'] != '1'): ?>style="display: none;"<?php endif; ?>
                    >
                    <span class="fl">手续费：<span id="sxf"></span>元</span>
                    <!--<em class="fr">金额限制<?php echo $cash_config['min_cash']; ?>-<?php echo $cash_config['max_cash']; ?>元</em>-->
                    </li>
                </ul>
            </div>
        </div>
        <!--可提现金额e-->
        <!--支付密码 s-->
        <div class="gdw-syt-zfmm simple-password-wrap">
            <p>请输入6位数字支付密码</p>
            <input id="simplePasswordInput" autocomplete="off" disableautocomplete="disableautocomplete"
                   class="simple-password-input" name="paypwd" maxlength="6" type="text" data-busy="0" type="hidden">
            <div class="facade-wrap clearfix">
                <ul class="facade" id="simplePassword" autocomplete="off" maxlength="6">
                    <li class="facade-item">
                        <i class="current"></i>
                    </li>
                    <li class="facade-item">
                        <i class="current"></i>
                    </li>
                    <li class="facade-item">
                        <i class="current"></i>
                    </li>
                    <li class="facade-item">
                        <i class="current"></i>
                    </li>
                    <li class="facade-item ">
                        <i class="current"></i>
                    </li>
                    <li class="facade-item">
                        <i class="current"></i>
                    </li>
                </ul>
                <a href="javascript:void(0)" data-url="<?php echo U('admin/withdraw/withdrawal_pwd'); ?>" onclick="set_pwd(this)">前往设置或修改支付密码</a>
                <div class="err-msg">请输入6位数字支付密码</div>
            </div>
        </div>

        <div>
            <p style="margin-top: 20px;line-height: 45px;font-size: 16px;">提现备注:</p>
            <textarea name="user_remark" style="width:456px; padding: 10px"></textarea>
        </div>
        <!--支付密码 e-->

        <ul class="hobby_jz">
            <li class="infor_wi_ri">
                <div class="save_s" style="margin-top: 0;">
                    <!--<input class="save closoff " style="border: 1px solid #dadde0; background: #f5f5f5; width:80px;height:30px;border-radius:6px; margin-top: 10px; margin-right: 20px;" type="reset" onclick="location.href='<?php echo U('User/recharge'); ?>'"-->
                    <!--value="取消并返回"/>-->
                    <input class="save" type="button"
                           style="border: 1px solid #019eef ;background: #4fc0e8; width:80px;height:30px;border-radius:6px;color:white; margin-top: 15px;"
                           id="save_data" value="提交申请"/>
                </div>
            </li>
        </ul>

        <input type="hidden" name="bank_name" id="bank_name" value="zfb">
        <input type="hidden" name="cards" id="cards" value="<?php echo $user['ali_account']; ?>">
        <input type="hidden" name="realname" id="realname" value="<?php echo $user['ali_username']; ?>">
        </form>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>


<script type="text/javascript">

    function set_pwd(obj){
        layer.open({
            type: 2,
            title: "支付设置",
            content: $(obj).data('url'),
            area: ["1250px", "650px"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
        });
    }



    var cash_type = 0;//选择的体现方式,默认为支付宝
    var service_ratio = '<?php echo $operator_config['operator_withdrawals']; ?>';
    var min_cash = '<?php echo $operator_config['min_cash']; ?>';
    var max_cash = '<?php echo $cash_config['max_cash']; ?>';
    var min_service_money = '<?php echo $cash_config['min_service_money']; ?>';
    var max_service_money = '<?php echo $cash_config['max_service_money']; ?>';
    var cash_open = '<?php echo $operator_config['cash_open']; ?>';

    //选择提现方式
    $(".withdraw-cont-wrap").eq(0).show();
    $(".withdraw-select-dl dd").click(function () {
        var j = $(this).index();

        var realname = $('.Bindings-dev').html();

        var bankusername = $('.Bindings-dev-bankusername').html();

        var cash_alipay = $('.Bindings-hone').html();

        var cash_bank_card = $('.Bindings-bankcard').html();

        if (j == 0) {
            $('#bank_name').val('zfb');
            if (cash_alipay) {
                $('#ali_0').css("display", "none");
                $('#ali_1').find('.Bindings-dev').html(realname);
                $('#ali_1').find('.Bindings-hone').html(cash_alipay);
                $('#cards').val(cash_alipay);
                $('#realname').val(realname);
                $('#ali_1').css("display", "block");
            } else {
                $('#pop_card').val('');
                $('#cards').val('');
                $('#ali_0').css("display", "block");
                $('#ali_1').css("display", "none");
            }

        }
        if (j == 1) {
            $('#bank_name').val('bank');
            if (cash_bank_card) {
                $('#bank_0').css("display", "none");
                $('#bank_1').find('.Bindings-dev-bankusername').html(bankusername);
                $('#bank_1').find('.Bindings-bankcard').html(cash_bank_card);
                $('#cards').val(cash_bank_card);
                $('#realname').val(bankusername);
                // $('#pop_bankcard').val(cash_bank_card);
                $('#bank_1').css("display", "block");
            } else {
                $('#pop_card').val('');
                $('#cards').val('');
                $('#bank_0').css("display", "block");
                $('#bank_1').css("display", "none");
            }

        }
        cash_type = j;

        $(".withdraw-select-dl dd").removeClass("withdraw-select-dl-color").children("label").removeClass("withdraw-select-labels");
        $(this).addClass("withdraw-select-dl-color").children("label").addClass("withdraw-select-labels");
        var index = $(this).index();
        $(".withdraw-cont-wrap").hide();
        $(".withdraw-cont-wrap").eq(index).show();
    })
</script>
<!--绑定新账号弹窗 s-->
<div class="z-bind-bg">
</div>
<div class="z-bind-pop">
    <form>
        <div class="z-bind-head">
            <i class="z-bind-cosle"></i>
            <h5>绑定支付宝</h5>
        </div>
        <div class="z-bind-li">
            <ul>
                <li class="p">
							<span class="fl" id="pop_title">
								支付宝账号
							</span>
                    <div class="z-bind-text fl">
                        <input type="text" id="pop_card" value="" placeholder="请填写账号"/>
                        <label></label>
                    </div>
                </li>
                <li class="p">
							<span class="fl">
								真实姓名
							</span>
                    <div class="z-bind-text fl">
                        <input type="text" id="pop_name" value="" placeholder="请填写真实姓名"/>
                        <label></label>
                    </div>
                </li>
            </ul>
        </div>
        <div class="z-bind-btn p">
            <div class="z-bind-qx fl">
                取消
            </div>
            <div class="z-bind-qd fr" id="add_card">
                <input type="button"  value="确定"/>
                <label></label>
            </div>
        </div>
    </form>
</div>


<!--绑定新账号银行弹窗 s-->
<div class="z-bind-bg-bank">
</div>
<div class="z-bind-pop-bank">
    <form>
        <div class="z-bind-head">
            <i class="z-bind-cosle-bank"></i>
            <h5>绑定银行卡</h5>
        </div>
        <div class="z-bind-li">
            <ul>
                <li class="p">
							<span class="fl" id="pop_bank_title">
								银行卡号
							</span>
                    <div class="z-bind-text fl">
                        <input type="text" id="pop_bank_card" value="" placeholder="请填写银行卡号"/>
                        <label></label>
                    </div>
                </li>
                <li class="p">
							<span class="fl">
								开户名
							</span>
                    <div class="z-bind-text fl">
                        <input type="text" id="pop_bank_username" value="" placeholder="请填写真实姓名"/>
                        <label></label>
                    </div>
                </li>
            </ul>
        </div>
        <div class="z-bind-btn p">
            <div class="z-bind-qx-bank fl">
                取消
            </div>
            <div class="z-bind-qd fr" id="add_bank_card">
                <input type="button"  value="确定"/>
                <label></label>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    //			绑定账号的弹窗显示隐藏
    $("#bind_zfb a").click(function () {
        $(".z-bind-bg,.z-bind-pop").show();
    })
    $(".z-bind-qx,.z-bind-cosle").click(function () {
        $(".z-bind-bg,.z-bind-pop").hide();
    })

    $("#bind_bank a").click(function () {
        $(".z-bind-bg-bank,.z-bind-pop-bank").show();
    })
    $(".z-bind-qx-bank,.z-bind-cosle-bank").click(function () {
        $(".z-bind-bg-bank,.z-bind-pop-bank").hide();
    })

    $(".Bindings-edit").click(function () {
        if (cash_type == 0) {
            $('#pop_title').html('支付宝账号');
            $('#pop_card').val($('.Bindings-hone').html());
            $('#pop_name').val($('.Bindings-dev').html());
            $(".z-bind-bg,.z-bind-pop").show();
        }
        if (cash_type == 1) {
            $('#pop_bank_title').html('银行账号');
            $('#pop_bank_card').val($('.Bindings-bankcard').html());
            $('#pop_bank_username').val($('.Bindings-dev-bankusername').html());
            $(".z-bind-bg-bank,.z-bind-pop-bank").show();
        }

    })
    function isPone(str) {
        var myreg = /^[1][3,4,5,6,7,8,9][0-9]{9}$/;
        if (!myreg.test(str)) {
            return false;
        } else {
            return true;
        }
    }
    function isMail(str) {
        var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        if (!myreg.test(str)) {
            return false;
        }
        return true;
    }
    function isBankcard(str) {
        var myreg = /^\d{16}|\d{17}|\d{19}$/;
        if (!myreg.test($.trim(str))) {
            return false;
        }
        return true;
    }
    //添加或修改支付宝提现账号
    $(document).on("click", '#add_card', function (e) {
        var card = $('#pop_card').val();
        var user_name = $('#pop_name').val();
        if (!card) {
            layer.open({content: '账号不能为空', icon: 2, time: 1000});
            return false;
        }
        if (!user_name) {
            layer.open({content: '真实姓名不能为空', icon: 2, time: 1000});
            return false;
        }
        if (isPone(card) || isMail(card)) {

        } else {

            layer.open({content: '账号必须为手机号或邮箱', icon: 2, time: 1000});
            return false;
        }
        $.post('<?php echo U("Admin/Withdraw/bind_zfb"); ?>', {
            'type': cash_type,
            'card': card,
            'user_name': user_name
        }, function (res) {
            if (res.status == 1) {
                // layer.alert('绑定成功');
                //location.reload();
                $(".z-bind-bg,.z-bind-pop").hide();

                $('.Bindings-hone').html(card);
                $('.Bindings-dev').html(user_name);

                $('#realname').val(user_name);
                if (cash_type == 0) {
                    cash_alipay = card;
                }
                if (cash_type == 1) {
                    cash_weixinpay = card;
                }
                $('#bank_card').val(card);
                $("#ali_1").show();
                $("#ali_0").hide();
            }
        }, 'JSON')
    })

    //添加或修改银行卡账s号
    $(document).on("click", '#add_bank_card', function (e) {
        var card = $('#pop_bank_card').val();
        var cash_name = $('#pop_bank_username').val();
        if (!card) {
            layer.open({content: '银行卡号不能为空', icon: 2, time: 1000});
            return false;
        }
        if (!cash_name) {
            layer.open({content: '开户名不能为空', icon: 2, time: 1000});
            return false;
        }
        if (isBankcard(card)) {

        } else {

            layer.open({content: '请输入正确的银行卡号', icon: 2, time: 1000});
            return false;
        }
        $.post('<?php echo U("Admin/Withdraw/bind_bankcard"); ?>', {
            'type': cash_type,
            'card': card,
            'cash_name': cash_name
        }, function (res) {
            if (res.status == 1) {
                layer.msg('恭喜，绑定成功！');
                //$("#imgId").attr('src',path);
                //https://i.alipayobjects.com/combo.png?d=cashier&t=ICBC_s
                $(".z-bind-bg-bank,.z-bind-pop-bank").hide();

                $('#bank_card').html(card);
                $('.Bindings-dev-bankusername').html(cash_name);
                $('#realname').val(cash_name);
                if (cash_type == 0) {
                    cash_alipay = card;
                }
                if (cash_type == 1) {
                    cash_bank_card = card;
                }
                $('#bank_card').val(card);
                $("#bank_1").show();
                $("#bank_0").hide();
                $('#img_bank').attr('src', 'https://i.alipayobjects.com/combo.png?d=cashier&t=' + res.data + '_s')

            } else if (res.status == 0) {

                layer.open({content: res.msg, time: 1000});
            } else {
                layer.open({content: '网络错误,请重试', time: 1000});
            }
        }, 'JSON')
    })


    $('#save_data').click(function () {
        checkSubmit();
    })

    //提现提交
    var ajax_return_status = 1;
    function checkSubmit() {
        if (ajax_return_status == 0) {
            return false;
        }
        var bank_name = $.trim($('#bank_name').val());
        var bank_card = $.trim($('#bank_card').val());
        var realname = $.trim($('#realname').val());
        var money = parseFloat($.trim($('#money').val()));
        var usermoney = parseFloat('<?php echo $user['funds']; ?>');  //用户余额
//        var paypwd = $.trim($('#paypwd').val());
        var paypwd = $.trim($('#simplePasswordInput').val());

        if (money > usermoney) {
            layer.open({content: '提现金额大于您的账户余额', time: 1000});
            return false;
        }
        if (money < min_cash) {
            layer.open({content: '最小体现额度不能少于' + min_cash, time: 1000});
            return false;
        }
        if (paypwd == '') {
            layer.open({content: '请输入支付密码', time: 1000});
            return false;
        }
        ajax_return_status = 0;
        $.ajax({
            type: "post",
            url: "<?php echo U('Admin/Withdraw/storeWithdrawal'); ?>",
            dataType: 'json',
            data: $('#returnform').serialize(),
            success: function (data) {
                ajax_return_status = 1;
                if (data.status == 1) {
                    layer.open({
                        content: data.msg, time: 1000, end: function () {
                            window.location.href = data.url;
                        }
                    });
                } else {
                    layer.open({content: data.msg, time: 1000});
                }
            }
        });
    }

    function get_service() {
        if (cash_open == 1) {

            var m = $('#money').val();
            var u = $('#user_money').val();
            if (parseFloat(m) > parseFloat(max_cash)) {
                layer.open({content: '单次提现额不得大于' + max_cash, icon: 2, time: 1000});
                $('#money').val('');
                return false;
            }
            var r = get_taxfee(m);
            $('#sxf').html(r);
            $("#taxfee").val(r);
        } else {

            $('#sxf').html(0);
            $("#taxfee").val(0);
        }

    }

    //全部提现时验证金额
    $('#all_cash').click(function () {
        $('#money').val('<?php echo $user['funds']; ?>');

        var m = $('#money').val();
//            if (parseFloat(m) > parseFloat(max_cash)) {
//                layer.open({content: '单次提现额不得大于' + max_cash, icon: 2, time: 1000});
//                $('#money').val('');
//                return false;
//            }
        var r = get_taxfee(m);
        $('#sxf').html(r);
        $("#taxfee").val(r);

    })
    // 获取手续费
    function get_taxfee(m) {
        var r = m * (service_ratio / 100);
        r = parseFloat(r.toFixed(2));
//        if (max_service_money == 0) {
//            return r;
//        }
//        if (r < parseFloat(min_service_money)) {
//            r = min_service_money;
//        }
//        if (r > parseFloat(max_service_money)) {
//            r = max_service_money;
//        }
        return r;
    }

    /*********** 模拟支付宝的密码输入 start ***********/
    var PasswordInput = $("#simplePasswordInput"),
        simplePassword = $("#simplePassword");

    //第一个框显示光标
    //    $(document).ready(function(){
    //        keyup(simplePassword,PasswordInput);
    //    });

    //focus,change,blur事件
    PasswordInput.on("keyup input", function () {
        keyup(simplePassword, PasswordInput);
        if (PasswordInput.length === 6) {
            simplePassword.find(".facade-item").removeClass("password-item-focus");
        }
        $(".facade-wrap .err-msg").css("visibility", "hidden");
    }).on("focus", function () {   //点击隐藏的input密码框,在6个显示的密码框的第一个框显示光标
        $(this).val() === "";
        keyup(simplePassword, PasswordInput);
    }).on("blur", function () {   //blur时去除输入框的高亮
        simplePassword.find(".facade-item").removeClass("password-item-focus");
    });
    simplePassword.click(function () {
        PasswordInput.focus();
    });

    //触发PasswordInput的焦点
    PasswordInput.focus(function () {
        cc();
    });

    //使用keyup事件，绑定键盘上的数字按键和backspace按键
    function keyup(pwdul, pwdipt) {
        pwdul.find(".facade-item").removeClass("password-item-focus");
        var u = pwdipt.val(), //获取input的值
            u = $.trim(u),  //去掉前后空白
            o = u.length; //输入框里面的密码长度

        var i = !1,
            s = "";
        for (var n = 0; n < o; n++) {
            var a = u.substr(n, 1);
            isNaN(a) ? i = !0 : s += a; //判断非数字
        }
        o = s.length,
            pwdipt.val(s);
        if (o <= 6) {
            pwdul.find(".facade-item").removeClass("pwd");
            pwdul.find(".facade-item").each(function (pwdipt) {
                pwdipt < o && $(this).addClass("pwd");
            });
            var f = o;
            f >= 6 && (f = 5);
            pwdul.find(".facade-item").eq(f).addClass("password-item-focus")
        }
    }

    /*********** 模拟支付宝的密码输入 end ***********/

    function cc(e) {
        evt = window.event || arguments.callee.caller.arguments[0];
        var e = evt.srcElement ? evt.srcElement : evt.target;
        if (e.createTextRange) { //IE浏览器
            var r = e.createTextRange();
            r.moveEnd("character", 0);
            r.moveStart("character", e.value.length);
            r.select();
        }
    }

</script>
<!--绑定新账号弹窗 d-->
<!--footer-s-->
<!--footer-e-->

</body>
</html>