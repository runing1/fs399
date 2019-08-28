<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"./application/admin/view/user\ajaxteam_member.html";i:1564561960;}*/ ?>
<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
    <table>
        <tbody>
        <!--<volist name="userList" id="list">-->
        <?php if(empty($team_list) == true): ?>
            <tr data-id="0">
                <td class="no-data" align="center" axis="col0" colspan="50">
                    <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                </td>
            </tr>
            <?php else: if(is_array($team_list) || $team_list instanceof \think\Collection || $team_list instanceof \think\Paginator): $i = 0; $__LIST__ = $team_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                <tr data-id="<?php echo $list['user_id']; ?>">
                    <td align="center" class="">
                        <label>
                            <div style="text-align: center; width: 50px;">
                                <input type="checkbox" name="selected[]" value="<?php echo $v['id']; ?>">
                            </div>
                        </label>
                    </td>
                    <td align="left" class="">
                        <div style="text-align: center; width: 150px;"><?php echo $list['nickname']; ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 150px;"><?php echo $list['level']; ?></div>
                    </td>
                    <td align="left" class="">
                        <div style="text-align: center; width: 150px;"><?php echo $list['mobile']; ?>

                        </div>
                    </td>

                    <!--<td align="left" class="">-->
                    <!--<div style="text-align: center; width: 60px;"><?php echo $list['pay_points']; ?></div>-->
                    <!--</td>-->
                    <td align="left" class="">
                        <div style="text-align: center; width: 150px;"><?php echo date("Y-m-d H:i:s",$list['createtime']); ?></div>
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
<?php echo $show; ?>

<script>
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