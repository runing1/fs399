/** 全局命名 */
//var dataobj = {user_id: 63}; // use_id
var loadingFlag;
// 获取店铺信息
function storeInfo(){
    loadingFlag = layer.load();
    $.ajax({
        type: 'post',
        url : '/admin/store/store_index',
      //  data : dataobj,
        dataType: 'json',
        async: true,
        success: function(res){
            var msg = res.data;
            console.log(res);
            if(res.code==200){
                        layer.close(loadingFlag); // 加载关闭
                        $('body').css('display','block');
                        localStorage.setItem('catId',msg.cat_id);
                        localStorage.setItem('type',msg.type);
                        localStorage.setItem('storeId',msg.id);
                        localStorage.setItem('userId',msg.user_id);
                        $('#store_manger').show();
                        $('#apply_store_btn').hide();
                        setHtmlInfo(msg); // 设置html
            }else{
                layer.close(loadingFlag); // 加载关闭
                        $('#store_manger').hide();
                        $('#apply_store_btn').show();
                        $('#text').html(res.msg);
                        $('body').css({
                            'display':'block',
                            'width':'100%',
                            'height':'100%',
                            'background':'rgba(0,0,0,.4)'
                        });

            }
            // if(res.code == 200) {
            //     if (msg == null) {
            //         layer.close(loadingFlag); // 加载关闭
            //         $('#store_manger').hide();
            //         $('#text').html(res.msg);
            //         $('body')
            //         .css({
            //             'display':'block',
            //             'width':'100%',
            //             'height':'100%',
            //             'background':'rgba(0,0,0,.4)'
            //         });
            //     }
            //     else {
            //         layer.close(loadingFlag); // 加载关闭
            //         $('body').css('display','block');
            //         localStorage.setItem('catId',msg.cat_id);
            //         localStorage.setItem('type',msg.type);
            //         localStorage.setItem('storeId',msg.id);
            //         localStorage.setItem('userId',msg.user_id);
            //         $('#store_manger').show();
            //         setHtmlInfo(msg); // 设置html
            //     }
            // }
        },
        error: function(error){
            console.log(error);
            // 未获取到信息
            $('body')
            .css({
                'display':'block',
                'width':'100%',
                'height':'100%',
                'background':'url(../../../public/static/images/store/store_bg.png) no-repeat',
                'background-size':'cover',
                'text-align':'center'
            })
            .html("网络请求失败，请重试...");
        }
    });
}

// 设置Html
function setHtmlInfo(msg){
    // logo
    $('.author').html('<img src="'+ msg.logo_image +'" alt="logo_image"/>');
    // store_name
    $('.h_middle').html(msg.store_name);
    // total_revenue
    $('.txt_money').html(msg.funds);
    // order_state
    $('.uncheck_num').html(msg.uncheck_num);
    $('.unpay_order').html(msg.unpay_order);
    $('.ungive_order').html(msg.ungive_order);
    $('.unget_order').html(msg.unget_order);
    $('.complaint_order').html(msg.complaint_order);
    // order_count
    $('.now_order').html(msg.now_order);
    $('.now_amount').html(msg.now_amount);
    $('.yesterday_order').html(msg.yesterday_order);
    $('.yesterday_amount').html(msg.yesterday_amount);
    // goods_store
    $('.onlin_goods').html(msg.onlin_goods);
    $('.obtained_goods').html(msg.obtained_goods);
    $('.uncheck_goods').html(msg.uncheck_goods);
    $('.reject_goods').html(msg.reject_goods);
    $('.num').html(msg.num);
}


// 加载函数
$(function(){
    storeInfo(); // 店铺信息
})