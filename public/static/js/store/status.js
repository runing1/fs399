// 超链接选项卡切换
// var urlstr = location.href; //获取点击的url
// var urlstatus=false;    //标识点击状态
// $(".order_state a").each(function () {
//
//     if ((urlstr + '/').indexOf($(this).attr('href')) > -1&&$(this).attr('href')!='') {
//         $(this).addClass('layui-this');   //添加点击状态类
//         urlstatus = true;   //切换点击状态
//     } else {
//         $(this).removeClass('layui-this');  //移除点击状态类
//     }
// });
//获取url参数
function getUrlData(){
    let url = window.location.search;  //url中?之后的部分
    url = url.substring(1);    //去掉?
    let dataObj = {};
    if(url.indexOf('&')>-1){
        url = url.split('&');
        for(let i=0; i<url.length; i++){
            let arr = url[i].split('=');
            dataObj[arr[0]] = arr[1];
        }
    }else{
        url = url.split('=');
        dataObj[url[0]]= url[1];
    }
    return dataObj;
}
//根据url参数选项卡并选中
if(getUrlData()){
    var type = getUrlData().type;
  //  alert(type)
    if(type){
        $(".layui-tab-title li").removeClass("layui-this");
        $(".layui-tab-title li a[data-value="+ type +"]").parent().addClass("layui-this").click();
       // var index = $(".layui-tab-title li.active").index();
       // alert(1)
       // tabUI(type, index);
    }else{
        alert('参数错误');
       // tableRender();
    }
}
// 待付款
$.ajax({
    type: 'GET',
    url: "/Admin/Store/store_order?type=1",
    dataType: "json",
    success: function (result) {
        var res = result.data.res;
        var html = "";
        if (res.length > 0) {
            for (var i = 0; i < res.length; i++) {
                html += `<ul class="ul_con">
                    <li>
                        <div class="pay_top">
                            <span class="fl">${res[i].nickname}</span>
                            <span class="fr bgcolor">待付款</span>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="lineItem" data-orders="${res[i].order_id}">
                        <div>
                            <div class="fl">
                                <div class="details">
                                    <div>
                                        <img src="${res[i].goods[0].logo}" alt="" width="80px" height="80px" style="vertical-align: top">
                                    </div>
                                    <div class="shopp_title">
                                        <p class="font">${res[i].goods[0].goods_name}</p>
                                        <p class="font tColor">${res[i].goods[0].spec_key_name == '' ? "" : res[i].goods[0].spec_key_name}</p>   
                                    </div>
                                </div>
                            </div>
                            <div class="fr">
                                <p class="font">¥${res[i].goods[0].goods_price}</p>
                                <p class="font tColor">x${res[i].goods[0].goods_num}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li>
                        <div class="fr bottomHR">
                            <span>共${res[i].goods[0].goods_num}件商品 </span>
                            <span> 合计:¥${res[i].real_money}</span>
                            <span> (含运费¥${res[i].shipping_price})</span>
                           
                        </div>
                        <hr>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fr  btns remind" data-order_sn="${res[i].order_sn}">提醒付款</div>
                        <div class="clearfix"></div>
                    </li>
               </ul>`
            }
            $('.obligation').html(html);
        }
    },
    error: function () {
        alert("服务器错误");
    }
})

// 提醒付款
$(document).on('click', ".remind", function () {
    var order_sn = $(this).data('order_sn');
    remind(order_sn)
})

// 总条数
var sendCount;
var page; //设置首页页码
var limit = 8;  //设置一页显示的条数
// 待发货
function sendGoods() {
    $.ajax({
        type: 'GET',
        url: "/Admin/Store/store_order?type=2",
        dataType: "json",
        data: {
            "page": page,
            "limit": limit,
        },
        success: function (result) {
            var res = result.data.res;
            sendCount = result.count;
            var html = "";

            if (res.length > 0) {
                for (var i = 0; i < res.length; i++) {
                    html += `<ul class="ul_con" data-orders="${res[i].order_id}">
                    <li>
                        <div class="pay_top">
                            <span class="fl">${res[i].nickname}</span>
                            <span class="fr bgcolor">待发货</span>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="lineItem" data-orders="${res[i].order_id}">
                        <div>
                            <div class="fl">
                                <div class="details">
                                    <div>
                                        <img src="${res[i].goods[0].logo}" alt="" width="80px" height="80px" style="vertical-align: top">
                                    </div>
                                    <div class="shopp_title">
                                        <p class="font">${res[i].goods[0].goods_name}</p>
                                        <p class="font tColor">${res[i].goods[0].spec_key_name == '' ? "" : res[i].goods[0].spec_key_name}</p>   
                                    </div>
                                </div>
                            </div>
                            <div class="fr">
                                <p class="font">¥${res[i].goods[0].goods_price}</p>
                                <p class="font tColor">x${res[i].goods[0].goods_num}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li>
                        <div class="fr bottomHR">
                            <span>共${res[i].goods[0].goods_num}件商品 </span>
                            <span> 合计:¥${res[i].real_money}</span>
                            <span> (含运费¥${res[i].shipping_price})</span>
                        </div>
                        <hr>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="fr  btns delivery">去发货</div>
                        <div class="clearfix"></div>
                        <input type="hidden" id="order_sn"  value="${res[i].order_sn}">   
                        <input type="hidden" id="type" value="${res[i].type}">                      
                    </li>
               </ul>`
                }
                $('#plist').html(html);
                if ($('#plist').html() != "") {
                    pageList()
                }

            }

        },
        error: function () {
            alert("服务器错误");
        }
    })
}

sendGoods()

function pageList() {
    layui.use('laypage', function () {
        var laypage = layui.laypage;
        laypage.render({
            elem: 'overhangPage'
            , count: sendCount
            , limit: limit
            , curr: page
            , theme: "#1E9FFF"
            , jump: function (obj, first) {

                page = obj.curr; //得到当前页，以便向服务端请求对应页的数据。
                limit = obj.limit; //得到每页显示的条数
                if (!first) {
                    sendGoods()  //加载数据
                }
                // 只有一页的时候分页不显示
                if (sendCount <= limit) {
                    $('#overhangPage').hide();
                }
            }
            //首次不执行
        });
    })
}

// 总条数
var cereCount;
var cerePage; //设置首页页码
var cereLimit = 8;  //设置一页显示的条数
// 待收货
function cereList() {
    $.ajax({
        type: 'GET',
        url: "/Admin/Store/store_order?type=3",
        dataType: "json",
        success: function (result) {
            var res = result.data.res;
            var html = "";
            cereCount = result.count;
            if (res.length > 0) {
                for (var i = 0; i < res.length; i++) {
                    html += `<ul class="ul_con" data-orders="${res[i].order_id}">
                    <li>
                        <div class="pay_top">
                            <span class="fl">${res[i].nickname}</span>
                            <span class="fr bgcolor">待收货</span>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="lineItem" data-orders="${res[i].order_id}">
                        <div>
                            <div class="fl">
                                <div class="details">
                                    <div>
                                        <img src="${res[i].goods[0].logo}" alt="" width="80px" height="80px" style="vertical-align: top">
                                    </div>
                                    <div class="shopp_title">
                                        <p class="font">${res[i].goods[0].goods_name}</p>
                                        <p class="font tColor">${res[i].goods[0].spec_key_name == '' ? "" : res[i].goods[0].spec_key_name}</p>   
                                    </div>
                                </div>
                            </div>
                            <div class="fr">
                                <p class="font">¥${res[i].goods[0].goods_price}</p>
                                <p class="font tColor">x${res[i].goods[0].goods_num}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li>
                        <div class="fr bottomHR">
                            <span>共${res[i].goods[0].goods_num}件商品 </span>
                            <span> 合计:¥${res[i].real_money}</span>
                            <span> (含运费¥${res[i].shipping_price})</span>
<!--                            <input type="hidden" id="orders_id" value="${res[i].order_id}">-->
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <hr class="${res[i].type == "offlin" ? "hide" : ""}">
                    <li class="${res[i].type == "offlin" ? "hide" : ""}">
                        <div class="fr  btns checkLogistics" data-orderid="${res[i].order_id}">查看物流</div>
                        <div class="clearfix"></div>
                    </li>
               </ul>`

                }
                $('#receList').html(html);
                if ($('#receList').html() != "") {
                    cerePageList()
                }

            }

        },
        error: function () {
            alert("服务器错误");
        }
    })
}

cereList()

function cerePageList() {

    layui.use('laypage', function () {
        var laypage = layui.laypage;
        laypage.render({
            elem: 'recePage'
            , count: cereCount
            , limit: cereLimit
            , curr: cerePage
            , theme: "#1E9FFF"
            , jump: function (obj, first) {
                cerePage:obj.curr; //得到当前页，以便向服务端请求对应页的数据。
                cereLimit = obj.limit;  //得到每页显示的条数
                if (!first) {
                    cereList()  //加载数据
                }
                // 只有一页的时候分页不显示
                if (cereCount <= cereLimit) {
                    $('#recePage').hide();
                }

            }
            //首次不执行
        });
    })
}


// 查看物流
$(document).on('click', ".checkLogistics", function () {
    var order_id = $(this).data("orderid");
    // window.open("logistics.html?order_id=" + order_id);
     window.open("/admin/store/logistics?order_id=" + order_id);
})


// 被投诉
$.ajax({
    type: 'GET',
    url: "/Admin/Store/store_order?type=7",
    dataType: "json",
    success: function (result) {
        var res = result.data.comp;

        var html = "";
        if (res.length > 0) {
            for (var i = 0; i < res.length; i++) {
                html += ` <ul class="ul_con complaint_list">
                    <li>
                        <div>
                            <span>订单编号：${res[i].order_sn}</span>
                        </div>
                        <div>
                            <img src="" alt="">
                        </div>
                    </li>
                    <li>
                        <div>
                            <span>投诉者：${res[i].username}</span>
                        </div>
                        <div>
                            <span>手机号码：${res[i].mobile}</span>
                        </div>
                    </li>
                    <li class="${res[i].shipping_num == 0 ? "hide" : ""}">
                        <div>
                            <span>运单号：${res[i].shipping_num}</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <span>发起时间：${res[i].time}</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <span>投诉原因：</span>
                            <span class="text_con">${res[i].reason}</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="${res[i].image[0]}" alt="" class="complaintImg" onclick="window.open(this.src,'_blank')">
                            <img src="${res[i].image[1]}" alt="" class="complaintImg" onclick="window.open(this.src,'_blank')">
                            <img src="${res[i].image[2]}" alt="" class="complaintImg" onclick="window.open(this.src,'_blank')">
                            <img src="${res[i].image[3]}" alt="" class="complaintImg" onclick="window.open(this.src,'_blank')">
                            <img src="${res[i].image[4]}" alt="" class="complaintImg" onclick="window.open(this.src,'_blank')">
                        </div>
                    </li>
                </ul>`

            }
            $('.complained').html(html);
        }

    },
    error: function () {
        alert("服务器错误");
    }
})

// 去发货
$(document).on('click', ".delivery", function () {
    // 获取是线上还是线下商品
    var type = $('#type').val()

    // 订单编号
    var order_sn = $('#order_sn').val();

    delivery(type, order_sn);
})
// 订单详情
$(document).on('click', ".lineItem", function () {
    var order_id = $(this).data("orders");
    // window.open("orderdetails.html?order_id="+order_id);
    window.open('/admin/store/orderDetails?order_id=' + order_id);
})

//获取url参数
function getUrlData(){
    let url = window.location.search;  //url中?之后的部分
    url = url.substring(1);    //去掉?
    let dataObj = {};
    if(url.indexOf('&')>-1){
        url = url.split('&');
        for(let i=0; i<url.length; i++){
            let arr = url[i].split('=');
            dataObj[arr[0]] = arr[1];
        }
    }else{
        url = url.split('=');
        dataObj[url[0]]= url[1];
    }
    return dataObj;
}

