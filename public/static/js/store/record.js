layui.use(['jquery','laytpl','laypage','util'], function () {
    var $ = layui.jquery,
        laytpl = layui.laytpl,
        laypage = layui.laypage;
    //var baseurl = "http://192.168.1.128/admin/";
    function Initialize(pageConf) {
        if (!pageConf) {
            pageConf = {};
            pageConf.pageSize = 10;
            pageConf.currentPage = 1;
        }
        $.ajax({
            url: "/admin/Goods/window_record",
            type: "get",
            data: {
                page: pageConf.currentPage,
                limit: pageConf.pageSize
            },
            dataType: "json",
            success: function(data){
                if(data.code == 200){
                    var mydata = eval(data.data);
                    var getTpl = record.innerHTML;
                    laytpl(getTpl).render(mydata, function (html) {
                        listview.innerHTML = html;
                    });
                    if ($("#layuipage").html() == ""){//如果没有加载过Page就加载，防止重复加载
                        if(mydata.detail.length > 0 ){
                            laypage.render({
                                elem: "layuipage",
                                count: mydata.count,
                                curr: pageConf.currentPage,
                                limit: pageConf.pageSize,
                                first: "首页",
                                last: "尾页",
                                layout: ['count', 'prev', 'page', 'next', 'limit', 'skip'],
                                jump: function (obj, first) {
                                    if (!first) {
                                        pageConf.currentPage = obj.curr;
                                        pageConf.pageSize = obj.limit;
                                        Initialize(pageConf);
                                    }
                                }
                            });
                        }
                    }
                }else{
                    $("#listview").append("<div class='list-notFound' style='margin-top:-20px;'>暂无数据</div>");
                }
            },
            error: function () {
                alert("抱歉，服务器出错了！");
            }
        });
    }
    Initialize();//初始化第一页
});