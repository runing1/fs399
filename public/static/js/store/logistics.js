var search = window.location.search;
var order_id = search.split('=')[1];

// 时间戳时间转换
function timestampToTime(timestamp) {
    var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
    var Y = date.getFullYear() + '-';
    var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    var D = date.getDate() + ' ';
    var h = date.getHours() + ':';
    var m = date.getMinutes();
    return M+D+'\n'+h+m;
}
var shipping_num = ""
$.ajax({
    type: 'GET',
    url: "http://192.168.1.175/api/index/getOrderExpress?order_id="+order_id,
    dataType: "json",
    success: function (result) {
        var result = result.data;
        // 收货地址
        var Shipping_address = result.order_address;
        // 下单时间
        var pay_time = timestampToTime(result.pay_time);
        // 快递单号
        shipping_num = result.shipping_num;
        // 发货时间
        var shipping_time = timestampToTime(result.shipping_time);

        var html = `<div class="site">
            <div class="statusList">
            <img src="${result.goods_img}" alt="">
            </div>
            <div class="expressage fr">
            <P>快递公司：${result.shipping_name}</P>
        <P>单号：${result.shipping_num}</P>
        </div>
        <hr>
        <div class="clearfix"></div>
            </div>
            <div class="contentList">
                <div class="logistics_title">
                    <span class="loc">收货地址</span>
                    <span>${result.order_address}</span>
                </div>
                <ul class="logistics_detail"></ul>
            </div>`
        $('.logistics_con').append(html)

        if($('.logistics_con').html()!=""){
            $.ajax({
                type: 'GET',
                url: "http://47.111.167.36/api/index/express?express_id="+shipping_num,
                dataType: "json",
                success: function (result) {
                    if(result.code == 400){
                        alert("暂无快递信息");
                    }else{
                        var res = result.data.data;
                  var status =`<span class="status">${res[0].status}</span>` ;
                  $('.statusList').append(status)
                        var html = "";
                        for(var i = 0; i<res.length;i++){
                            html += `<li>
                    <div class="left fl">
                        <p>${res[i].time}</p>
                    </div>
                    <div class="right">
                        <p>${res[i].status}</p>
                        <p>${res[i].context}</p>
                    </div>
                </li>`
                        }
                    $('.logistics_con .logistics_detail').html(html);
                    }
                }
            })

        }



    }
})


