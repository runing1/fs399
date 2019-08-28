<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./application/admin/view/operator\bill.html";i:1566202615;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
</head>
<body>
<!--<div class="layui-card-header button">账单记录</div>-->
<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
    <!--<ul class="layui-tab-title">-->
        <!--<li class="layui-this">待审核</li>-->
        <!--<li>审核记录</li>-->
    <!--</ul>-->
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <table id="bill" lay-filter="test" class="hide"></table>
        </div>
    </div>
</div>
<script src="/public/static/js/jquery.js"></script>
<script src="/public/static/js/layui/layui.all.js"></script>
<!--<script src="/public/static/js/store/bill.js"></script>-->
<script>
  var id="<?php echo $id; ?>";

    layui.use('table', function(){
        var table = layui.table;

        //第一个实例
        table.render({
            elem: '#bill'
            ,cellMinWidth: 80
            ,url: '/Admin/Operator/bill_list/id/'+id //数据接口
            ,page: {theme:"#1E9FFF",prev: '<em>上一页</em>'
                ,next: '<em>下一页</em>'} //开启分页
            ,limit: 1
            ,cols: [[ //表头
                {field: 'username', title: '昵称',align: 'center'}
                ,{field: 'level', title: '星级',align: 'center'}
                ,{field: 'type', title: '类型',align: 'center' }
                ,{field: 'mobile', title: '联系电话',align: 'center'}
                ,{field: 'type_money', title: '金额',align: 'center'}
                ,{field: 'update_money', title: '奖励',align: 'center'}
                ,{field: 'createtime', title: '日期',align: 'center'}
                // ,{fixed: 'right', title:'操作', toolbar: '#bar', width:150,align: 'center'}
            ]]
        });


    });
    </script>
</body>

</html>