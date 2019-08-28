// 提醒付款
function remind(order_sn){
        $.ajax({
            type: 'GET',
            url: "/Admin/Store/remind_payment?order_sn="+order_sn+"&user_id=61",
            dataType: "json",
            success: function (result) {
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area:["100px","50px"],
                    shadeClose: true,
                    skin: 'yourclass',
                    time:800,
                    content: '提醒成功',
                    success:function (layero) {
                        layero.find('.layui-layer-content').css('text-align', 'center');
                        layero.find('.layui-layer-content').css('line-height', '50px');

                    }
                });
            }
        })
}

// 去发货
function delivery(type,order_sn){
    $.ajax({
        type: 'GET',
        url: "/Admin/Store/go_deliver_goods?order_sn="+order_sn,
        dataType: "json",
        success: function (result) {
            // 发货信息获取
            var data = result.data.orderData;
            // 物流信息获取
            var expressData = result.data.expressData;
            var html = ""
            html = `<div id="delivery" style="display: none">
    <div>
        <span>订单编号：${data.order_sn}</span>
    </div>
    <hr>
    <div>
        <span>下单时间：${data.add_time}</span>
    </div>
    <hr>
    <div>
        <span>付款时间：${data.pay_time}</span>
    </div>
    <hr>
    <div style="cursor: pointer" class="express_company ${type == "offlin" ? "hide" : ""}">
        <span>快递公司</span>
        <span class="fr">
            <span  style="padding-right: 15px; font-size: 16px" class="selectName">请选择</span>
            <span  style="padding-right: 15px; font-size: 16px">&gt;</span>
        </span>
        <div class="clearfix"></div>
    </div>
    <hr class="${type == "offlin" ? "hide" : ""}">
    <div class="${type == "offlin" ? "hide" : ""}">
        <span>快递单号</span>
        <input type="text"  onkeyup="value=value.replace(/[\\W]/g,'') " placeholder="请输入快递单号"
onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\\d]/g,''))" class="oddNumbers" >
    </div>`
            $('.status_con').append(html)



            var materialHtml = "";
            for (var i = 0; i<expressData.length; i++){
                materialHtml += ` <li class="meterial_title" data-expcode="${expressData[i].shipping_code}" data-expid="${expressData[i].shipping_id}">
                                        <p>${expressData[i].shipping_name}</p>
                                        </li>`
            }


            $('#material ul').html(materialHtml)


            // 获取快递公司
            var selectTitle = "";
            // 快递code
            var express_code = "";

            // 快递id
            var express_id = "";

            // 快递单号
            var express_number ="";
            $(document).on('click',".express_company" ,function(){
                layer.open({
                    type: 1
                    , title: "选择快递公司"
                    , area: ['500px']
                    , btn: ['确认','取消']
                    ,shade:0
                    , content: $('#material')
                    ,success: function(layero) { //调整按钮位置
                        layero.find('.layui-layer-btn').css('text-align', 'center');
                        layero.find('.layui-layer-title').css('text-align', 'center');
                        layero.find('.layui-layer-title').css('font-size', '20px');
                        layero.find('.layui-layer-btn0').css('font-size', '20px');
                        $(document).on('click', ".meterial_title", function () {
                            $(this).addClass('selectTitle').siblings().removeClass('selectTitle');
                        })
                    }
                    ,yes:function(index,layero){
                        selectTitle = $.trim($('.selectTitle').text());
                        var selectName =  $('.selectName').text(selectTitle);
                        express_code = $('.selectTitle').data("expcode");
                        express_id = $('.selectTitle').data("expid");
                        layer.close(index)
                    }
                });
            })
            layer.open({
                type: 1
                , title: "商家发货"
                , area: ['500px']
                , btn: ['确认发货']
                ,shade:0
                , content: $('#delivery')
                ,success: function(layero){ //调整按钮位置
                    layero.find('.layui-layer-btn').css('text-align', 'center');
                    layero.find('.layui-layer-title').css('text-align', 'center');
                    layero.find('.layui-layer-title').css('font-size','20px');
                    layero.find('.layui-layer-btn0').css('font-size','20px');
                }
                ,yes:function(index,layero){
                    express_number =  $(".oddNumbers").val()
                    if(type == "offlin"){
                        $.ajax({
                            type: 'POST',
                            url: "/Admin/Store/confirm_delivery?order_sn=" + order_sn,
                            dataType: "json",
                            success: function (result) {
                                window.location.reload()
                            },
                            error:function(){
                                alert("服务器错误");
                            }
                        })
                    }else{
                        $.ajax({
                            type: 'POST',
                            url: "/Admin/Store/confirm_delivery?order_sn=" + order_sn,
                            data:{express_number:express_number,express_code:express_code,express_name:selectTitle,express_id:express_id},
                            dataType: "json",
                            success: function (result) {
                                if(result.code==200){
                                    layer.msg(result.msg);
                                    window.location.reload()
                                }else{
                                    layer.msg(result.msg);
                                }
                                console.log(result)
                            },
                            error:function(jqXHR){
                                alert("服务器错误");
                            }
                        })
                    }

                   // layer.close(index);
                }
            });
        }
    });
}