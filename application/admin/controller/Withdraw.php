<?php

namespace app\admin\controller;

use think\Db;
use think\Page;
use app\admin\logic\UsersLogic;
use think\Request;

class Withdraw extends Base
{
//店铺提现
    public function storeWithdrawal()
    {
        //提现类型:1=用户提现,2=商家提现3=运营商账户提现 4.商家-橱窗提现 5.运营商店铺提现

        //获取当前绑定的支付宝和银行卡号


        // $store_user=Db::name('user')->where('id',$this->user_id)->find();
        //$store_user = Db::name('store')->where('user_id', $this->user_id)->find();
        //$user = Db::name('admin_operator')->where("admin_id", session('admin_id'))->find();

        $user = db::name("admin_operator")
            ->alias('ao')
           // ->join('user u', 'ao.user_id=u.id', 'left')
            ->join('store s', 'ao.user_id=s.user_id', 'left')
            ->where('ao.admin_id', $_SESSION['admin_id'])
            ->field('ao.*,s.id as store_id,s.funds')
            ->find();



        //获取配置信息
        $operator_config = Db::name('operator_config')->find(1);

        if (IS_POST) {
            $data = I('post.');
            if (empty($data['cards'])) {
                $this->ajaxReturn(['status' => 0, 'msg' => '请输入卡号']);
            }
            if (!is_numeric($data['money'])) {
                $this->ajaxReturn(['status' => 0, 'msg' => '请输入正确的金额数']);
            }
            if (encrypt_a($data['paypwd']) != $user['payment_password']) {
                $this->ajaxReturn(['status' => 0, 'msg' => '支付密码错误']);
            }
            if ($data['money'] > $user['funds']) {
                $this->ajaxReturn(['status' => 0, 'msg' => "本次提现余额不足"]);
            }
            if ($data['money'] <= 0) {
                $this->ajaxReturn(['status' => 0, 'msg' => '提现额度必须大于0']);
            }

            //查看是否已申请提现
            $is_tixian = Db::name('store_withdrawal')->where(['user_id' => $this->user_id, 'withdraw_status' => 1, 'user_type' => 5])->find();
            if ($is_tixian) {
                $this->ajaxReturn(['status' => 0, 'msg' => "您已有提现申请处理中"]);
            }


            if ($operator_config['cash_open'] == 1) {//开启手续费
                $data['procedures_money'] = round($data['money'] * ($operator_config['operator_withdrawals'] / 100), 2);


                // 每次限提现额度
                if ($operator_config['min_cash'] > 0 && $data['money'] < $operator_config['min_cash']) {
                    $this->ajaxReturn(['status' => 0, 'msg' => '最少提现额度' . $operator_config['min_cash']]);
                }

            } else {
                $data['procedures_money'] = 0;
            }
            $data['fee'] = ($operator_config['operator_withdrawals']) / 100;
            //整理数据
            if ($data['bank_name'] == 'zfb') {
                $data['bank_name'] = '支付宝';
            } elseif ($data['bank_name'] == 'bank') {
                $data['bank_name'] = $user['bank_name'];
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,数据错误!']);
            }
            $arr = [
                'user_id' => $this->user_id,
                'money' => $data['money'],
                'fee' => $data['fee'],
                'procedures_money' => $data['procedures_money'],
                'account_name' => $data['realname'],
                'account_bank' => $data['cards'],
                'account_money' => $data['money'] - $data['procedures_money'],
                'bank_name' => $data['bank_name'],
                'create_time' => time(),
                'remark' => $data['remark'],
                'user_remark' => $data['user_remark'],
                'user_type' => 5  //运营商店铺提现
            ];
            Db::startTrans();
            try {
                if (Db::name('store_withdrawal')->insert($arr)) {
                    //减少余额
                    // $a=  Db::name('user')->where('id', $this->user_id)->setDec('user_money', $data['money']);
                    $a = Db::name('store')->where('user_id', $this->user_id)->setDec('funds', $data['money']);
                    $b = Db::name('store')->where('user_id', $this->user_id)->setInc('frozen_money', $data['money']);

                    //构造消息数据
                    $message = [
                        'user_id' => $this->user_id,
                        'store_id' => $user['store_id'],
                        'message' => '您于' . date('Y年m月d日') . "提现{$data['money']}元，扣除手续费{$data['procedures_money']}元。请前往查看！",
                        'category' => 1,
                        'type' => 16,
                        'add_time' => time()

                    ];
                    Db::name('user_message')->add($message);

                    $loginfo = json_encode($arr);
                    //写日志
                    adminLog('运营商申请提现操作:' . $loginfo);

                    if ($a && $b) {
                        // 提交事务
                        Db::commit();
                        $this->ajaxReturn(['status' => 1, 'msg' => "已提交申请", 'url' => U('withdraw/storeWithdrawal')]);


                    } else {
                        Db::rollback();
                        $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,联系客服!']);
                    }
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,联系客服!']);
                }
            } catch (\Exception $e) {
                Db::rollback();
            }

        }
        //$this->assign('store_user', $store_user);
        $this->assign('operator_config', $operator_config);
        $this->assign('user', $user);

        return $this->fetch();
    }

    //账户申请提现
    public function withdrawal()
    {
        //获取当前绑定的支付宝和银行卡号
        //$user = Db::name('admin_operator')->where("admin_id", $_SESSION['admin_id'])->find();
        // $user_zhanghu = Db::name('user')->where('id', $user['user_id'])->find();

        $user = db::name("admin_operator")
            ->alias('ao')
            ->join('user u', 'ao.user_id=u.id', 'left')
            ->join('store s', 'ao.user_id=s.user_id', 'left')
            ->where('ao.admin_id', $_SESSION['admin_id'])
            ->field('ao.*,u.user_money,s.id as store_id')
            ->find();


        //获取配置信息
        $operator_config = Db::name('operator_config')->find(1);

        if (IS_POST) {
            $data = I('post.');
            // $data['user_id'] = $user['admin_id'];
            //$cash = tpCache('cash');
            if (empty($data['cards'])) {
                $this->ajaxReturn(['status' => 0, 'msg' => '请输入卡号']);
            }
            if (!is_numeric($data['money'])) {
                $this->ajaxReturn(['status' => 0, 'msg' => '请输入正确的金额数']);
            }
            if (encrypt_a($data['paypwd']) != $user['payment_password']) {
                $this->ajaxReturn(['status' => 0, 'msg' => '支付密码错误']);
            }
            if ($data['money'] > $user['user_money']) {
                $this->ajaxReturn(['status' => 0, 'msg' => "本次提现余额不足"]);
            }
            if ($data['money'] <= 0) {
                $this->ajaxReturn(['status' => 0, 'msg' => '提现额度必须大于0']);
            }

            //查看是否已申请提现
            // $is_tixian = Db::name('store_withdrawal')->where(['user_id' => $user['admin_id'], 'user_type' => 3])->find();
            $is_tixian = Db::name('store_withdrawal')->where(['user_id' => $user['user_id'], 'withdraw_status' => 1, 'user_type' => 3])->find();
            if ($is_tixian) {
                $this->ajaxReturn(['status' => 0, 'msg' => "您已有提现申请处理中"]);
            }

//            if ($total_money + $data['money'] > $this->user['user_money']) {
//                $this->ajaxReturn(['status'=>0, 'msg'=>"您有提现申请待处理，本次提现余额不足"]);
//            }

            if ($operator_config['cash_open'] == 1) {//开启手续费
                $data['procedures_money'] = round($data['money'] * ($operator_config['operator_withdrawals'] / 100), 2);

                // 限手续费
//                if ($user['max_service_money'] > 0 && $taxfee > $user['max_service_money']) {
//                    $taxfee = $cash['max_service_money'];
//                }
//                if ($user['min_service_money'] > 0 && $taxfee < $user['min_service_money']) {
//                    $taxfee = $cash['min_service_money'];
//                }
//                if ($taxfee >= $user['money']) {
//                    $this->ajaxReturn(['status'=>0, 'msg'=>'手续费超过提现额度了！']);
//                }
                // $data['procedures_money'] = $taxfee;

                // 每次限提现额度
                if ($operator_config['min_cash'] > 0 && $data['money'] < $operator_config['min_cash']) {
                    $this->ajaxReturn(['status' => 0, 'msg' => '最少提现额度' . $operator_config['min_cash']]);
                }
//                if ($user['max_cash'] > 0 && $data['money'] > $user['max_cash']) {
//                    $this->ajaxReturn(['status'=>0, 'msg'=>'每次最多提现额度' . $user['max_cash']]);
//                }

            } else {
                $data['procedures_money'] = 0;
            }
            $data['fee'] = ($operator_config['operator_withdrawals']) / 100;
            //整理数据
            if ($data['bank_name'] == 'zfb') {
                $data['bank_name'] = '支付宝';
            } elseif ($data['bank_name'] == 'bank') {
                $data['bank_name'] = $user['bank_name'];
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,数据错误!']);
            }
            $arr = [
                // 'user_id' => $_SESSION['admin_id'],
                'user_id' => $this->user_id,
                'money' => $data['money'],
                'fee' => $data['fee'],
                'procedures_money' => $data['procedures_money'],
                'account_name' => $data['realname'],
                'account_bank' => $data['cards'],
                'account_money' => $data['money'] - $data['procedures_money'],
                'bank_name' => $data['bank_name'],
                'create_time' => time(),
                'remark' => $data['remark'],
                'user_remark' => $data['user_remark'],
                'user_type' => 3 //运营商账户余额提现
            ];

            Db::startTrans();
            try {
                if (Db::name('store_withdrawal')->insert($arr)) {
                    //减少余额
                    // $a=  Db::name('admin_operator')->where('admin_id', $user['admin_id'])->setDec('money', $data['money']);
                    $a = Db::name('user')->where('id', $user['user_id'])->setDec('user_money', $data['money']);
                    // $b = Db::name('admin_operator')->where('admin_id', $user['admin_id'])->setInc('frozen_money', $data['money']);
                    $b = Db::name('user')->where('id', $user['user_id'])->setInc('frozen_money', $data['money']);
                    //构造消息数据
                    $message = [
                        'user_id' => $this->user_id,
                        'store_id' => $user['store_id'],
                        'message' => '您于' . date('Y年m月d日') . "提现{$data['money']}元，扣除手续费{$data['procedures_money']}元。请前往查看！",
                        'category' => 1,
                        'type' => 16,
                        'add_time' => time()

                    ];
                    Db::name('user_message')->add($message);

                    $loginfo = json_encode($arr);
                    //写日志
                    adminLog('运营商申请提现操作:' . $loginfo);

                    if ($a && $b) {
                        // 提交事务
                        Db::commit();
                        $this->ajaxReturn(['status' => 1, 'msg' => "已提交申请", 'url' => U('Withdraw/withdrawal')]);


                    } else {
                        Db::rollback();
                        $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,联系客服!']);
                    }
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '提交失败,联系客服!']);
                }
            } catch (\Exception $e) {
                Db::rollback();
            }

        }
        $this->assign('operator_config', $operator_config);
        //  $this->assign('user_zhanghu', $user_zhanghu);
        $this->assign('user', $user);

        return $this->fetch();
    }

    //提现记录
    public function withdrawal_list(Request $request)
    {
        //$operator_id = session('admin_id');
        if ($request->isPost()) {
            $page = input('page', 1);
            $limit = input('limit', '');
            $offset = ($page - 1) * $limit;
            $page_offset = "$offset,$limit";
            $count = Db::name('store_withdrawal')->where(['user_id' => $this->user_id, 'user_type' => ['in', '3,5']])->count();
            // $Page = new Page($count, 1);
            // $list = Db::name('store_withdrawal')->where(['user_id' => $this->user_id, 'user_type' => ['in', '3,5']])->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $list = Db::name('store_withdrawal')->where(['user_id' => $this->user_id, 'user_type' => ['in', '3,5']])->limit($page_offset)->select();
            return json_return_layui($list, $count);
        }
        //  $show = $Page->show();
        //  $this->assign('page', $show);
        //  $this->assign('list', $list);
        return $this->fetch();
    }

    //提现密码设置
    public function withdrawal_pwd()
    {
        //获取当前用户信息
        $admin_operator = Db::name('admin_operator')->find(session('admin_id'));
        $step = I('step', 1);

        if (IS_POST && $step == 3) {
            $userLogic = new UsersLogic();
            $data = $userLogic->withdrawalPwd(session('admin_id'), I('post.new_password'), I('post.confirm_password'));
            if ($data['status'] == -1)
                $this->error($data['msg']);
            $this->redirect(U('Admin/Withdraw/withdrawal_pwd', array('step' => 3)));
            exit;
        }
        $this->assign('admin_operator', $admin_operator);
        $this->assign('step', $step);
        return $this->fetch();
    }

    //绑定支付宝
    public function bind_zfb()
    {
        $data['ali_account'] = I('post.card');
        $data['ali_username'] = I('post.user_name');
        $rs = Db::name('admin_operator')->where(array('admin_id' => $_SESSION['admin_id']))->save($data);
        $this->ajaxReturn(['status' => 1, 'msg' => '绑定成功']);

    }

    //绑定银行卡
    public function bind_bankcard()
    {
        $banks = include APP_PATH . 'admin/conf/bank.php';
        $data['bank_card'] = I('post.card');
        $data['bank_username'] = I('post.cash_name');
        if ($data['bank_card'] == '') {
            $this->ajaxReturn(['status' => 0, 'msg' => '银行卡号不能为空']);
        }
        if ($data['bank_username'] == '') {
            $this->ajaxReturn(['status' => 0, 'msg' => '开户名不能为空']);
        }
        $bank_info = Alicurl($data['bank_card']);

        $arr = json_decode($bank_info, true);
        $data['bank_code'] = $arr['bank'];
        $data['bank_name'] = $banks[$data['bank_code']];
        if ($arr['validated'] == true) {
            Db::name('admin_operator')->where(array('admin_id' => $_SESSION['admin_id']))->save($data);

            $this->ajaxReturn(['status' => 1, 'data' => $arr['bank'], 'msg' => '恭喜，绑定成功']);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '信息有误，绑定失败']);
        }
    }


    /**
     * 商家提现申请
     */
    public function store_withdraw_handle()
    {
        $uid = $this->user_id;
        $num = input('amount/d', 0);
        $type = input('type/d', 0);
        if ($num < 100) {
            return json_return('', 400, '提现金额必须大于100');
        }
        $storeData = Db::name('store')->field('id,store_name,realname,bank_name,bankcard,funds,win_funds')->where('user_id', $uid)->find();
        if ($num > $storeData['funds'] && $type == '0') {
            return json_return('', 400, '提现金额不得大于店铺金额');
        }
        if ($num > $storeData['win_funds'] && $type == '1') {
            return json_return('', 400, '提现金额不得大于店铺金额');
        }
        $storeConfig = get_store_config();
        $fee = $storeConfig['withdraw_fee'] / 100;
        $procedures_money = $num * $fee;
        $funds = $storeData['funds'];
        $where = ['funds' => Db::raw("funds-$num")];
        $user_type = 2;
        if ($type == 1) {
            $fee = $storeConfig['win_fee'] / 100;
            $procedures_money = $fee * $num;
            $funds = $storeData['win_funds'];
            $where = ['win_funds' => Db::raw("win_funds-$num")];
            $user_type = 4;
        }

        $withdraw = [
            'user_id' => $uid,
            'store_id' => $storeData['id'],
            'money' => $num,
            'fee' => $fee,
            'procedures_money' => $procedures_money,
            'account_money' => $num - $procedures_money,
            'account_name' => $storeData['realname'],
            'bank_name' => $storeData['bank_name'],
            'account_bank' => $storeData['bankcard'],
            'withdraw_status' => 1,
            'create_time' => time(),
            'user_type' => $user_type
        ];
        $storeLog = [
            'store_id' => $storeData['id'],
            'user_id' => $uid,
            'money' => -$num,
            'type' => 3,
            'before_money' => $funds,
            'after_money' => $funds - $num,
            'remark' => $storeData['store_name'] . '发起提现',
            'createtime' => time(),
            'dataFlog' => -1,
            'log_type' => $type
        ];
        $message = [
            'user_id' => $uid,
            'store_id' => $storeData['id'],
            'message' => '您于' . date('Y年m月d日') . "提现{$num}元，扣除手续费{$procedures_money}元。请前往查看！",
            'category' => 2,
            'type' => 7,
            'add_time' => time()
        ];
        Db::startTrans();
        try {
            Db::name('store')->where('user_id', $uid)->update($where);
            $withdraw_id = Db::name('store_withdrawal')->insertGetId($withdraw);
            $storeLog['withdraw_id'] = $withdraw_id;
            Db::name('store_money_log')->insert($storeLog);
            Db::name('user_message')->insert($message);
            Db::commit();
            return json_return('', 200, '处理成功');
        } catch (\Exception $e) {
            Db::rollback();
            return json_return('', 400, '处理失败');
        }
    }

    /**
     * 商家资金明细
     */
    public function store_money_info()
    {
        $id = $this->user_id;
        $type = input('type', 0);
        $page = input('page/d', 1);
        $limit = input('limit/d', 10);
        $month = input('monthTime');
        $keywords = input('keywords');
        $search = input('search');//是否搜索
        $where = array();
        $status = [1 => '审核中', 2 => '提现成功', 3 => '审核拒绝'];
        $typeDesc = [1 => '订单收款', 2 => '充值', 3 => '提现', 4 => '缴纳管理费'];
        $store_id = get_store_id($id);
        if ($search == 1) {//非搜索数据
            if (!empty($month)) {
                $monthTime = GetAppointMonth($month);
                $where['createtime'] = ['between', $monthTime];
            } else {
                $monthTime = GetBookMonth();
                $where['createtime'] = ['between', $monthTime];
            }
            $where['type'] = ['in', [1, 2, 3, 4]];
        } elseif ($search == 2) {//搜索数据
            switch ($type) {
                case 1://提现
                    $where['type'] = 3;
                    break;
                case 2://收入(充值)
                    $where['type'] = ['in', [1, 2]];
                    break;
                default:
                    $where['type'] = ['in', [1, 2, 3, 4]];
            }
            if (!empty($keywords)) {
                $where['s.remark'] = ['like', "%$keywords%"];
            }
            //根据关键字及支出类型搜索结果数据
            $searchData = Db::name('store_money_log')->alias('s')
                ->join('store_withdrawal w', 's.withdraw_id=w.id', 'left')
                ->join('order o', 'o.order_sn=s.order_sn', 'left')
                ->field('s.money,type,s.createtime,withdraw_status,o.user_id')
                ->where(['s.store_id' => $store_id])
                ->where($where)
                ->page($page)
                ->limit($limit)
                ->order('s.id desc')
                ->select();
            foreach ($searchData as $k => $v) {
                $searchData[$k]['ico'] = '';
                if ($v['type'] == 3) {//提现数据增加状态
                    $searchData[$k]['withdraw_status'] = $status[$v['withdraw_status']];
                    $searchData[$k]['ico'] = aimg('/logo/tixian.png');
                } else {
                    $searchData[$k]['withdraw_status'] = '';
                }
                if ($v['type'] == 1) {//收款数据收款人处理
                    $userName = Db::name('user')->field('username,real_name')->where('id', $v['user_id'])->find();
                    $searchData[$k]['username'] = empty($userName['real_name']) ? $userName['username'] : substr_cut($userName['real_name']);
                    $searchData[$k]['ico'] = aimg('/logo/shoukuan.png');
                }
                $searchData[$k]['time'] = date('m月d日 H:i:s', $v['createtime']);
                $searchData[$k]['typeDesc'] = $typeDesc[$v['type']];
            }
            $data = ['money' => ['payment' => 0, 'income' => 0], 'list' => $searchData];
            return json_return($data, 200, '获取成功');
        } else {
            return json_return('', 400, '获取失败');
        }
        //非搜索数据
        $data = Db::name('store_money_log')->alias('s')
            ->join('store_withdrawal w', 's.withdraw_id=w.id', 'left')
            ->join('order o', 'o.order_sn=s.order_sn', 'left')
            ->field('s.money,type,s.createtime,withdraw_status,o.user_id')
            ->where(['s.store_id' => $store_id])
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order('s.id desc')
            ->select();
        if (empty($data)) {
            return json_return('', 200, '获取成功');
        }
        $arrayTime = array();
        foreach ($data as $k => $v) {
            $arrayTime[$k] = date('Y-m', $v['createtime']);
            $data[$k]['ico'] = '';
            if ($v['type'] == 3) {//提现数据增加状态
                $data[$k]['withdraw_status'] = $status[$v['withdraw_status']];
                $data[$k]['ico'] = aimg('/logo/tixian.png');
            } else {
                $data[$k]['withdraw_status'] = '';
            }
            if ($v['type'] == 1) {//收款数据收款人处理
                $userName = Db::name('user')->field('username,real_name')->where('id', $v['user_id'])->find();
                $data[$k]['username'] = empty($userName['real_name']) ? $userName['username'] : substr_cut($userName['real_name']);
                $data[$k]['ico'] = aimg('/logo/shoukuan.png');
            }
            $data[$k]['time'] = date('m月d日 H:i:s', $v['createtime']);
            $data[$k]['typeDesc'] = $typeDesc[$v['type']];
        }
        //获取消费月份
        $arrayTime = array_unique($arrayTime);
        $array = array();
        $n = 0;
        foreach ($arrayTime as $kel => $val) {
            $array['time'] = $val;
            $date = GetAppointMonth($val);//获取指定月份月尾月末
            //每月收支统计
            $array['money'] = Db::name('store_money_log')
                ->field('abs(coalesce(sum(case when type=3 or type=4 then money else null end),0)) as payment,coalesce(sum(case when type=1 or type=2 then money else null end),0) as income')
                ->where(['store_id' => $store_id, 'dataFlog' => 1, 'createtime' => ['between', $date]])
                ->find();
            $x = 0;
            foreach ($data as $key => $value) {//月份数据拼接
                $time = date('Y-m', $v['createtime']);
                if ($array['time'] == $time) {
                    $array['list'][$x] = $value;
                    $x++;
                }
            }
            $n++;
        }
        return json_return($array, 200, '获取成功');
    }


}
