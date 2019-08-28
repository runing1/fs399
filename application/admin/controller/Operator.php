<?php

namespace app\admin\controller;


use think\AjaxPage;
use think\Page;
use think\Db;
use app\common\logic\AdminLogic;
use app\admin\logic\UsersLogic;
use app\common\model\Withdrawals;
use app\common\model\Users;
use app\common\model\Admin;
use think\Loader;

class Operator extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    //账单记录
    public function bill_list()
    {
        $id = input('id');
        $page = input('page', 1);
        $limit = input('limit', '');
        $offset = ($page - 1) * $limit;
        $page_offset = "$offset,$limit";
        $count = Db::name('operator_money_log')->where('operator_id', $id)->count();
        $bill = Db::name('operator_money_log')->where('operator_id', $id)->limit($page_offset)->select();
        // 类型说明:1店铺提现 2店铺开通 3运营商账户提现 4营利分红 5运营商店铺提现
        foreach ($bill as $k => $v) {
            switch ($v['type']) {
                case 1:
                    $bill[$k]['type'] = '店铺提现';
                    break;
                case 2:
                    $bill[$k]['type'] = '店铺开通';
                    break;
                case 3:
                    $bill[$k]['type'] = '运营商账户提现';
                    break;
                case 4:
                    $bill[$k]['type'] = '营利分红';
                    break;
                case 5:
                    $bill[$k]['type'] = '运营商店铺提现';
                    break;

            }
            $bill[$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
        }
        json_return_layui($bill, $count);
    }


    public function bill()
    {
        $id = input('id');
        $this->assign('id', $id);
        return $this->fetch();
    }


    /**
     * 运营商列表
     */
    public function ajaxindex()
    {
        $data = I('post.');
        $adminModel = M('admin_operator');

        $map['ao.user_name'] = array('like', '%' . $data['search_keywords'] . '%');
        // $count = $adminModel->where($map)->count();
        $count = Db::name('admin_operator')
            ->alias('ao')
            ->join('user u', 'ao.user_id=u.id', 'left')
            ->join('store s', 'ao.user_id=s.user_id', 'left')
            ->where($map)
            ->field('ao.*,u.user_money,s.funds')
            ->count();
        $Page = new AjaxPage($count, 10);
        //$res = Db::name('admin_operator')->where($map)->order('admin_id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $res = Db::name('admin_operator')
            ->alias('ao')
            ->join('user u', 'ao.user_id=u.id', 'left')
            ->join('store s', 'ao.user_id=s.user_id', 'left')
            ->where($map)
            ->field('ao.*,u.user_money,s.funds')
            ->order('admin_id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $role = D('admin_operator_role')->getField('role_id,role_name');
        $region_model = M('region');
        if ($res && $role) {
            foreach ($res as $val) {
                $val['role'] = $role[$val['role_id']];
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
                $val['province'] = $region_model->where(array('region_id' => $val['province_id']))->getField('region_name');
                $val['city'] = $region_model->where(array('region_id' => $val['city_id']))->getField('region_name');
                $val['district'] = $region_model->where(array('region_id' => $val['district_id']))->getField('region_name');
                $list[] = $val;
            }
        }
        $show = $Page->show();
        $this->assign('adminlist', $list);

        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        return $this->fetch();
    }

//    //运营商绑定app端手机号
//    public function bindApp()
//    {
//        $appphone = I('post.AppPhone');
//        $rs = Db::name('user')->where('mobile', $appphone)->find();
//        if ($rs) {
//            $this->ajaxReturn(['status' => 1, 'msg' => '1']);
//        } else {
//            $this->ajaxReturn(['status' => -1, 'msg' => '绑定手机号错误']);
//        }
//    }

    public function admin_info()
    {
        $admin_id = I('get.admin_id/d', 0);
        if ($admin_id) {
            $info = Db::name('admin_operator')->where("admin_id", $admin_id)->find();
            $info['password'] = "";
            $this->assign('info', $info);
        }
        $act = empty($admin_id) ? 'add' : 'edit';
        $this->assign('act', $act);
        $role = Db::name('admin_operator_role')->select();
        $this->assign('role', $role);
        return $this->fetch();
    }

    //添加管理员(运营商)账号
    public function adminHandle()
    {
        $data = I('post.');
        //自动验证
        $adminOperatorValidate = Loader::validate('AdminOperator');
        if (!$adminOperatorValidate->scene($data['act'])->batch()->check($data)) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败', 'result' => $adminOperatorValidate->getError()]);
        }
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $user_obj = new AdminLogic();
            $ec_salt = $user_obj->random(8);
            $data['password'] = $data['password'] . $ec_salt;
            $data['password'] = encrypt_a($data['password']);
            $data['ec_salt'] = $ec_salt;
        }
        if ($data['act'] == 'add') {//添加运营商
            //生成app前台的用户
            $salt = uniqid();
            $op_data = [
                'username' => 'op_' . uniqid(),
                'salt' => $salt,
                'level' => 99,
                'mobile' => $data['mobile'],
                'createtime' => time(),
                'password' => md5(md5($salt . 'a66666666')),
                'is_insider' => 6, //运营商标记
                'is_operator' => 1
            ];
            $lastInsId = Db::name('user')->add($op_data);

            $data['add_time'] = time();
            $data['role_id'] = 10;
            $data['user_id'] = $lastInsId;
            //系统生成推荐码
            $data['operator_tjm'] = 'op_' . substr(md5(uniqid(mt_rand(1000, 9999), true)), 26);
            $r = Db::name('admin_operator')->add($data);
            if ($r) {
                Db::name('user')->where('id', $data['user_id'])->setField('operator_id', $r);
            }

        }
        if ($data['act'] == 'add_child') {//添加子运营商
            $config = Db::name('operator_config')->field('operator_fee,operator_fee_reward')->find();
            $parent_operator = Db::name('admin_operator')->where('admin_id', $data['id'])->find();
            $reward = $config['operator_fee'] * ($config['operator_fee_reward'] / 100);
//            $data['user_id'] = Db::name('user')->where('mobile', $data['bindapp'])->getField('id');
//            if (empty($data['user_id'])) {
//                $this->ajaxReturn(['status' => -1, 'msg' => '绑定手机号错误']);
//            }

            //先生成前台用户
            $salt = uniqid();
            $op_data = [
                'username' => 'op_' . uniqid(),
                'salt' => $salt,
                'level' => 99,
                'mobile' => $data['mobile'],
                'createtime' => time(),
                'password' => md5(md5($salt . 'a66666666')),
                'is_insider' => 6, //运营商标记
                'is_operator' => 1
            ];
            $lastInsId = Db::name('user')->add($op_data);

            $data['parent_id'] = $data['id'];
            $data['add_time'] = time();
            $data['role_id'] = 10;
            $data['user_id'] = $lastInsId;
            $data['operator_type'] = 1;
            //开启事务
            Db::startTrans();
            try {
                //系统生成推荐码
                $data['operator_tjm'] = 'op_' . substr(md5(uniqid(mt_rand(1000, 9999), true)), 26);
                $a = Db::name('admin_operator')->add($data);
                //推荐运营商获得的奖励
                //$r = Db::name('admin_operator')->where('admin_id', $data['id'])->setInc('money', $reward);
                $r = Db::name('user')->where('id', $parent_operator['user_id'])->setInc('user_money', $reward);
                if ($a && $r) {
                    Db::commit();
                } else {
                    // 回滚事务
                    Db::rollback();
                    $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->ajaxReturn(['status' => -1, 'msg' => $e->getMessage()]);
            }
        }

        if ($data['act'] == 'edit') {
            $r = Db::name('admin_operator')->where('admin_id', $data['admin_id'])->save($data);
        }
        if ($data['act'] == 'del' && $data['admin_id'] > 1) {
            $r = Db::name('admin_operator')->where('admin_id', $data['admin_id'])->delete();
        }
        if ($r) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Operator/index')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
    }

    //添加子运营商
    public function add_child_operator()
    {
        $operator_id = I('get.id');
        $operator_name = Db::name('admin_operator')->where('admin_id', $operator_id)->getField('user_name');
        $operator_fee_reward = Db::name('operator_config')->getField('operator_fee_reward');
        $this->assign('operator_name', $operator_name);
        $this->assign('operator_fee_reward', $operator_fee_reward);
        $this->assign('act', 'add_child');
        return $this->fetch('admin_info');
    }

    public function operator_child()
    {
        return $this->fetch();
    }

    public function team_member()
    {
        $id = input('id', '');
        $this->assign('id', $id);
        return $this->fetch();
    }

    public function team_member_handle()
    {
        //查询运营商团队成员
        $id = input('id', '');
        $page = input('page', 1);
        $limit = input('limit', '10');
        $offset = ($page - 1) * $limit;
        $page_offset = "$offset,$limit";
        $list = Db::name('user')->where(['operator_id' => $id, 'is_operator' => 0])->limit($page_offset)->order('createtime desc')->select();
        $count = Db::name('user')->where(['operator_id' => $id, 'is_operator' => 0])->count();
        return json_return_layui($list, $count);
    }

    public function operator_child_handle()
    {
        //查询运营商团队成员
        $id = input('id', '');
        $page = input('page', 1);
        $limit = input('limit', '10');
        $offset = ($page - 1) * $limit;
        $page_offset = "$offset,$limit";
        $list = Db::name('user')->where(['operator_id' => $id, 'is_operator' => 0])->limit($page_offset)->order('createtime desc')->select();
        $count = Db::name('user')->where(['operator_id' => $id, 'is_operator' => 0])->count();
        return json_return_layui($list, $count);


//        $id = I('get.id/d', 0);
//        $team_list = Db::name('user')->where('operator_id', $id)->select();
//        $operator_name = Db::name('admin_operator')->where('admin_id', $id)->getField('user_name');
//        $count = Db::name('user')->where('operator_id', $id)->count();
//        $Page = new AjaxPage($count, 5);
//        $show = $Page->show();
//        $this->assign('operator_name', $operator_name);
//        $this->assign('show', $show);
//        $this->assign('team_list', $team_list);
//        return $this->fetch();
    }

//运营商详情
    public function detail()
    {
        $uid = input('get.id');
        $operator = Db::name('admin_operator')->alias('ao')
            ->join('user u', 'ao.user_id=u.id', 'left')
            ->join('store s', 'ao.user_id=s.user_id', 'left')
            ->field('ao.*,u.user_money,s.funds')
            ->where(array('admin_id' => $uid))
            ->find();
        if (!$operator)
            exit($this->error('会员不存在'));
        if (IS_POST) {
            //  会员信息编辑
           // dump($_POST);die;
            $password = input('post.password');
            $password2 = input('post.password2');
            if ($password != '' && $password != $password2) {
                exit($this->error('两次输入密码不同'));
            }
            if ($password == '' && $password2 == '') {
                unset($_POST['password']);
            } else {
                //获取ec_salt
                $rs = Db::name('admin_operator')->field('ec_salt')->find(array($uid));
                $_POST['password'] = encrypt_a($_POST['password'] . $rs['ec_salt']);
            }
            if (!empty($_POST['mobile'])) {
                $mobile = trim($_POST['mobile']);
                $c = Db::name('admin_operator')->where("admin_id != $uid and mobile = '$mobile'")->count();
                $c && exit($this->error('手机号不得和已有用户重复'));
            }

            if (!is_numeric($_POST['payment_amount'])) {
                exit($this->error('金额错误'));
            }
            $row = Db::name('admin_operator')->where(array('admin_id' => $uid))->save($_POST);
            if ($row)
                adminLog(json_encode($_POST));
                exit($this->success('修改成功'));
            exit($this->error('未作内容修改或修改失败'));
        }
        $province = Db::name('region')->where(array('region_type' => 1))->select();
        $city = Db::name('region')->where(array('region_type' => 2, 'parent_id' => $operator['province_id']))->select();
        $district = Db::name('region')->where(array('region_type' => 3, 'parent_id' => $operator['city_id']))->select();
        $this->assign('province', $province);
        $this->assign('city', $city);
        $this->assign('district', $district);
        $this->assign('operator', $operator);
        return $this->fetch();
    }

    public function upgradeShareholder()
    {
        $id = I('post.id');
        //判断是否满足旗下20个运营商
        $count = Db::name('admin_operator')->where('parent_id', $id)->count();
        if ($count < 20) {
            $this->ajaxReturn(['status' => -1, 'msg' => '未满足条件,需推荐20个运营商']);
        }
        $row = Db::name('admin_operator')->where('admin_id', $id)->setField('operator_type', 3);
        if ($row) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Operator/index')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }

    }

    public function deleteOperator()
    {
        $id = I('post.id');
        $user_id = Db::name('admin_operator')->where('admin_id', $id)->getField('user_id');
        $a = Db::name('admin_operator')->where('admin_id', $id)->delete();
       // echo $a;die;
        //同时删除前台用户
        $b = Db::name('user')->where('id', $user_id)->delete();
        if ($a) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Operator/index')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
    }

}