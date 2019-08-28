layui.use(['jquery','table'], function(){
    var $ = layui.jquery;
    var table = layui.table;
    var baseurl = "/Admin/Store/";

    //初始化
    var filter = "goods-list";
    $("input[name='filter']").val(filter);
    $(".btn-see").eq(2).hide();
    $(".tabitem").eq(0).show().siblings(".tabitem").hide();

    //根据url参数选项卡并选中
    if(getUrlData()){
        var type = getUrlData().type;
        if(type){
            $(".shoptab li").removeClass("active");
            $(".shoptab li a[data-value="+ type +"]").parent().addClass("active");
            var index = $(".shoptab li.active").index();
            tabUI(type, index);
        }else{
            tableRender();
        }
    }

    //选项卡切换
    function tabUI(val,index){
        filter = $(".tabitem").eq(index).find("table").attr("lay-filter");
        $("input[name='filter']").val(filter);
        $("input[name='goods_name']").val('');
        $(".tabitem").eq(index).show().siblings(".tabitem").hide();
        if(val == "onlin"){//销售商品
            $(".btn-info").show();
            $(".btn-see").eq(2).hide();
            $(".btn-see").eq(3).show();
            $(".btn-see").eq(4).hide();
            tableRender();
        }else if(val == "uncheck"){//审核中
            $(".btn-info").hide();
            table.render({
                elem: "#goods-list2",
                url: baseurl + "goods_manage?status=" + val,
                page: !0,
                cols: [[
                    {type:"checkbox",fixed:"true"},
                    {title: "编号",width: "5%",templet:"#xuhao"},
                    {title: "商品名称",toolbar: "#goods_info"},
                    {field: "goods_number",title: "库存",align: "center",width: "10%"},
                    {field: "shop_price",title: "价格",align: "center",width: "10%",templet:function(d){return '¥ '+ d.shop_price}},
                    {field: "goods_status",title: "状态",align: "center",width: "10%",toolbar: "#goods_status"}
                ]],
                parseData: function(res) {
                    return {
                        "code": 0,
                        "msg": res.msg,
                        "count": res.count,
                        "data": res.data.data
                    }
                }
            });
        }else if(val == "obtained"){//下架
            $(".btn-info").show();
            $(".btn-see").eq(2).show();
            $(".btn-see").eq(3).hide();
            $(".btn-see").eq(4).hide();
            table.render({
                elem: "#goods-list3",
                url: baseurl + "goods_manage?status=" + val,
                page: !0,
                cols: [[
                    {type:"checkbox",fixed:"true"},
                    {title: "编号",width: "5%",templet:"#xuhao"},
                    {title: "商品名称",toolbar: "#goods_info"},
                    {title: "标签",width: "15%",toolbar: "#goods_tag"},
                    {field: "goods_number",title: "库存",align: "center",width: "10%"},
                    {field: "shop_price",title: "价格",align: "center",width: "10%",templet:function(d){return '¥ '+ d.shop_price}},
                    {field: "goods_status",title: "状态",align: "center",width: "10%",toolbar: "#goods_status"},
                    {title: "操作",align: "center",width: 300,toolbar: "#handle3"}
                ]],
                parseData: function(res) {
                    return {
                        "code": 0,
                        "msg": res.msg,
                        "count": res.count,
                        "data": res.data.data
                    }
                }
            });
        }else if(val == "reject"){//未通过
            $(".btn-info").show();
            $(".btn-see").eq(2).hide();
            $(".btn-see").eq(3).hide();
            $(".btn-see").eq(4).show();
            table.render({
                elem: "#goods-list4",
                url: baseurl + "goods_manage?status=" + val,
                page: !0,
                cols: [[
                    {type:"checkbox",fixed:"true"},
                    {title: "编号",width: "5%",templet:"#xuhao"},
                    {title: "商品名称",toolbar: "#goods_info"},
                    {field: "goods_number",title: "库存",align: "center",width: "10%"},
                    {field: "shop_price",title: "价格",align: "center",width: "10%",templet:function(d){return '¥ '+ d.shop_price}},
                    {field: "goods_status",title: "状态",align: "center",width: "10%",toolbar: "#goods_status"},
                    {field: "beizhu",title: "未通过原因",width: "15%"},
                    {title: "操作",align: "center",width: 300,toolbar: "#handle2"}
                ]],
                parseData: function(res) {
                    return {
                        "code": 0,
                        "msg": res.msg,
                        "count": res.count,
                        "data": res.data.data
                    }
                }
            });
        }
    }
    
    //商品列表切换
    $(".shoptab li").click(function(){
        var val = $(this).children().data("value");
        var index = $(this).index();
        $(this).addClass("active").siblings().removeClass("active");
        tabUI(val, index);
    })

    //销售商品数据加载
    function tableRender(){
        // var type = localStorage.getItem('type');
        table.render({
            elem: "#goods-list",
            url: baseurl + "goods_manage?status=onlin",
            page: !0,
            cols: [[
                {type:"checkbox",fixed:"true"},
                {title: "编号",width: "5%",templet:"#xuhao"},
                {title: "商品名称",toolbar: "#goods_info"},
                {title: "标签",width: "15%",toolbar: "#goods_tag"},
                {field: "goods_number",title: "库存",align: "center",width: "10%"},
                {field: "shop_price",title: "价格",align: "center",width: "10%",templet:function(d){return '¥ '+ d.shop_price}},
                {field: "goods_status",title: "状态",align: "center",width: "10%",toolbar: "#goods_status"},
                {title: "操作",align: "center",width: 300,toolbar: "#handle"}
            ]],
            parseData: function(res) {
                return {
                    "code": 0,
                    "msg": res.msg,
                    "count": res.count,
                    "data": res.data.data
                }
            }
        });
    }
    
    //设置展位
    table.on("tool(goods-list)", function(e) {
        var id = e.data.goods_id;
        if(e.event === "booth"){
            var t = $(this).data("value");
            var qtip,stip;
            if(t == 0){
                qtip = "确定取消展位?";
                stip = "取消成功";
            }else{
                qtip = "确定消耗1个橱窗位兑换展位?";
                stip = "兑换成功";
            }
            layer.confirm(qtip,{icon:3,title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "goods_recommend",
                    type: "post",
                    data: {
                        ids: id,
                        is_recommend: t
                    },
                    dataType: "json",
                    success: function(data){
                        layer.msg(stip, { icon: 1, time: 1000 }, function(){
                            table.reload("goods-list");
                        });
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            });
        }
    })

    //审核
    table.on("tool(goods-list4)", function(e) {
        var id = e.data.goods_id;
        if(e.event === "audits"){
            layer.confirm('确定重新审核该商品吗？',{title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "operate_goods?type=reject",
                    type: "post",
                    data: {
                        ids: id
                    },
                    dataType: "json",
                    success: function(data){
                        table.reload("goods-list4");
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            })
        }
    })

    var active = {
        add:function(){//添加
            layer.open({
                type: 2,
                title: "添加",
                content: "/admin/store/releaseGoods",
                area: ["1000px", "800px"],
                // btn: ["确定", "取消"],
                btnAlign: 'c',
                maxmin:true,
                yes: function(index, layero) {
                    layer.close(index);
                },
                success: function(t,e) {
                    
                }
            })
        },
        batchdel: function(){//批量删除
            var checkStatus = table.checkStatus(filter)
            ,checkData = checkStatus.data;
            var id = getRowFexId(filter,"goods_id");
            if(checkData.length === 0){
                return layer.msg('请选择商品');
            }
            layer.confirm('确定删除吗？',{title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "operate_goods?type=delete",
                    type: "post",
                    data: {
                        ids: id
                    },
                    dataType: "json",
                    success: function(data){
                        table.reload($("input[name=filter]").val());
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            });
        },
        on_sale: function(){//上架
            var checkStatus = table.checkStatus(filter)
            ,checkData = checkStatus.data;
            var id = getRowFexId(filter,"goods_id");
            if(checkData.length === 0){
                return layer.msg('请选择商品');
            }
            layer.confirm('确定上架商品吗？',{title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "operate_goods?type=onlin",
                    type: "post",
                    data: {
                        ids: id
                    },
                    dataType: "json",
                    success: function(data){
                        table.reload($("input[name=filter]").val());
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            });
        },
        not_on_sale: function(){//下架
            var checkStatus = table.checkStatus(filter)
            ,checkData = checkStatus.data;
            var id = getRowFexId(filter,"goods_id");
            if(checkData.length === 0){
                return layer.msg('请选择商品');
            }
            layer.confirm('确定下架商品吗？',{title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "operate_goods?type=obtained",
                    type: "post",
                    data: {
                        ids: id
                    },
                    dataType: "json",
                    success: function(data){
                        table.reload($("input[name=filter]").val());
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            });
        },
        reaudit: function(){//审核
            var checkStatus = table.checkStatus(filter)
            ,checkData = checkStatus.data;
            var id = getRowFexId(filter,"goods_id");
            if(checkData.length === 0){
                return layer.msg('请选择商品');
            }
            layer.confirm('确定重新审核该商品吗？',{title:"温馨提示"}, function(index) {
                $.ajax({
                    url: baseurl + "operate_goods?type=reject",
                    type: "post",
                    data: {
                        ids: id
                    },
                    dataType: "json",
                    success: function(data){
                        table.reload($("input[name=filter]").val());
                    },
                    complete: function () {
                        layer.close(index);
                    },
                    error: function () {
                        alert("抱歉，服务器出错了！");
                    }
                });
            });
        },
        getSearch: function(){//搜索
            var key = $("input[name='goods_name']").val();
            var tabfilter = $("input[name='filter']").val();
            if(key){
                var index = layer.msg('查询中，请稍候...',{icon: 16});
                setTimeout(function(){
                    table.reload(tabfilter, {
                        where:{
                            'key': key
                        }
                    });
                    $("input[name='goods_name']").val('');
                    layer.close(index);
                },800);
            }else{
                layer.msg("请输入商品名称");
            }
        }
    }

    $('.btn-see').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });

    //商品编辑
    window.goodsEdit = function(id){
        layer.open({
            type: 2,
            title: "商品发布",
           // content: "edit.html?id=" + id,
            content: "editGoods/id/"+id,
            area: ["1100px", "800px"],
            // btn: ["确定", "取消"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
        })
    }

    //单个商品删除
    window.goodsDel = function(obj,id){
        var obj = $(obj);
        var indexs = obj.parents("tr").data("index");
        layer.confirm("确定删除此商品?",{icon:3,title:"温馨提示"}, function(index) {
            obj.parents("tr[data-index="+indexs+"]").remove();
            $.ajax({
                url: baseurl + "operate_goods?type=delete",
                type: "post",
                data: {
                    ids: id
                },
                dataType: "json",
                success: function(data){
                    table.reload($("input[name=filter]").val());
                },
                complete: function () {
                    layer.close(index);
                },
                error: function () {
                    alert("抱歉，服务器出错了！");
                }
            });
        });
    }

    //单个商品上下架切换
    window.listTableSwitch = function(obj, act, id) {
        var obj = $(obj);
        $.ajax({
            url: baseurl + "operate_goods?type=" + act,
            type: "post",
            data: {
                ids: id
            },
            dataType: "json",
            success: function(){
                if (obj.hasClass("active")) {
                    obj.removeClass("active");
                    obj.next("input[type='hidden']").val(0);
                    obj.attr("title", "否");
                } else {
                    obj.addClass("active");
                    obj.next("input[type='hidden']").val(1);
                    obj.attr("title", "是");
                }
            },
            error: function () {
                alert("抱歉，服务器出错了！");
            }
        });
    }

    //获取选中的值
    function getRowFexId(tables,value){
        var checkStatus = table.checkStatus(tables),
        data = checkStatus.data,
        arr = new Array();
        for(var i = 0;i<data.length;i++){
            arr.push(data[i][value]);
        }
        return arr.join(",");
    }

    //复制链接
    var clipboard = new ClipboardJS(".btn-operation");
    clipboard.on("success", function(e) {
        layer.msg("复制成功");
        e.clearSelection();
    });
    clipboard.on("error", function(e) {
        console.log(e);
        layer.msg("复制失败，请重试");
    });

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
})
