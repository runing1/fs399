var search = window.location.search;
var order_id = search.split('=')[1];

// 时间戳转换
function timestampToTime(timestamp) {
    var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
    var Y = date.getFullYear() + '-';
    var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    var D = date.getDate() + ' ';
    var h = date.getHours()< 10 ? '0'+ date.getHours() + ':' : date.getHours() + ':';
    var m = date.getMinutes() < 10 ? '0'+ date.getMinutes() + ':' : date.getMinutes() + ':';
    var s = date.getSeconds()< 10 ? '0'+ date.getSeconds() : date.getSeconds();
    return Y+M+D + h+m+s;
}


$.ajax({
    type: 'POST',
    url: "/Admin/Order/order_info",
    data:{order_id:order_id,type:2},
    dataType: "json",
    success: function (result) {
        var res = result.data;
        var order_status  = res.order_status;
        var html = `<ul>
            <li>
                <p class="details_title">订单详情</p>
            </li>
            <li class="detailStatus">
                <div class="fl">
                    <p class="text">
                    ${
                        (function(){
                            if(order_status == 0){
                                return  "等待买家付款";
                            }else if(order_status == 1){
                                return "等待发货";
                            }else{
                                return "已发货";
                            }
                        })()
                    }
                    </p>
                    <p class="font12 text">
                    ${
                        (function(){
                            if(order_status == 0){
                                return  "系统将在两个小时后自动取消订单";
                            }else if(order_status == 1){
                                return "请及时发货";
                            }else{
                                return "等待买家确认收货";
                            }
                        })()
                    }
                    </p>
                </div>
                <div class="fr">
                ${
                    (function(){
                        if(order_status == 0){
                            return  `<img src="../../../../public/static/images/store/obligation.png" alt="" class="obl_img">`;
                        }else if(order_status == 1){
                            return `<img src="../../../../public/static/images/store/shipped.png" alt="" class="obl_img">`;
                        }else{
                            return `<img src="../../../../public/static/images/store/received.png" alt="" class="obl_img">`;
                        }
                    })()
                    }
                </div>
                <div class="clearfix"></div>
            </li>
            <li>
                <p class="borderbottom">收货人信息</p>
                <div class="font14 locationList">
                    <span>&nbsp;&nbsp;${res.consignee}&nbsp;&nbsp;</span>
                    <span class="location">&nbsp;&nbsp;${res.province}${res.city}${res.district}${res.address}</span>
                    <span>&nbsp;&nbsp;${res.mobile}</span>
                </div>
            </li>
            <li>
                <div class="borderbottom">
                    <img src="${res.logo_image}" alt="" class="photo">
                    <span class="location">${res.store_name}</span>
                </div>
            </li>
            <li>
                <div class="fl">
                    <div class="goods_con">
                        <img src="${res.goods[0].logo}" alt="" class="showImg">
                        <div>
                            <p>${res.goods[0].goods_name}</p>
                            <p style="width:300px;display: table-cell;line-height: 20px">${res.goods[0].spec_key_name == null ? "" : res.goods[0].spec_key_name}</p>
                        </div>
                    </div>
                </div>
                <div class="fr line">
                    <p>¥${res.goods[0].goods_price}</p>
                    <p>x${res.goods[0].goods_num}</p>
                </div>
                <div class="clearfix"></div>
            </li>
            <li class="line">
                <div class="fl">
                 ${
                    (function () {
                        if (res.goods[0].rz_time != null ) {
                            return `<span class="${res.goods[0].rz_time == null ? "hide" : ""}">预定日期&nbsp;&nbsp;</span>
                            <span class="${res.goods[0].rz_time == null ? "hide" : ""}">&nbsp;&nbsp;${res.goods[0].rz_time.minDay}-${res.goods[0].rz_time.maxDay}</span>`;
                        } else{
                            return "";
                        }
                    })()
                    }
                    <p>店铺优惠</p>
                    <div class="${res.shipping_price == "0.00"? "hide" : ""}">
                        <span>配送方式&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>普通配送</span>
                    </div>
                    <div>
                        <span>订单备注&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>${res.user_note == "" ?"无" : res.user_note}</span>
                    </div>
                    <p>实付款(含运费)</p>
                </div>
                <div class="fr">
                    <P class="${res.goods[0].rz_day == 0 ? "hide" : ""}">共${res.goods[0].rz_day}晚</P>
                    <P>-¥${res.coupon_amount == 0 ? "0.00" : res.coupon_amount}</P>
                    <p class="${res.shipping_price == "0.00"? "hide" : ""}">快递¥${res.shipping_price}</p>
                    <p style="height:25px" class="line"></p>
                    <P class="color">¥${res.real_money}</P>
                </div>
                <div class="clearfix"></div>
            </li>
            <li>
                <p class="borderbottom">订单信息</p>
                <div class="line bottom">
                    <div>
                        <span>订单编号：</span>
                        <span>${res.order_sn}</span>
                    </div>
                    <div>
                        <span>创建时间：</span>
                        <span>${timestampToTime(res.add_time)}</span>
                    </div>
                    <div>
                        <span>付款时间：</span>
                        <span>${timestampToTime(res.pay_time)}</span>
                    </div>
                    <div class="${res.shipping_num == "" || res.shipping_num == 0 ? "hide" : ""}">
                        <span>运单编号：</span>
                        <span>${res.shipping_num}</span>
                    </div>
                    <div class="${res.shipping_time == 0 ? "hide" : ""}">
                        <span>发货时间：</span>
                        <span>${timestampToTime(res.shipping_time)}</span>
                    </div>
                </div>
            </li>
            <li>
             ${
            (function () {
                if (order_status == 0) {
                    return  ` <p class="fr payBtn remind" data-order_sn="${res.order_sn}">提醒付款</p><div class="clearfix"></div>`;
                        }else if(order_status == 1){
                            return ` <p class="fr payBtn delivery" data-order_sn="${res.order_sn}" data-type="${res.storeType}">去发货</p><div class="clearfix"></div>`;
                        }else{
                            return "";
                        }
                    })()
                    }
                
            </li>
        </ul>`
        $('.details_con').html(html)
    },
    error:function(jqXHR){
        alert("服务器错误");
    }
})

// 提醒付款
$(document).on('click',".remind" ,function(){
    var  order_sn = $(this).data('order_sn');
    remind(order_sn)
})

// 去发货
$(document).on('click',".delivery" ,function(){
    // 获取是线上还是线下商品
    var type = $(this).data('type');


    // 订单编号
    var order_sn = $(this).data('order_sn');

    delivery(type,order_sn);
})