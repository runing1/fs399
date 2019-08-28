// 待审核
layui.use('table', function(){
    var table = layui.table;

    //第一个实例
    table.render({
        elem: '#examine'
        ,cellMinWidth: 80
        ,url: '/Admin/Store/uncheck_upgrade_list' //数据接口
        ,page: {theme:"#1E9FFF",prev: '<em>上一页</em>'
            ,next: '<em>下一页</em>'} //开启分页
        ,limit: 10
        ,cols: [[ //表头
            {field: 'username', title: '申请人',align: 'center'}
            ,{field: 'is_activation', title: '申请等级',align: 'center'}
            ,{field: 'mobile', title: '联系方式',align: 'center' }
            ,{field: 'order_image', title: '凭证',align: 'center', templet: `<div> <img src="{{d.order_image}}" style="cursor:pointer;" onclick="window.open(this.src,'_blank')"></div>`}
            ,{fixed: 'right', title:'操作', toolbar: '#bar', width:150,align: 'center'}
        ]]
    });

    table.on('tool(test)', function(obj){
        console.log(obj);
        var data = obj.data;
        // 店铺id
        var store_id = "";
        // 付款订单id
        var pay_order = "";
        // 升级订单id
        var up_id = "";
        // 是否设置过密码
        var is_setpwd = ""

        if(obj.event === 'adopt'){
            layer.open({
                type: 1
                ,title:false
                ,btn: ['去点亮', '取消']
                ,closeBtn: 0
                , content: $('#adopt') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                ,success: function(layero) { //调整按钮位置
                    layero.find('.layui-layer-btn').css('text-align', 'center');
                }
                ,yes:function(index,layero){
                    // 点亮升级
                    layer.close(index);
                    $.ajax({
                        type: 'GET',
                        url:"/Admin/Store/store_check_list?up_id="+data.id+"&type=finish" ,
                        data: data ,
                        dataType: "json",
                        success:function (data) {
                            // var result = data.data
                            // console.log(result)
                            layer.msg(data.msg);
                            window.location.reload();
                            // store_id = result.store_id;
                            // pay_order = result.pay_order;
                            // up_id  = result.up_id;
                            // is_setpwd = result. is_setpwd;
                            // $('.RMB').html("¥ "+result.total_amount);
                            // $('.user_money').html(result.user_money);
                            // $('.store_money').html(result.store_money);
                        }
                    })
                    // layer.open({
                    //     type: 1
                    //     ,area:["501px","500px"]
                    //     , title: "支付"
                    //     , btn: ['立即付款']
                    //     , content: $('#pay') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //     ,success: function(layero){
                    //         layero.find('.layui-layer-btn').css('text-align', 'center');
                    //         layero.find('.layui-layer-btn0').css('font-size','20px');
                    //     }
                    //     ,yes:function(index,layero){
                    //         if($(".surplus").is(".select")) {
                    //            // if (is_setpwd == 0){
                    //            //      layer.open({
                    //             //          type: 1
                    //             //          , title: "请输入支付密码"
                    //             //          , btn: ['确认', '取消']
                    //             //          , content: $('#payment') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //             //          , success: function (layero) {
                    //             //              layero.find('.layui-layer-btn').css('text-align', 'center');
                    //             //              layero.find('.layui-layer-btn0').css('font-size', '20px');
                    //             //              // 输入框获取焦点
                    //             //              $('#simplePasswordInput').focus()
                    //             //              // $("#simplePasswordInput").attr({ readonly: 'true' });
                    //             //              $('.setPWD').show()
                    //             //          }
                    //             //          // ,yes: function (index, layero) {
                    //             //          //     layer.msg("请先设置支付密码");
                    //             //          // }
                    //             //      })
                    //          //   }else {
                    //                 layer.open({
                    //                     type: 1
                    //                     , title: "请输入支付密码"
                    //                     , btn: ['确认', '取消']
                    //                     , content: $('#payment') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //                     , success: function (layero) {
                    //                         layero.find('.layui-layer-btn').css('text-align', 'center');
                    //                         layero.find('.layui-layer-btn0').css('font-size', '20px');
                    //                         // 输入框获取焦点
                    //                         $('#simplePasswordInput').focus()
                    //                         if(is_setpwd==0){
                    //                             $('.setPWD').show()
                    //                         }
                    //                     }
                    //                     , yes: function (index, layero) {
                    //                         var password = $('#simplePasswordInput').val();
                    //                         $.ajax({
                    //                             type: 'POST',
                    //                             url: "/Admin/Store/pay_buy_upgrade",
                    //                             data: {
                    //                                 pay_id: 1,
                    //                                 store_id: store_id,
                    //                                 pay_order: pay_order,
                    //                                 password: password,
                    //                                 up_id: up_id
                    //                             },
                    //                             dataType: "json",
                    //                             success: function (result) {
                    //
                    //                                 layer.open({
                    //                                     type: 1,
                    //                                     title: false,
                    //                                     closeBtn: 0,
                    //                                     area: ["100px", "50px"],
                    //                                     shadeClose: true,
                    //                                     time: 800,
                    //                                     content: result.msg,
                    //                                     success: function (layero) {
                    //                                         layero.find('.layui-layer-content').css('text-align', 'center');
                    //                                         layero.find('.layui-layer-content').css('line-height', '50px');
                    //                                     }
                    //                                 });
                    //                                 if (result.code == 200) {
                    //                                     setInterval("window.location.reload()", 810);
                    //                                     console.log(result)
                    //                                 } else {
                    //                                     $('#simplePasswordInput').val('');
                    //                                     $("#simplePassword li").removeClass('pwd');
                    //                                     // 输入框获取焦点
                    //                                     $('#simplePasswordInput').focus();
                    //                                 }
                    //                             }
                    //                         })
                    //                     }
                    //                 })
                    //           //  }
                    //         }else if($(".shopsum").is(".select")){
                    //             // if (is_setpwd == 0){
                    //             //     layer.open({
                    //             //         type: 1
                    //             //         , title: "请输入支付密码"
                    //             //         , btn: ['确认', '取消']
                    //             //         , content: $('#payment') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //             //         , success: function (layero) {
                    //             //             layero.find('.layui-layer-btn').css('text-align', 'center');
                    //             //             layero.find('.layui-layer-btn0').css('font-size', '20px');
                    //             //             // 输入框只读
                    //             //             // $("#simplePasswordInput").attr({ readonly: 'true' });
                    //             //             $('.setPWD').show()
                    //             //         }
                    //             //         ,yes: function (index, layero) {
                    //             //             layer.msg("请先设置支付密码");
                    //             //         }
                    //             //     })
                    //             // }else {
                    //                 layer.open({
                    //                     type: 1
                    //                     , title: "请输入支付密码"
                    //                     , btn: ['确认', '取消']
                    //                     , content: $('#payment') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //                     , success: function (layero) {
                    //                         layero.find('.layui-layer-btn').css('text-align', 'center');
                    //                         layero.find('.layui-layer-btn0').css('font-size', '20px');
                    //                         // 输入框获取焦点
                    //                         $('#simplePasswordInput').focus()
                    //                         if(is_setpwd==0){
                    //                             $('.setPWD').show()
                    //                         }
                    //                     }
                    //                     , yes: function (index, layero) {
                    //                         var password = $('#simplePasswordInput').val();
                    //                         $.ajax({
                    //                             type: 'POST',
                    //                             url: "/Admin/Store/pay_buy_upgrade",
                    //                             data: {
                    //                                 pay_id: 4,
                    //                                 store_id: store_id,
                    //                                 pay_order: pay_order,
                    //                                 password: password,
                    //                                 up_id: up_id
                    //                             },
                    //                             dataType: "json",
                    //                             success: function (result) {
                    //
                    //                                 layer.open({
                    //                                     type: 1,
                    //                                     title: false,
                    //                                     closeBtn: 0,
                    //                                     area: ["100px", "50px"],
                    //                                     shadeClose: true,
                    //                                     time: 800,
                    //                                     content: result.msg,
                    //                                     success: function (layero) {
                    //                                         layero.find('.layui-layer-content').css('text-align', 'center');
                    //                                         layero.find('.layui-layer-content').css('line-height', '50px');
                    //                                     }
                    //                                 });
                    //                                 if (result.code == 200) {
                    //                                     setInterval("window.location.reload()", 810);
                    //                                     console.log(result)
                    //                                 } else {
                    //                                     $('#simplePasswordInput').val('');
                    //                                     $("#simplePassword li").removeClass('pwd');
                    //                                     // 输入框获取焦点
                    //                                     $('#simplePasswordInput').focus();
                    //                                 }
                    //                             }
                    //                         })
                    //                     }
                    //                 })
                    //            // }
                    //         } else if($(".Alipay").is(".select")){
                    //             window.open("pay_buy_upgrade?store_id="+store_id+"&pay_order="+pay_order+"&up_id="+up_id+"&pay_id=2");
                    //             layer.open({
                    //                 type: 1
                    //                 , title: "支付情况"
                    //                 , btn: ['我已支付成功', '不想支付了']
                    //                 // , content: $('#payment') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    //                 , success: function (layero) {
                    //                     layero.find('.layui-layer-btn').css('text-align', 'center');
                    //                     layero.find('.layui-layer-btn0').css('font-size', '20px');
                    //                     // 输入框获取焦点
                    //                     $('#simplePasswordInput').focus()
                    //                 }
                    //
                    //                 ,yes:function(index,layero){
                    //                     window.location.reload();
                    //                 }
                    //
                    //
                    //             });
                    //         }
                    //     }
                    // })
                }
            });
        } else if(obj.event === 'reason') {
            layer.open({
                type: 1
                ,closeBtn: 0
                ,title:false
                ,btn: ['确认发送', '取消']
                , content: $('#reason') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                ,success: function(layero){
                    layero.find('.layui-layer-btn').css('text-align', 'center');

                }
                ,yes:function(index,layero){
                    var val = $(".tarea").val();
                    if(val!=""){
                        $.ajax({
                            type: 'POST',
                            url:"/Admin/Store/store_check_list?type=reject",
                            data: { up_id:data.id, content:val },
                            dataType: "json",
                            success:function (data) {
                                layer.close(index);
                            }
                        })
                    }
                }
            });

        }
    });
    // 选中切换
    $(document).on("click", ".font", function() {
        // $(".font").click(function(){
        $('.font').removeClass('select')
        $(this).addClass('select');
    })
});

// 密码框
/*********** 模拟支付宝的密码输入 start ***********/
var PasswordInput = $("#simplePasswordInput"),
    simplePassword = $("#simplePassword");

//第一个框显示光标
$(document).ready(function(){
    keyup(simplePassword,PasswordInput);

});

//focus,change,blur事件
PasswordInput.on("keyup input",function(){
    keyup(simplePassword,PasswordInput);
    if(PasswordInput.length === 6){
        simplePassword.find(".facade-item").removeClass("password-item-focus");
    }
    $(".facade-wrap .err-msg").css("visibility","hidden");
}).on("focus",function(){   //点击隐藏的input密码框,在6个显示的密码框的第一个框显示光标
    $(this).val() === "";
    keyup(simplePassword,PasswordInput);
}).on("blur",function(){   //blur时去除输入框的高亮
    simplePassword.find(".facade-item").removeClass("password-item-focus");
});
simplePassword.click(function(){
    PasswordInput.focus();
});

//触发PasswordInput的焦点
PasswordInput.focus(function(){
    cc();
});

//使用keyup事件，绑定键盘上的数字按键和backspace按键
function keyup(pwdul,pwdipt){
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
        pwdul.find(".facade-item").each(function(pwdipt) {
            pwdipt < o && $(this).addClass("pwd");
        });
        var f = o;
        f >= 6 && (f = 5);
        pwdul.find(".facade-item").eq(f).addClass("password-item-focus")
    }
}

/*********** 模拟支付宝的密码输入 end ***********/

function cc(e){
    evt = window.event || arguments.callee.caller.arguments[0];
    var e = evt.srcElement ? evt.srcElement : evt.target;
    if(e.createTextRange){ //IE浏览器
        var r = e.createTextRange();
        r.moveEnd("character",0);
        r.moveStart("character",e.value.length);
        r.select();
    }
}

// 历史记录
layui.use('table', function() {
    var table = layui.table;

    //第一个实例
    table.render({
        elem: '#history'
        , cellMinWidth: 80
        , url: '/Admin/Store/store_check_info' //数据接口
        ,limit:10
        , page: {
            theme: "#1E9FFF"
            ,prev: '<em>上一页</em>'
            ,next: '<em>下一页</em>'
        }
        , cols: [[ //表头
            {field: 'username', title: '申请人', align: 'center'}
            , {field: 'is_activation', title: '申请等级', align: 'center'}
            , {field: 'mobile', title: '联系方式', align: 'center'}
            , {
                field: 'order_image', title: '凭证', align: 'center',
                templet: `<div> <img src="{{d.order_image}}" style="cursor:pointer;" " onclick="window.open(this.src,'_blank')"></div>`
            }
            , {field: 'checktime', title: '审核时间', align: 'center',templet:'<div>{{ layui.util.toDateString(d.checktime*1000, "yyyy-MM-dd HH:mm") }}</div>'}
            , {field: 'status', title: '审核状态', align: 'center'}
        ]]
        ,done: function(res, curr, count){
            $("[data-field='status']").children().each(function(){
                if($(this).text()=='finish'){
                    $(this).text("审核通过");
                }else if($(this).text()=='reject'){
                    $(this).text("拒绝");
                    $(this).css('color','#FF3030');
                }else if($(this).text()=='uncheck'){
                    $(this).text("待审核");
                }else if($(this).text()=='progress'){
                    $(this).text("审核中");
                }
            });
        }
    });

});


