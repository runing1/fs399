<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"./application/admin/view/user\ajaxteam.html";i:1564802643;}*/ ?>
<div class="bDiv" style="height: auto;">
    <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
        <table>
            <tbody>
            <?php if(empty($info) == true): ?>
                <tr data-id="0">
                    <td class="no-data" align="center" axis="col0" colspan="50">
                        <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                    </td>
                </tr>
                <?php else: if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <!--<td align="center" class="">-->
                        <!--<label>-->
                        <!--<div style="text-align: center; width: 50px;">-->
                        <!--<?php if($v['status'] == 0): ?><input type="checkbox" name="selected[]" value="<?php echo $v['id']; ?>"><?php endif; ?>-->
                        <!--</div></label>-->
                        <!--</td>-->
                        <!--<td align="center" class="">-->
                        <!--<div style="text-align: center; width: 50px;">-->
                        <!--<?php echo $v['id']; ?>-->
                        <!--</div>-->
                        <!--</td>-->
                        <td align="center" class="">
                            <label>
                                <div style="text-align: center; width: 50px;">
                                    <?php if($v['status'] == 0): ?><input type="checkbox" name="selected[]"
                                                                             value="<?php echo $v['id']; ?>"><?php endif; ?>
                                </div>
                            </label>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;">
                                <!--<a class="open" href="<?php echo U('Admin/user/detail',array('id'=>$v['user_id'])); ?>" target="blank">-->
                                <?php echo $v['username']; ?>
                                <!--</a>-->
                            </div>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;"><?php echo $v['level']; ?></div>
                        </td>
                        <td align="center" class="">
                            <?php if($v['type'] == 1): ?>
                                <div style="text-align: center; width: 140px;">提现</div>
                            <?php endif; if($v['type'] == 2): ?>
                                <div style="text-align: center; width: 140px;">缴纳管理费</div>
                            <?php endif; if($act_list == 'all'): if($v['type'] == 3): ?>
                                    <div style="text-align: center; width: 140px;">运营商账户提现</div>
                                <?php endif; if($v['type'] == 4): ?>
                                    <div style="text-align: center; width: 140px;">营利分红</div>
                                <?php endif; if($v['type'] == 5): ?>
                                    <div style="text-align: center; width: 140px;">运营商店铺提现</div>
                                <?php endif; endif; ?>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;"><?php echo $v['mobile']; ?></div>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;">￥<?php echo $v['type_money']; ?></div>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;"><?php echo date("Y-m-d
                                H:i",$v['createtime']); ?>
                            </div>
                        </td>
                        <td align="center" class="">
                            <div style="text-align: center; width: 140px;">￥<?php echo $v['update_money']; ?></div>
                        </td>
                        <?php if($act_list == 'all'): ?>
                            <td align="center" class="">
                                <div style="text-align: center; width: 80px;"><?php echo $v['operator_name']; ?></div>
                            </td>
                            <td align="center" class="">
                                <div style="text-align: center; width: 80px;"><?php if($v['operator_type'] == 1): ?>县运营商<?php endif; if($v['operator_type'] == 2): ?>股东<?php endif; if($v['operator_type'] == 3): ?>核心股东<?php endif; ?></div>
                            </td>
                        <?php endif; ?>
                        <td align="left" class="handle">
                            <div style="text-align: center; width: 180px; max-width:250px;">
                                <!--<a href="<?php echo U('editWithdrawals',array('id'=>$v['id'],'p'=>$_GET['p'])); ?>"-->
                                   <!--class="btn blue"><i class="fa fa-pencil-square-o"></i>查看</a>-->
                                <?php if($v['status'] <= -1): ?>
                                    <a class="btn red" href="javascript:void(0)" data-id="<?php echo $v['id']; ?>"
                                       onclick="delfunc(this)" data-url="<?php echo U('delTeams'); ?>"><i
                                            class="fa fa-trash-o"></i>删除</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td align="" class="" style="width: 100%;">
                            <div>&nbsp;</div>
                        </td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="iDiv" style="display: none;"></div>
</div>
<!--分页位置-->
<?php echo $show; ?>
<script>
    $(".pagination  a").click(function () {
        var page = $(this).data('p');
        ajax_get_table('search-form', page);
    });
    //    $(document).ready(function(){
    //        // 表格行点击选中切换
    //        $('#flexigrid >table>tbody>tr').click(function(){
    //            $(this).toggleClass('trSelected');
    //        });
    //        $('#user_count').empty().html("<?php echo $pager->totalRows; ?>");
    //    });
        function delfun(obj) {
            // 删除按钮
            layer.confirm('确认删除？', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajax({
                    type: 'post',
                    url: $(obj).attr('data-url'),
                    data: {id : $(obj).attr('data-id')},
                    dataType: 'json',
                    success: function (data) {
                        layer.closeAll();
                        if (data.status == 1) {
                            $(obj).parent().parent().parent().remove();
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                })
            }, function () {
            });
       }
</script>