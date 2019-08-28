$.ajax({
    type: 'GET',
    url: "/Admin/Store/preview_store_goods",
    dataType: "json",
    success: function (result) {

        if(result.code==400){
            $('#apply_store_btn').show();
        }else{
            var res = result.data.goods;
            var stroe = result.data.store;
            if(res.length==0){
                layer.msg('您还未发布商品');
            }else{
                var html = `<div class="bj_photo" style="background: url('${stroe.self_cover_image == ''? '/public/static/images/store/my_shop_bg2.png' : stroe.self_cover_image}') no-repeat;background-size: cover;" >
            <div class="wrap-mask">
                <div >
                    <img src="${stroe.logo_image}" alt="" class="sculpture">
                </div>
                <div class="userName">
                    <p>${stroe.store_name}</p>
                    <p class="realname">实名认证</p>
                </div>
            </div>
        </div>`
                $('.shop_con').prepend(html);
                var shopList = "";
                for(var i = 0; i<res.length; i++){
                    shopList += `<ul class="shopList">
                <li>
                    <img src="${res[i].goods_thumb}" alt=""  class="shopImg">
                </li>
                <li>
                    <p class="titles">${res[i].goods_name}</p>
                </li>
                <li>
                    <span class="money">¥${res[i].shop_price}</span>
                    <span class="btn ${res[i].promotion_method == 0 || res[i].promotion_method == 1 ?"hide" : ""}">通用</span>
                    <span class="btn ${res[i].promotion_method == 0 || res[i].promotion_method == 2 ?"hide" : ""}">指定</span>
                </li>
            </ul>`
                }
                $('.show_con').append(shopList);
            }


        }

    }
})

