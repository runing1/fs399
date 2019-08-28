<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./application/admin/view/operator\ajaxindex.html";i:1566025147;}*/ ?>
<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
    <table>
        <tbody>
        <!--<volist name="userList" id="list">-->
        <?php if(empty($adminlist) == true): ?>
            <tr data-id="0">
                <td class="no-data" align="center" axis="col0" colspan="50">
                    <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                </td>
            </tr>
            <?php else: if(is_array($adminlist) || $adminlist instanceof \think\Collection || $adminlist instanceof \think\Paginator): $i = 0; $__LIST__ = $adminlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
            <tr data-id="<?php echo $list['user_id']; ?>">
                <td class="sign">
                    <div style="width: 24px;"><i class="ico-check"></i></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 50px;"><?php echo $list['admin_id']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['user_name']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['province']; ?><?php echo $list['city']; ?><?php echo $list['district']; ?></div>
                </td>
                <td align="left" class="">
                    <?php if($list['operator_type'] == 1): ?><div style="text-align: center; width: 150px;">县运营商</div><?php endif; if($list['operator_type'] == 2): ?><div style="text-align: center; width: 150px;">股东</div><?php endif; if($list['operator_type'] == 3): ?><div style="text-align: center; width: 150px;">核心股东</div><?php endif; ?>
                </td>

                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['mobile']; ?>

                    </div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><em>￥</em><?php echo $list['user_money']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><em>￥</em><?php echo $list['funds']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;">￥<?php echo $list['payment_amount']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['add_time']; ?></div>
                </td>
                <td align="center" class="handle">
                    <button onclick='$(this).siblings(".showList").css("display")=="none" ? $(this).siblings(".showList").show() : $(this).siblings(".showList").hide() ' style="color:#fff;background:#0ba4da;font-size:12px;width:120px;height: 20px; margin: 8px;">展开列表</button>
                    </br>
                    <div class="showList"  <?php if($list['operator_type'] == 2): ?> style="height:150px;<?php else: ?>style="height:150px;<?php endif; ?> display: none ">

                        <a class="btn blue" href="<?php echo U('Admin/operator/detail',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>详情</a>
                        <br>
                        <a class="btn blue" href="javascript:void(0)" onclick="bill(this)" data-url="<?php echo U('Admin/Operator/bill',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>账单记录</a>
                        <br>
                        <a class="btn blue" href="javascript:void(0)" onclick="team_member(this)" data-url="<?php echo U('Admin/Operator/team_member',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>团队成员</a>
                        <br>
                        <a class="btn blue" href="javascript:void(0)" data-mobile="<?php echo $list['mobile']; ?>"
                           onclick="sendsms(this)" data-url="<?php echo U('Admin/Api/sendSms'); ?>"><i class="fa fa-pencil-square-o"></i>短信发送</a>
                        <br>
                        <a class="btn blue" href="<?php echo U('Admin/operator/add_child_operator',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>添加子运营商</a>
                        <br>

                        <?php if($list['operator_type'] == 2): ?> <a class="btn blue" href="javascript:void(0)" onclick="upgrade(this)" data-url="<?php echo U('Admin/Operator/upgradeShareholder',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>升级为核心股东</a>
                            <br>
                        <?php endif; ?>

                        <a class="btn red" href="javascript:void(0)" onclick="deleteOperator(this)" data-url="<?php echo U('Admin/Operator/deleteOperator',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>删除</a>
                    <!--<button  style="color:#fff;background:#e090af;font-size:12px;width:120px;height: 40px;">团队成员</button>-->
                    <!--<br>-->
                    <!--<button  style="color:#fff;background:#e090af;font-size:12px;width:120px;height: 40px;">团队成员</button>-->
                    <!--<br>-->
                    <!--<button  style="color:#fff;background:#e090af;font-size:12px;width:120px;height: 40px;">团队成员</button>-->
                    <!--<br>-->
                    <!--<button  style="color:#fff;background:#e090af;font-size:12px;width:120px;height: 40px;">团队成员</button>-->
                    </div>

                    <!--<div style="text-align: center; width: 200px; max-width:250px;>-->
                        <!--<a class="btn blue" href="<?php echo U('Admin/user/detail',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>1详情</a>-->
                        <!--<a class="btn blue" href="<?php echo U('Admin/user/add_child_operator',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>添加子运营商</a>-->
                        <!--<a class="btn blue" href="<?php echo U('Admin/user/operator_child',array('id'=>$list['admin_id'])); ?>"><i class="fa fa-pencil-square-o"></i>团队成员</a>-->
                        <!--<a class="btn red" href="javascript:void(0)" data-mobile="<?php echo $list['mobile']; ?>"-->
                        <!--onclick="sendsms(this)" data-url="<?php echo U('Admin/Api/sendSms'); ?>"><i-->
                         <!--class="fa"></i>短信发送</a>-->
                    <!--</div>-->
                </td>
                <td align="" class="" style="width: 100%;">
                    <div>&nbsp;</div>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </tbody>
    </table>
</div>
<!--分页位置-->
<?php echo $pager->show(); ?>

<script>

    function upgrade(obj){
        layer.confirm('确认升级为核心股东？',{
            btn:['确定','取消']
            },function(){
            $.ajax({
                url: $(obj).data('url'),
                type: 'post',
                data: {},
                dataType: 'JSON',
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        layer.msg(data.msg, {icon: 1, time: 2000}, function () {
                            location.href = data.url;
                        });
                    } else {
                        layer.msg(data.msg, {icon: 2, time: 2000});
                    }
                }
            });
            },function(index){
            layer.close(index);
            }
        );
    }
    function bill(obj){
        layer.open({
            type: 2,
            title: "账单记录",
            // content: "edit.html?id=" + id,
            content: $(obj).data('url'),
            area: ["1300px", "800px"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
        })
    }
    function team_member(obj){
        layer.open({
            type: 2,
            title: "团队信息",
            // content: "edit.html?id=" + id,
            content: $(obj).data('url'),
            area: ["1300px", "800px"],
            btnAlign: 'c',
            maxmin:true,
            yes: function(index, layero) {
                layer.close(index);
            }
        })
    }

    function deleteOperator(obj){
        layer.confirm('确定要删除吗？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                // 确定
                $.ajax({
                    url: $(obj).data('url'),
                    type: 'post',
                    data: {},
                    dataType: 'JSON',
                    success: function (data) {
                        layer.closeAll();
                        if (data.status == 1) {
                            layer.msg(data.msg, {icon: 1, time: 2000}, function () {
                                location.href = data.url;
                            });
                        } else {
                            layer.msg(data.msg, {icon: 2, time: 2000});
                        }
                    }
                });
            }, function (index) {
                layer.close(index);
            }
        );
    }


    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
    $(document).ready(function(){
        // 表格行点击选中切换
        $('#flexigrid >table>tbody>tr').click(function(){
            $(this).toggleClass('trSelected');
        });
        $('#user_count').empty().html("<?php echo $pager->totalRows; ?>");
    });
    function sendsms(obj) {
        var mobile=$(obj).attr('data-mobile');
        // 删除按钮
        layer.confirm('确认发送？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {mobile:mobile,work:2,role:4},
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        layer.alert(data.msg, {icon: 1});
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            })
        }, function () {
        });
    }
</script>