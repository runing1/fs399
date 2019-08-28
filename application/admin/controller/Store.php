<?php

namespace app\admin\controller;

use app\common\logic\Random;
use app\common\logic\Goods;
use app\admin\model\GoodsAttr;
use app\admin\model\Products;
use app\admin\model\Attribute;
use think\Db;
use think\Debug;
use app\admin\model\Store as StoreModel;
use think\Request;

class Store extends Base
{
    public function publishGoods()
    {
        return $this->fetch();
    }

    public function storeManage()
    {
        $count = Db::name('store_withdrawal')->where(['user_id' => $this->user_id, 'user_type' => ['in', '3,5']])->find();
        // $where= ['level' => Db::raw("level+1")];
        // $where=['level' => ['exp', 'level+1']];
        // Db::name('user')->where('id', 359)->update($where);
        $this->assign('count', $count);

        return $this->fetch();
    }

    public function editStore()
    {
        return $this->fetch();
    }

    public function editGoods()
    {
        return $this->fetch();
    }

    public function releaseGoods()
    {
        return $this->fetch();
    }

    public function order_manage()
    {
        return $this->fetch();
    }

    public function shenhe()
    {
        return $this->fetch();
    }

    public function orderDetails()
    {
        return $this->fetch();
    }

    public function logistics()
    {
        return $this->fetch();
    }

    public function goodsManage()
    {
        return $this->fetch();
    }

    public function showCase()
    {
        return $this->fetch();
    }

    public function cityPicker()
    {
        $a = APP_PATH . 'admin/conf/CityPicker.json';
        $city = file_get_contents($a);
        echo $city;
    }

    public function pay_dalibao()
    {
        vendor('Alipay.Alipay');
        $order_amount = '0.01';
        $pay_order['order_num'] = '1256987632778958';
        $alipay = new \Alipay();
        $btype = "operator_pay";
        $price = $order_amount;
        $order_sn = $pay_order['order_num'];
        $body = "支付凡商优店帮帮商品，订单号为：${order_sn}，支付金额为：${price}元";
        $subject = "支付凡商优店帮帮商品，订单号为：" . $order_sn;
        $orderInfo = $alipay->tradeAppPay($body, $subject, $order_sn, $price, $btype);
        return $orderInfo;
        // exit('{"data":"' . $orderInfo . '","msg":"success","code":200}');
    }

//开通店铺

    /**
     *
     *店铺入驻申请(无人脸识别)
     * */
    public function submit_store_certifications()
    {
         $uid = $this->user_id;
       // $uid = input('user_id', 355);
        //验证店铺是否存在
        $auth_check = Db::name('store')->where(array('user_id' => $uid))->field('id')->find();

//        if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] == 0) {
//            $logo_image = oss_upload_file('logo_image');
//        } else {
//            if (!$auth_check)
//                return json_return('', 400, '头像LOGO不能为空！');
//        }
        if (input('logo_image') != '') {
            $logo_image = input('logo_image');
        } else {
            if (!$auth_check)
                return json_return('', 400, '头像LOGO不能为空！');
        }
        $type = input('post.type');
        if (empty($type)) {
            return json_return('', 400, '请选择入驻属性！');
        }
        if ($type == 'offlin') {
            $cat_id = input('post.cat_id');
            if (empty($cat_id)) {
                return json_return('', 400, '请选择店铺类别！');
            }
        } else {
            $cat_id = 0;
        }
        $store_name = input('post.store_name');
        if (empty($store_name)) {
            return json_return('', 400, '商铺名称不能为空！');
        }
        $province_id = input('post.province_id');
        if (empty($province_id)) {
            return json_return('', 400, '请选择省份！');
        }
        $city_id = input('post.city_id');
        if (empty($city_id)) {
            return json_return('', 400, '请选择市！');
        }
        $district_id = input('post.district_id');
        if (empty($district_id)) {
            return json_return('', 400, '请选择区！');
        }
        $street_id = input('post.street_id');
        if (empty($street_id)) {
            return json_return('', 400, '请选择街道！');
        }
        $wx_number = input('post.wx_number');
        if (empty($wx_number)) {
            return json_return('', 400, '店家微信不能为空！');
        }

        $qq = input('post.qq');
        if (empty($qq)) {
            return json_return('', 400, '店家qq不能为空！');
        }

        $realname = input('post.realname');
        if (empty($realname)) {
            return json_return('', 400, '真实姓名不能为空！');
        }
        $id_card = input('post.id_card');
        if (empty($id_card)) {
            return json_return('', 400, '身份证不能为空！');
        }
        $bankcard = input('post.bankcard');
        if (empty($bankcard)) {
            return json_return('', 400, '银行卡号不能为空！');
        }
        $mobile = input('post.mobile');
        if (empty($mobile)) {
            return json_return('', 400, '手机号码不能为空！');
        }
        $code = input('post.code');
        if (empty($code)) {
            return json_return('', 400, '验证码不能为空！');
        }

        //验证验证码
        $smsRes = check_code($mobile, $code);
        if ($smsRes['status'] == 0) {
            return json_return('', 400, $smsRes['msg']);
        }

        if (input('wechat_code_img') != '') {
            $wechat_code_img = input('wechat_code_img');
        } else {
            if (!$auth_check)
                return json_return('', 400, '微信二维码不能为空！');
        }

        if (input('card_img_front') != '') {
            $card_img_front = input('card_img_front');
        } else {
            if (!$auth_check)
                return json_return('', 400, '身份证正面照不能为空！');
        }
        if (input('card_img_back') != '') {
            $card_img_back = input('card_img_back');
        } else {
            if (!$auth_check)
                return json_return('', 400, '身份证反面照不能为空！');
        }
        Db::startTrans();
        try {

            if (!$auth_check) {
                //验证身份证是否已经使用
                $check_card = db::name('store')
                    ->where('id_card', $id_card)
                    ->value('id');
                if ($check_card) {
                    return json_return('', 400, '该身份证已被其他商家使用！');
                }
                //银行卡鉴权
                $data = array(
                    'key' => '39b1ce181281cef4412af235b2c0d3dd',
                    'realname' => $realname,
                    'idcard' => $id_card,
                    'bankcard' => $bankcard,
                    'mobile' => $mobile,
                    'uorderid' => uniqid()
                );
                $result = _request('http://v.juhe.cn/verifybankcard4/query', FALSE, 'post', $data);
                $backInfo = json_decode($result, TRUE);
                if (!isset($backInfo['result']['res'])) {
                    return json_return('', 400, $backInfo['reason']);
                }
                if ($backInfo['result']['res'] != 1) {
                    return json_return('', 400, $backInfo['result']["message"]);
                }
                $b = Alicurl($bankcard);
                $bank = json_decode($b, true);
                $bank_list = include APP_PATH . 'admin/conf/bank.php';
                // $bank_name = config("bank." . $bank['bank']);
                $bank_name = $bank_list[$bank['bank']];
                //  var_dump($bank_name);
                $bank_icon = 'https://i.alipayobjects.com/combo.png?d=cashier&t=' . $bank['bank'] . '_s';
                //   var_dump($bank_icon);
                //插入申请入驻信息
                $list = array(
                    'user_id' => $uid,
                    'id_card' => $id_card,
                    'realname' => $realname,
                    'cat_id' => $cat_id,
                    'createtime' => time(),
                    'bank_name' => $bank_name,
                    'bank_icon' => $bank_icon,
                    'bankcard' => $bankcard,
                    'mobile' => $mobile,
                    'logo_image' => $logo_image,
                    'store_name' => $store_name,
                    'province' => get_area($province_id),
                    'city' => get_area($city_id),
                    'district' => get_area($district_id),
                    'street' => get_area($street_id),
                    'province_id' => $province_id,
                    'city_id' => $city_id,
                    'district_id' => $district_id,
                    'street_id' => $street_id,
                    'wx_number' => $wx_number,
                    'qq' => $qq,
                    'type' => $type,
                    'wechat_code_img' => $wechat_code_img,
                    'card_img_front' => $card_img_front,
                    'card_img_back' => $card_img_back,
                    'state' => 'finish',
                    //'manager_fee_time' => getNextMonthDays(time()) + 24 * 3600 - 1
                    'manager_fee_time' => getManagerDays(time(), 12) + 24 * 3600 - 1

                );
                // var_dump($list);die;
                $res = db::name("store")->insertGetId($list);

                //添加首年免管理费记录
                $s_data = array(
                    'user_id' => $uid,
                    'type' => 'month',
                    'order_sn' => '88888888',
                    'fee' => 0,
                    'status' => 'pay',
                    'createtime' => $list['createtime'],
                    'expiretime' => $list['manager_fee_time'],
                );

                $last = db::name('managerfee')->insertGetId($s_data);
                //修改用户类型、同步四要素信息
                $map = array(
                    'store_status' => $list['type'],
                    'real_name' => $list['realname'],
                    'card_id' => $list['id_card'],
                    'card_img_front' => $list['card_img_front'],
                    'card_img_back' => $list['card_img_back'],
                    'bank_name' => $list['bank_name'],
                    'bank_icon' => $list['bank_icon'],
                    'bankcard' => $list['bankcard'],
                    'wx_id' => $list['wx_number'],
                    'qq' => $list['qq'],
                    'is_activation' => 2,
                    'updatetime' => time()
                );
                $user = db::name('user')->where('id', $uid)->update($map);

                //消息通知
                $m_data = array(
                    "user_id" => $uid,
                    "store_id" => $res,
                    "message" => '恭喜您，您的店铺已免费开通成功，请去您的店铺发布新商品，开启赚钱新模式。',
                    "category" => 2,
                    "type" => 6,
                    "add_time" => time(),
                    "is_user_info" => 1
                );

                $m_m_bool = db::name('user_message')->insert($m_data);

                $cat_id = Goods::WIN_LOCATION_CATE;
                //  $winGoods = db::name('goods')->where(['is_bbm' => $cat_id, 'user_id' => $uid])->find();
                $winNum = 10;

                //$winNum = 3;
                //      $level = db::name('user')->where('id', $uid)->value('level');

//                if ($level < 1) {
//                    $winNum = 1;
//                }

//                if ($winGoods) {
//                    $ret = db::name('goods')->where('goods_id', $winGoods['goods_id'])->update(['store_id' => $res, 'goods_number' => $winGoods['goods_number'] + $winNum]);
//                } else {
//                    $count = db('user')->where(['parent' => $uid, 'level' => ['gt', 0]])->count();
//                    $insertData = [
//                        'store_id' => $res,
//                        'user_id' => $uid,
//                        'is_bbm' => $cat_id,
//                        'goods_number' => $winNum + getRecNum($count),
//                        'goods_status' => '',
//                        'shop_price' => '100',
//                        'market_price' => '100',
//                        'goods_name' => '橱窗位',
//                        'goods_thumb' => Goods::WIN_LOCATION_URL,
//                        'add_time' => time()
//                    ];
//                    $ret = db::name('goods')->insert($insertData);
//                }
                $insertData = [
                    'store_id' => $res,
                    'user_id' => $uid,
                    'is_bbm' => $cat_id,
                    'goods_number' => $winNum,
                    'goods_status' => '',
                    'shop_price' => '100',
                    'market_price' => '100',
                    'goods_name' => '橱窗位',
                    'goods_thumb' => Goods::WIN_LOCATION_URL,
                    'add_time' => time()
                ];
                $ret = db::name('goods')->insert($insertData);


                if ($res && $last && $ret) {
                    Db::commit();
                    $pc_data = array(
                        "title" => "店铺申请入驻",
                        "content" => "店铺" . $store_name . "已提交申请入驻资料,点击前往处理",
                        "type" => "4"
                    );
                    uptoPC($pc_data);
                    return json_return('', 200, '申请成功！');
                } else {
                    Db::rollback();
                    return json_return('', 400, '申请失败！');
                }
            } else {
                //验证身份证是否已经使用
                $check_card = db::name('store')
                    ->where('id_card', $id_card)
                    ->where('id', '<>', $auth_check['id'])
                    ->value('id');
                if ($check_card) {
                    return json_return('', 400, '该身份证已被其他商家使用！');
                }
                //银行卡鉴权
                $data = array(
                    'key' => '39b1ce181281cef4412af235b2c0d3dd',
                    'realname' => $realname,
                    'idcard' => $id_card,
                    'bankcard' => $bankcard,
                    'mobile' => $mobile,
                    'uorderid' => uniqid()
                );
                $result = _request('http://v.juhe.cn/verifybankcard4/query', FALSE, 'post', $data);
                $backInfo = json_decode($result, TRUE);
                if (!isset($backInfo['result']['res'])) {
                    return json_return('', 400, $backInfo['reason']);
                }
                if ($backInfo['result']['res'] != 1) {
                    return json_return('', 400, $backInfo['result']["message"]);
                }
                $b = Alicurl($bankcard);
                $bank = json_decode($b, true);
                $bank_name = config("bank." . $bank['bank']);
                $bank_icon = 'https://i.alipayobjects.com/combo.png?d=cashier&t=' . $bank['bank'] . '_s';
                //修改申请入驻资料
                $list = array(
                    'store_name' => $store_name,
                    'cat_id' => $cat_id,
                    'province' => get_area($province_id),
                    'city' => get_area($city_id),
                    'district' => get_area($district_id),
                    'street' => get_area($street_id),
                    'province_id' => $province_id,
                    'city_id' => $city_id,
                    'district_id' => $district_id,
                    'street_id' => $street_id,
                    'wx_number' => $wx_number,
                    'qq' => $qq,
                    'state' => 'uncheck',
                    'check_status' => 'uncheck',
                    'note' => '',
                    'checktime' => '',
                    'type' => $type,
                );

                //修改用户类型、同步四要素信息
                $map = array(
                    //'store_status'   => $list['type'],
                    'wx_id' => $list['wx_number'],
                    'qq' => $list['qq'],
                    'updatetime' => time()
                );

                if (isset($logo_image)) {
                    $list ['logo_image'] = $logo_image;
                }
                if (isset($wechat_code_img)) {
                    $list ['wechat_code_img'] = $wechat_code_img;
                }

                if (isset($card_img_front)) {
                    $list ['card_img_front'] = $card_img_front;
                    $map['card_img_front'] = $list['card_img_front'];
                }

                if (isset($card_img_back)) {
                    $list ['card_img_back'] = $card_img_back;
                    $map['card_img_back'] = $list['card_img_back'];
                }

                $last = db::name("store")->where('id', $auth_check['id'])->update($list);

                //修改用户类型、同步四要素信息

                $user = db::name('user')->where('id', $uid)->update($map);

                if ($last && $user) {
                    Db::commit();
                    $pc_data = array(
                        "title" => "店铺申请入驻",
                        "content" => "店铺" . $store_name . "已提交申请入驻资料,点击前往处理",
                        "type" => "4"
                    );
                    uptoPC($pc_data);
                    return json_return('', 200, '提交成功！');
                } else {
                    Db::rollback();
                    return json_return('', 400, '提交失败！');
                }
            }
        } catch (\Exception $e) {
            Db::rollback();
            exit($e->getMessage());
        }
    }


    /**
     * 用户升级待审核记录
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="page", type="integer", required=false, description="页码")
     * @ApiParams   (name="limit", type="integer", required=false, description="页数")
     * */
    public function uncheck_upgrade_list()
    {
        $list = array(
          //  'user_id' => $this->uid,
            'user_id' => $this->user_id,
            'page' => input('page') ?: 1,
            'limit' => input('limit/d') ?: 10
        );

        $data = array();
        $res = array();
        //查询用户已完成的申请记录
        $data = db::name('updetail')
            ->alias('a')
            ->join('shop_user o', 'a.user_id=o.id')
            ->where(array('a.store_uid' => $list['user_id'], 'a.status' => 'uncheck'))
            ->field('a.id,a.user_up_level,a.order_id,a.order_image,a.order_image_two,a.status,o.username,o.real_name,o.avatar,o.is_activation,o.mobile')
            ->limit($list['limit'])
            ->page($list['page'])
            ->order('id desc')
            ->select();
        if ($data) {
            foreach ($data as $key => $value) {
                $data[$key]['order_image'] = add_image_pre($value['order_image']);
                $data[$key]['order_image_two'] = add_image_pre($value['order_image_two']);
                $data[$key]['avatar'] = add_image_pre($value['avatar']);
                $data[$key]['real_name'] = $value['username'] ? substr_cut($value['username']) : $value['username'];
            }
        }
//        $res = array(
//            'list' => $data,
//            'num' => count($data)
//        );

      //  return json_return($res);
        return json_return_layui($data, count($data));
    }


    /**
     *用户升级待审核记录
     * */
//    public function uncheck_upgrade_list()
//    {
//        $list = array(
//            'user_id' => input('user_id', 53),
//            'page' => input('page') ?: 1,
//            'limit' => input('limit/d') ?: 10
//        );
//
//        //查询用户已完成的申请记录
//        $data = Db::name('updetail')
//            ->alias('a')
//            ->join('shop_user o', 'a.user_id=o.id')
//            ->where(array('a.store_uid' => $list['user_id'], 'a.status' => 'uncheck'))
//            ->field('a.id,a.user_up_level,a.order_id,a.order_image,a.order_image_two,a.status,o.username,o.real_name,o.avatar,o.is_activation,o.mobile')
//            ->limit($list['limit'])
//            ->page($list['page'])
//            ->order('id desc')
//            ->select();
//        if ($data) {
//            foreach ($data as $key => $value) {
//                $data[$key]['order_image'] = add_image_pre($value['order_image']);
//                $data[$key]['order_image_two'] = add_image_pre($value['order_image_two']);
//                $data[$key]['avatar'] = add_image_pre($value['avatar']);
//                $data[$key]['real_name'] = $value['username'] ? substr_cut($value['username']) : $value['username'];
//            }
//        }
////        $res = array($data, 'num' => count($data)
////        );
//
//        return json_return_layui($data, count($data));
//    }

    public function store_check_before()
    {
        $data = array(
            //'user_id' => $this->uid, //审核人id
            'user_id' => $this->user_id, //审核人id
            'up_id' => input('up_id')
        );
        $updetail = db::name('updetail')->alias('u')
            ->join('shop_order o', 'u.order_id=o.order_id')
            ->field('u.user_id,u.store_id,o.order_id,o.total_amount')
            ->where('id', $data['up_id'])->find();
        if (empty($updetail)) {
            return json_return('', 400, '参数错误');
        }
        $password=db::name('admin_operator')->where('user_id',$data['user_id'])->value('payment_password');

        $pay_order = db::name('pay_order')->where('store_order_id',$updetail['order_id'])->find();

        //审核人余额
        $user = db::name('user')->where('id', $data['user_id'])->value('user_money');

        //审核人店铺余额
        $store_money = db::name('store')->where('user_id', $data['user_id'])->value('funds');

        $res['total_amount'] = $pay_order['total_goods_price'];
        $res['user_money'] = $user;
        $res['store_money'] = $store_money;
        $res['pay_order'] = $pay_order['id'];
        $res['store_id'] = $updetail['store_id'];
        $res['up_id'] = $data['up_id'];
        $res['is_setpwd']= $password ? 1:0;
        return json_return($res, 200, 'success！');
    }

    /**
     * 店主审核升级记录
     * */
    public function store_check_list111()
    {
        $data = array(
            'user_id' => $this->user_id,
            'up_id' => input('up_id'),
            'type' => input('type'),
            'content' => input('content')
        );

        if ($data['type'] == 'finish') {//同意升级
            //开启事务
            Db::startTrans();
            try {
                //todo 审核逻辑
                $ret = checkUpgrade($data['up_id'], $data['user_id']);
                if ($ret['state']) {
                    $redis = Redis();
                    $red_dev_code = $redis->get("dev_code" . $ret['data']['user_id']);
                    if ($red_dev_code) {
                        jgSend2($red_dev_code, $ret['msg_data']['message'], '', 10016);
                    }
                    Db::commit();
                    return json_return('', 200, '同意操作成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '同意操作失败！');
                }

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } elseif ($data['type'] == 'reject') {
            //开启事务
            Db::startTrans();
            try {
                //验证
                $info = db::name('updetail')
                    ->alias('a')
                    ->join('shop_store m', 'a.store_id=m.id')
                    ->where('a.id', $data['up_id'])
                    ->where('a.status', '<>', 'finish')
                    ->field('a.user_id,a.user_level,a.user_up_level,a.order_id,a.store_id,m.store_name')
                    ->find();
                //修改状态
                $updetail = db::name('updetail')->where('id', $data['up_id'])->update(array('status' => 'reject', 'checktime' => time(), 'note' => $data['content']));

                //消息通知
                $m_data = array(
                    "user_id" => $info['user_id'],
                    "message" => "您的审核店家 " . $info['store_name'] . ' 已拒绝您的申请，请前往查看！',
                    "category" => 1,
                    "type" => 1,
                    "add_time" => time(),
                    "is_user_info" => 1
                );

                $m_m_bool = db::name('user_message')->insert($m_data);
                if ($updetail && $m_m_bool) {
                    // 提交事务
                    Db::commit();
//                         $redis = Redis();
//                         $red_dev_code = $redis->get("dev_code".$info['user_id']);
//                         if($red_dev_code){
//                             jgSend2($red_dev_code,$m_data['message'],["order_id"=>''],10016);
//                         }
                    return json_return('', 200, '拒绝操作成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '拒绝操作失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } else {
            return json_return('', 400, '审核参数状态有误！');
        }
    }


    /**
     * 店主审核升级记录
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @param integer $up_id 审核ID
     * @param string $type 审核类型:reject-拒绝
     * @param string $content 描述
     * @return bool
     * */
    public function store_check_list()
    {
        $data = array(
            'user_id' => $this->user_id,
            'up_id' => input('up_id'),
            'type' => input('type'),
            'content' => input('content')
        );

        if ($data['type'] == 'finish') {//同意升级
            //开启事务
            Db::startTrans();
            try {

                //todo 审核逻辑
                $up = db::name('updetail')
                    ->alias('up')
                    ->join('user u','up.user_id=u.id','left')
                    ->where('up.id',$data['up_id'])
                    ->find();
                $order = db::name('order')->where('order_id',$up['order_id'])->find();
                $pay_order = db::name('pay_order')->alias('p')
                    ->join('order o','o.order_id=p.order_ids')
                    ->field('p.*,o.order_sn')
                    ->where('p.store_order_id',$up['order_id'])->find();
                //修改付款订单状态
                $bool_pay_order = db::name('pay_order')->where('id', $pay_order['id'])->update(array('is_pay' => 1));

                //修改订单状态
                $bool_order = db::name('order')
                    ->where('order_id', 'in', $pay_order['order_ids'])
                    ->where(array('order_status' => 7, 'is_bbm' => 3))
                    ->update(array('order_status' => 3, 'pay_status' => 1, 'pay_id' => 4, 'pay_name' => '商铺资金支付', 'real_money' => $pay_order['total_goods_price'], 'pay_time' => time()));

                $store = db::name('store')->where('id',$up['store_id'])->find();


                //修改店铺资金
                $balance = $store['funds'];   //商家原资金
                $funds = $balance + $order['total_amount'];  //商家总资金
                $money = $funds-$pay_order['total_goods_price'];

                $bool_store = db::name('store')->where('id', $store['id'])->update(array('funds' => $money));

                //运营商奖励
                $flag = 1;
                if (!empty($up['operator_id']) && $up['is_operator']!=1) {
                    $flag = getOperatorReward($up, $pay_order['total_goods_price'], 0, 4);
                }


                $pay_data = ['余额','支付宝','微信'];
                //$balance = $store['funds'] - $pay_order['total_goods_price'];

                $bool_store_money = db::name('store_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $order['order_sn'],
                    'store_id' => $up['store_id'],
                    'trade_no' => '',
                    'type' => 1,
                    'pay_type' => $order['pay_id']-1, // 0余额 1支付宝 2微信'
                    'money' => $order['total_amount'],
                    'before_money' => $balance,
                    'after_money' => $funds,
                    'remark' => $pay_data[$order['pay_id']-1].'支付升级商品',
                    'createtime' => time()
                ));

                $bool_user_money = db::name('store_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $pay_order['order_sn'],
                    'store_id' => $up['store_id'],
                    'trade_no' => '',
                    'type' => 5,
                    'pay_type' => 3, // 0余额 1支付宝 2微信 3商家资金支付
                    'money' => -$pay_order['total_goods_price'],
                    'before_money' => $funds,
                    'after_money' => $money,
                    'remark' => '商铺资金支付-点亮审核通道',
                    'createtime' => time()
                ));



                $ret = checkUpgrade($data['up_id']);
                if($ret['state'] && $bool_pay_order && $bool_order && $bool_store && $bool_user_money && $bool_store_money&&$flag){
                    $redis = Redis();
                    $red_dev_code = $redis->get("dev_code" . $ret['data']['user_id']);
                    if ($red_dev_code) {
                        jgSend2($red_dev_code, $ret['msg_data']['message'], '', 10016);
                    }
                    Db::commit();
                    return json_return('', 200, '同意操作成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '同意操作失败！');
                }

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } elseif ($data['type'] == 'reject') {
            //开启事务
            Db::startTrans();
            try {
                //验证
                $info = db::name('updetail')
                    ->alias('a')
                    ->join('shop_store m', 'a.store_id=m.id')
                    ->where('a.id', $data['up_id'])
                    ->where('a.status', '<>', 'finish')
                    ->field('a.user_id,a.user_level,a.user_up_level,a.order_id,a.store_id,m.store_name')
                    ->find();
                //修改状态
                $updetail = db::name('updetail')->where('id', $data['up_id'])->update(array('status' => 'reject', 'checktime' => time(), 'note' => $data['content']));

                //消息通知
                $m_data = array(
                    "user_id" => $info['user_id'],
                    "message" => "您的审核店家 " . $info['store_name'] . ' 已拒绝您的申请，请前往查看！',
                    "category" => 1,
                    "type" => 1,
                    "add_time" => time(),
                    "is_user_info" => 1
                );

                $m_m_bool = db::name('user_message')->insert($m_data);
                if ($updetail && $m_m_bool) {
                    // 提交事务
                    Db::commit();
//                         $redis = Redis();
//                         $red_dev_code = $redis->get("dev_code".$info['user_id']);
//                         if($red_dev_code){
//                             jgSend2($red_dev_code,$m_data['message'],["order_id"=>''],10016);
//                         }
                    return json_return('', 200, '拒绝操作成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '拒绝操作失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } else {
            return json_return('', 400, '审核参数状态有误！');
        }
    }



    /*
  * 用户支付礼包(弃用)
  * */
    public function pay_buy_upgrade()
    {
        $data = array(
            // 'user_id' => $this->user_id,
            'user_id' => $this->user_id,
            'pay_id' => input('pay_id/d', 1),//支付方式，1=余额，2=支付宝
            'password' => input('password'),//安卓支付密码
            'pwd' => input('pwd'),//ios支付密码
            'pay_order' => input('pay_order'),//付款订单id
            'store_id' => input('store_id'),//店铺id
            'up_id' => input('up_id')//用户升级记录id
        );
        if ($data['password'] == '') {
            $data['password'] = $data['pwd'];
        }
        //用户信息
        $user = db::name('user')
            ->where('id', $data['user_id'])
            ->field('id,username,mobile,user_money,level,payment_salt,payment_password,parent,operator_id')
            ->find();
        //付款订单信息
        $pay_order = db::name('pay_order')->where(array('id' => $data['pay_order'], 'is_pay' => 0))->find();
        if (empty($pay_order)) {
            return json_return('', 400, '订单信息有误！');
        }
        //店铺信息
        $store = db::name('store')
            ->alias('a')
            ->join('shop_user b', 'a.user_id=b.id')
            ->where('a.id', $data['store_id'])
            ->field('a.id,a.funds,a.checknum,a.user_id,b.level')
            ->find();
        if (empty($store)) {
            return json_return('', 400, '店铺信息有误！');
        }
        //验证余额是否足够
        $order_amount = $pay_order['total_goods_price'] + $pay_order['total_shipping_price'];

        if ($data['pay_id'] == '1') {
            //支付密码验证
            if ($user['payment_salt'] == '') {
                return json_return('', 10001, '支付密码为空');
            }
            //验证支付密码
            $password = md5(md5($user['payment_salt'] . $data['password']));
            if ($password != $user['payment_password']) {
                return json_return('', 400, '支付密码错误');
            }

            if ($user['user_money'] < $order_amount) {
                return json_return('', 400, '可用余额不足！');
            }
            //开启事务
            Db::startTrans();
            try {
                //查询订单信息
                $order = db::name('order')
                    ->alias('a')
                    ->join('shop_order_goods b', 'a.order_id=b.order_id')
                    ->join('shop_goods c', 'b.goods_id=c.goods_id')
                    ->where('a.order_id', $pay_order['order_ids'])
                    ->field('a.store_id,b.goods_id,c.goods_number as num,c.sales_volume')
                    ->find();

                //修改付款订单状态
                $bool_pay_order = db::name('pay_order')->where('id', $data['pay_order'])->update(array('is_pay' => 1));
                //修改订单状态
                $bool_order = db::name('order')
                    ->where('order_id', 'in', $pay_order['order_ids'])
                    ->where(array('order_status' => 7, 'is_bbm' => 1))
                    ->update(array('order_status' => 3, 'pay_status' => 1, 'pay_id' => 1, 'pay_name' => '余额', 'real_money' => $order_amount, 'pay_time' => time()));
                //修改用户余额
                $balance = $user['user_money'] - $order_amount;
                $bool_user = db::name('user')->where('id', $data['user_id'])->update(array('user_money' => $balance));
                //添加用户的资金变化记录
                $discount = get_user_discount($data['user_id']);
                $bool_user_money = db::name('user_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $pay_order['order_ids'],
                    'money' => -$order_amount,
                    'type' => 2,
                    'pay_type' => 1,
                    'before_money' => $user['user_money'],
                    'after_money' => $balance,
                    'before_discount' => $discount['discount'] ?: '0',
                    'after_discount' => $discount['discount'] ?: '0',
                    'before_discount_all' => $discount['discountAll'] ?: '0',
                    'after_discount_all' => $discount['discountAll'] ?: '0',
                    'remark' => '余额支付升级商品',
                    'createtime' => time()
                ));


                //todo 审核逻辑
                $ret = checkUpgrade($data['up_id'], $data['user_id']);

                //运营商奖励
//                $flag=1;
//                if(!empty($user['operator_id'])){
//                    $flag=getOperatorReward($user,$order_amount,1,4);
//                }

                if ($bool_pay_order && $bool_order && $bool_user && $bool_user_money && $ret['state']) {
                    // 提交事务
                    Db::commit();
                    $redis = Redis();
                    $red_dev_code = $redis->get("dev_code" . $ret['data']['user_id']);
                    if ($red_dev_code) {
                        jgSend2($red_dev_code, $ret['msg_data']['message'], '', 10016);
                    }

                    return json_return('', 200, '支付成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '支付失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } elseif ($data['pay_id'] == '2') {
//            vendor('Alipay.Alipay');
//            $alipay = new \Alipay();
//            $btype = "buy_upgrade_goods";
//            $price = '' . $order_amount;
//            $order_sn = $pay_order['order_num'];
//            $body = "支付凡商优店帮帮商品，订单号为：${order_sn}，支付金额为：${price}元";
//            $subject = "支付凡商优店帮帮商品，订单号为：" . $order_sn;
//            $orderInfo = $alipay->tradeAppPay($body, $subject, $order_sn, $price, $btype);
//            exit('{"data":"' . $orderInfo . '","msg":"success","code":200}');
            vendor('Alipay.Alipay');
            $order_amount = '0.01';
            $alipay = new \Alipay();
            $btype = "operator_pay";
            $price = $order_amount;
            //$order_sn = $pay_order['order_num'];
            $order_sn="12545474353445466";
            $body = "支付凡商优店帮帮商品，订单号为：${order_sn}，支付金额为：${price}元";
            $subject = "支付凡商优店帮帮商品，订单号为：" . $order_sn;
            $orderInfo = $alipay->tradeAppPay($body, $subject, $order_sn, $price, $btype);
            return $orderInfo;

        } else {
            return json_return('', 400, '非法请求');
        }
    }



    /**
     * 支付通道
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="pay_id", type="integer", required=true, description="支付方式，1=余额，2=支付宝")
     * @ApiParams   (name="password", type="integer", required=false, description="安卓支付密码")
     * @ApiParams   (name="pwd", type="integer", required=false, description="ios支付密码")
     * @ApiParams   (name="pay_order", type="integer", required=true, description="付款订单ID")
     * @ApiParams   (name="store_id", type="integer", required=true, description="店铺ID")
     * @ApiParams   (name="up_id", type="integer", required=true, description="用户升级记录ID")
     * */
    public function pay_platform_upgrade()
    {
        $data = array(
            'user_id' => $this->uid,
            'pay_id' => input('pay_id/d', 1),//支付方式，1=余额，2=支付宝， 4=商铺资金
            'password' => input('password'),//安卓支付密码
            'pwd' => input('pwd'),//ios支付密码
            'pay_order' => input('pay_order'),//付款订单id
            'store_id' => input('store_id'),//店铺id
            'up_id' => input('up_id')//用户升级记录id
        );
        if ($data['password'] == '') {
            $data['password'] = $data['pwd'];
        }
        //用户信息
        $user = db::name('user')
            ->where('id', $data['user_id'])
            ->field('id,username,mobile,user_money,level,payment_salt,payment_password,parent,operator_id,is_operator')
            ->find();
        //付款订单信息
        $pay_order = db::name('pay_order')->where(array('id' => $data['pay_order']))->find();
        if (empty($pay_order)) {
            return json_return('', 400, '订单信息有误！');
        }
        //店铺信息
        $store = db::name('store')
            ->alias('a')
            ->join('shop_user b', 'a.user_id=b.id')
            ->where('a.id', $data['store_id'])
            ->field('a.id,a.funds,a.checknum,a.user_id,b.level')
            ->find();
        if (empty($store)) {
            return json_return('', 400, '店铺信息有误！');
        }
        //验证余额是否足够
        $order_amount = $pay_order['total_goods_price'] + $pay_order['total_shipping_price'];

        //查询订单信息
        $order = db::name('order')
            ->alias('a')
            ->join('shop_order_goods b', 'a.order_id=b.order_id')
            ->join('shop_goods c', 'b.goods_id=c.goods_id')
            ->where('a.order_id', $pay_order['store_order_id'])
            ->field('a.store_id,b.goods_id,c.goods_number as num,c.sales_volume,a.total_amount,a.order_sn,a.pay_id')
            ->find();
        //dump($order);die;
        if ($data['pay_id'] == '1') {
            //支付密码验证
            if ($user['payment_salt'] == '') {
                return json_return('', 10001, '支付密码为空');
            }
            //验证支付密码
            $password = md5(md5($user['payment_salt'] . $data['password']));
            if ($password != $user['payment_password']) {
                return json_return('', 400, '支付密码错误');
            }

            if ($user['user_money'] < $order_amount) {
                return json_return('', 400, '可用余额不足！');
            }
            //开启事务
            Db::startTrans();
            try {

                //修改付款订单状态
                $bool_pay_order = db::name('pay_order')->where('id', $data['pay_order'])->update(array('is_pay' => 1));
                //修改订单状态
                $bool_order = db::name('order')
                    ->where('order_id', 'in', $pay_order['order_ids'])
                    ->where(array('order_status' => 7, 'is_bbm' => 3))
                    ->update(array('order_status' => 3, 'pay_status' => 1, 'pay_id' => 1, 'pay_name' => '余额', 'real_money' => $order_amount, 'pay_time' => time()));
                //修改用户余额
                $balance = $user['user_money'] - $order_amount;
                $bool_user = db::name('user')->where('id', $data['user_id'])->update(array('user_money' => $balance));
                //添加用户的资金变化记录
                $discount = get_user_discount($data['user_id']);
                $bool_user_money = db::name('user_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $pay_order['order_ids'],
                    'money' => -$order_amount,
                    'type' => 2,
                    'pay_type' => 1,
                    'before_money' => $user['user_money'],
                    'after_money' => $balance,
                    'before_discount' => $discount['discount'] ?: '0',
                    'after_discount' => $discount['discount'] ?: '0',
                    'before_discount_all' => $discount['discountAll'] ?: '0',
                    'after_discount_all' => $discount['discountAll'] ?: '0',
                    'remark' => '余额支付升级商品',
                    'createtime' => time()
                ));

                //修改商铺资金
                $funds = $store['funds'] + $order['total_amount'];
                $bool_store = db::name('store')->where('id', $data['store_id'])->update(array('funds' => $funds));
                $pay_data = ['余额', '支付宝', '微信'];
                $bool_store_money = db::name('store_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $order['order_sn'],
                    'store_id' => $data['store_id'],
                    'trade_no' => '',
                    'type' => 1,
                    'pay_type' => $order['pay_id'] - 1, // 0余额 1支付宝 2微信'
                    'money' => $order['total_amount'],
                    'before_money' => $store['funds'],
                    'after_money' => $funds,
                    'remark' => $pay_data[$order['pay_id'] - 1] . '支付升级商品',
                    'createtime' => time()
                ));

                //todo 审核逻辑
                $ret = checkUpgrade($data['up_id'], $data['user_id']);

                //运营商奖励
//                $flag = 1;
//                if (!empty($user['operator_id']) && $user['is_operator']!=1) {
//                    $flag = getOperatorReward($user, $order_amount, 1, 4);
//                }


                if ($bool_pay_order && $bool_order && $bool_user && $bool_user_money && $ret['state'] && $bool_store && $bool_store_money && $flag) {

                    // 提交事务
                    Db::commit();
                    $redis = Redis();
                    $red_dev_code = $redis->get("dev_code" . $ret['data']['user_id']);
                    if ($red_dev_code) {
                        jgSend2($red_dev_code, $ret['msg_data']['message'], '', 10016);
                    }

                    return json_return('', 200, '支付成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '支付失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } elseif ($data['pay_id'] == '2') {
            $alipay = new \Alipay\Alipay();
            $btype = "buy_upgrade_platform";
            $price = '' . $order_amount;
            $order_sn = $pay_order['order_num'];
            $body = "支付凡商优店帮帮商品，订单号为：${order_sn}，支付金额为：${price}元";
            $subject = "支付凡商优店帮帮商品，订单号为：" . $order_sn;
            $orderInfo = $alipay->tradeAppPay($body, $subject, $order_sn, $price, $btype);
            exit('{"data":"' . $orderInfo . '","msg":"success","code":200}');
        } elseif ($data['pay_id'] == '4') {
            //支付密码验证
            if ($user['payment_salt'] == '') {
                return json_return('', 10001, '支付密码为空');
            }
            //验证支付密码
            $password = md5(md5($user['payment_salt'] . $data['password']));
            if ($password != $user['payment_password']) {
                return json_return('', 400, '支付密码错误');
            }

            if ($store['funds'] < $order_amount) {
                return json_return('', 400, '可用资金不足！');
            }
            //开启事务
            Db::startTrans();
            try {
                //修改付款订单状态
                $bool_pay_order = db::name('pay_order')->where('id', $data['pay_order'])->update(array('is_pay' => 1));
                //修改订单状态
                $bool_order = db::name('order')
                    ->where('order_id', 'in', $pay_order['order_ids'])
                    ->where(array('order_status' => 7, 'is_bbm' => 3))
                    ->update(array('order_status' => 3, 'pay_status' => 1, 'pay_id' => 4, 'pay_name' => '商铺资金支付', 'real_money' => $order_amount, 'pay_time' => time()));
                //修改店铺资金
                $balance = $store['funds'] - $order_amount;
                $bool_user = db::name('store')->where('id', $data['store_id'])->update(array('funds' => $balance));

                $bool_user_money = db::name('store_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $order['order_sn'],
                    'store_id' => $data['store_id'],
                    'trade_no' => '',
                    'type' => 5,
                    'pay_type' => 3, // 0余额 1支付宝 2微信 3商家资金支付
                    'money' => -$order_amount,
                    'before_money' => $store['funds'],
                    'after_money' => $balance,
                    'remark' => '商铺资金支付-点亮审核通道',
                    'createtime' => time()
                ));

                //修改商铺资金
                $funds = $balance + $order['total_amount'];
                $bool_store = db::name('store')->where('id', $data['store_id'])->update(array('funds' => $funds));
                $pay_data = ['余额', '支付宝', '微信'];
                $bool_store_money = db::name('store_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $order['order_sn'],
                    'store_id' => $data['store_id'],
                    'trade_no' => '',
                    'type' => 1,
                    'pay_type' => $order['pay_id'] - 1, // 0余额 1支付宝 2微信'
                    'money' => $order['total_amount'],
                    'before_money' => $balance,
                    'after_money' => $funds,
                    'remark' => $pay_data[$order['pay_id'] - 1] . '支付升级商品',
                    'createtime' => time()
                ));

                //todo 审核逻辑
                $ret = checkUpgrade($data['up_id'], $data['user_id']);

                //商家资金支付-运营商奖励
//                $flag = 1;
//                if (!empty($user['operator_id'])&& $user['is_operator']!=1) {
//                    $flag = getOperatorReward($user, $order_amount, 3, 4);
//                }

                if ($bool_pay_order && $bool_order && $bool_user && $bool_user_money && $ret['state'] && $bool_store && $bool_store_money && $flag) {
                    // 提交事务
                    Db::commit();
                    $redis = Redis();
                    $red_dev_code = $redis->get("dev_code" . $ret['data']['user_id']);
                    if ($red_dev_code) {
                        jgSend2($red_dev_code, $ret['msg_data']['message'], '', 10016);
                    }

                    return json_return('', 200, '支付成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '支付失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } else {
            return json_return('', 400, '非法请求');
        }
    }







    /*
        * @wjj
        * 发货页面数据
        * */
    public function go_deliver_goods()
    {
        $order_sn = input('order_sn');
        if (empty($order_sn)) {
            return json_return('', 400, '订单号不能为空');
        }
        $order = Db::name('order')->field('order_id,add_time,pay_time,order_sn')
            ->where(['order_sn' => $order_sn, 'order_status' => 1, 'pay_time' => ['>', 0]])
            ->find();
        if (empty($order)) {
            return json_return('', 400, '查无此订单');
        }
        $order['add_time'] = date('Y-m-d H:i:s', $order['add_time']);
        $order['pay_time'] = date('Y-m-d H:i:s', $order['pay_time']);
        $express = Db::name('shipping')->field('shipping_id,shipping_name,shipping_code')->limit(10)->select();
        $data = ['orderData' => $order, 'expressData' => $express];
        return json_return($data, 200, '获取成功');
    }

    /*
     * 确认发货
     * @user wj
     * */
    public function confirm_delivery()
    {
        $order_sn = input('order_sn');
        $express_id = input('express_id');
        $express_code = input('express_code', 0);
        $express_name = input('express_name', 0);
        $express_number = input('express_number', 0);
        if (empty($order_sn))
            return json_return('', 400, '订单号不能为空');
        $order = Db::name('order')->field('order_id,order_sn,user_id,store_id')
            ->where(['order_sn' => $order_sn, 'order_status' => 1, 'pay_time' => ['>', 0]])
            ->find();
        if (empty($order)) {
            return json_return('', 400, '查无此订单');
        }
        $storeType = Db::name('store')->where('id', $order['store_id'])->value('type');
        if ($storeType == 'onlin') {
            if (empty($express_id))
                return json_return('', 400, '快递公司不能为空');
            if (empty($express_name))
                return json_return('', 400, '快递名称不能为空');
            if (empty($express_code))
                return json_return('', 400, '快递代码不能为空');
            if (!preg_match("/^[0-9a-zA-Z]{10,}$/", $express_number))
                return json_return('', 400, '快递单号不合规');
            if (empty($express_number))
                return json_return('', 400, '快递单号不能为空');
        }
        $goodsData = Db::name('order_goods')->field('spec_key_name,goods_name,goods_num')->where('order_id', $order['order_id'])->select();
        $goodsDesc = array();
        foreach ($goodsData as $k => $v) {
            if ($v['spec_key_name']) {
                $goodsDesc[] = $v['goods_name'] . '-' . $v['spec_key_name'] . '×' . $v['goods_num'];
            } else {
                $goodsDesc[] = $v['goods_name'] . '×' . $v['goods_num'];
            }

        }
        $goodsDesc = implode(',', $goodsDesc);
        $data = [
            'order_status' => 2,
            'shipping_status' => 1,
            'shipping_id' => $express_id,
            'shipping_num' => $express_number,
            'shipping_code' => $express_code,
            'shipping_name' => $express_name,
            'shipping_time' => time()
        ];
        $message = '您购买的' . $goodsDesc . "商品，商家已成功发货，请注意收货！";
        $messageData = [
            'user_id' => $order['user_id'],
            'order_id' => $order['order_id'],
            'order_sn' => $order['order_sn'],
            'message' => $message,
            'category' => 1,
            'type' => 4,
            'add_time' => time()
        ];

        Db::startTrans();
        try {
            Db::name('order')->where(['order_sn' => $order_sn, 'order_status' => 1])->update($data);
            Db::name('user_message')->insert($messageData);
            //Db::name('user_message')->insert($messageLogistics);
            Db::commit();
            return json_return('', 200, '处理成功');
        } catch (\Exception $e) {
            Db::rollback();
            return json_return('', 400, '处理失败');
        }
    }

    /**
     *
     *帮帮卖商品详情
     * */
    public function store_goods_info()
    {
        $uid = $this->user_id;
        $goods_id = input('good_id');
        $store_id = input('store_id');
        if (empty($goods_id)) {
            return json_return('', 400, '参数有误！');
        }
        if (empty($store_id)) {
            return json_return('', 400, '参数有误！');
        }
        $data = array();
        $goods = array();
        //商品信息
        $goods = db::name('goods')
            ->where(array('goods_id' => $goods_id, 'is_bbm' => 1))
            ->field('goods_id,goods_sn,goods_name,goods_number,market_price,shop_price,fare,goods_brief,goods_desc,goods_thumb,goods_img,goods_unit')
            ->find();
        if (empty($goods)) {
            return json_return('', 400, '商品id有误！');
        }

        $images = array();
        if ($goods['goods_img']) {
            $images = explode(',', $goods['goods_img']);
            foreach ($images as $k => $v) {
                $images[$k] = add_image_pre($v);
            }
        }
        $goods['iamges'] = $images;
        $goods['goods_thumb'] = add_image_pre($goods['goods_thumb']);

        $descs = array();
        if ($goods['goods_desc']) {
            $descs = explode(',', $goods['goods_desc']);
            foreach ($descs as $k => $v) {
                $descs[$k] = add_image_pre($v);
            }
        }
        $goods['goods_info'] = $descs;

        //店主地址
        $city = db::name('store')->where('user_id', $uid)->value('city');
        $goods['city'] = $city ? $city : '温州';
        //销量
        $count = db::name('order')->where(array('user_id' => $uid, 'is_bbm' => 1))->where('order_status', '<>', 7)->count();
        $goods['sell_num'] = $count ? $count : 0;

        //店铺信息
        $store = db::name('store')->where(array('id' => $store_id))->field('id,mobile,qq')->find();
        $goods['store_id'] = $store_id;
        $goods['mobile'] = $store['mobile'];
        $goods['qq'] = $store['qq'];

        unset($goods['goods_img']);
        unset($goods['goods_desc']);
        return json_return($goods);
    }

    /**
     *
     *店铺订单
     * */
    public function store_order()
    {
        $data = array(
            //'user_id' => input('user_id', 63),
            'user_id' => $this->user_id,
            'type' => input('type', 1),
            'key' => '',
            'page' => input('page', 1) ?: 1,
            'limit' => input('limit/d', 10) ?: 10
        );

        if ($data['type'] > 7 || $data['type'] < 1) {
            return json_return('', 400, '订单类型错误');
        }
        $list = StoreModel::get_order($data);
        return json_return_layui($list, $list['count']);
    }

    /**
     *
     *申请入驻弹窗信息
     * */
    public function apply_pop()
    {
        //获取入驻费用
        $data = Db::name('store_config')->field('season_fee,mid_year_fee,year_fee')->order('id desc')->find();
        if ($data) {
            $list = array(
                'title' => '免费入驻 管理费首月免费',
                'info' => '管理费标准：年/' . $data['year_fee'] . '元、半年/' . $data['mid_year_fee'] . '元、季/' . $data['year_fee'] . '元',
            );
            return json_return($list);
        } else {
            return json_return('', 400, '系统参数设置有误！');
        }
    }

    /**
     *
     *店铺入驻申请资料
     * */
    public function store_certification_info()
    {
        $uid = $this->user_id;
        $res = array();
        $res = Db::name("store")
            ->where('user_id', $uid)
            ->field('id,realname,id_card,bankcard,mobile,face_spot,state,check_token,tokentime,type,cat_id')
            ->find();
        if ($res['cat_id']) {
            //线下店铺所属类别
            $cat_name = Db::name('goods_category')->where('cat_id', $res['cat_id'])->value('cat_name');
        } else {
            $cat_name = null;
        }


        //验证是否开启人脸识别
        $set = Db::name('store_config')->order('id desc')->find();
        if ($set['face_check'] == 'Y') {
            if ($res) {
                if (!empty($res['check_token']) && $res['face_spot'] == 'uncheck') {
                    if ($res['tokentime'] + 30 * 60 < time()) {
                        $res['check_token'] = $this->getFaceAuthToken(json_encode(array('Name' => $res['realname'], 'IdentificationNumber' => $res['id_card'])));
                    }
                }
            }
        }
        if ($res) {
            $res['cat_name'] = $cat_name;
            $res['face_check'] = $set['face_check'] ?: 'N';
        }

        return json_return($res);
    }

    /**
     *
     *店铺首页
     * */
    public function store_index()
    {
         $uid = $this->user_id;
        //$uid = input('user_id', 68);
        $cate = Goods::WIN_LOCATION_CATE;
        $storeData = db::name("user")
            ->alias('a')
            ->join('shop_store o', 'a.id=o.user_id', 'left')
            ->join('goods g', 'a.id=g.user_id and g.is_bbm=' . $cate, 'left')
            //->join('goods g','a.id=g.user_id and g.cat_id='.$cate,'left')
            ->where('a.id', $uid)
            //->where('a.state','finish')
            //->where('o.store_status','in',array('onlin','offlin'))
            ->field('o.id,a.id as user_id,o.store_name,o.logo_image,o.funds,o.win_funds,o.type,a.level,o.manager_fee_time,o.state as store_state,a.store_status,a.level,o.cat_id,o.is_all,g.goods_number as num,note')
            ->find();
        if (!($storeData['store_state'] == 'reject')) {
            if (empty($storeData)) {
                return json_return($storeData, 1001, '您还没有入驻店铺！');
            }
            if ($storeData['store_status'] == 'personal') {
                return json_return($storeData, 1002, '您还没有入驻店铺！');
            }
            if ($storeData['store_state'] == 'uncheck') {
                return json_return($storeData, 1003, '您提交的入驻资料还未审核！');
            }
//            if ($storeData['store_state'] == 'reject') {
//                return json_return($storeData, 400, '您提交的入驻资料已被拒绝！');
//            }
        }

        $storeData['logo_image'] = add_image_pre($storeData['logo_image']);
        $storeData['num'] = $storeData['num'] - getWinStock($uid);

        //待付款订单数量
        $unpay_order = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 0, 'is_bbm' => 0, 'order_status' => 0))->count();
        //待发货订单数量
        $ungive_order = db::name('order')->where(array('store_id' => $storeData['id'], 'order_status' => 1, 'is_bbm' => 0, 'pay_status' => 1))->count();
        //待收货订单数量
        $unget_order = db::name('order')->where(array('store_id' => $storeData['id'], 'order_status' => 2, 'is_bbm' => 0, 'pay_status' => 1))->count();
        //被投诉订单数量
        $complaint_order = db::name('complaint')->where('b_uids', $storeData['user_id'])->count();

        $storeData['unpay_order'] = $unpay_order ? $unpay_order : 0;
        $storeData['ungive_order'] = $ungive_order ? $ungive_order : 0;
        $storeData['unget_order'] = $unget_order ? $unget_order : 0;
        $storeData['complaint_order'] = $complaint_order ? $complaint_order : 0;

        //今日订单数
        $where = array();
        $where['pay_time'] = array('between', array(strtotime(date('Y-m-d', time())), time()));
        $now_order = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 1, 'is_bbm' => array('in', array(0, 2))))->where($where)->count();
        //今日成交额
        $now_amount = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 1, 'is_bbm' => array('in', array(0, 2))))->where($where)->sum('real_money');
        //昨日订单数
        $list = array();
        $stime = strtotime(date("Y-m-d", strtotime("-1 day")));
        $etime = strtotime(date("Y-m-d 23:59:59", strtotime("-1 day")));
        $list['pay_time'] = array('between', array($stime, $etime));
        $yesterday_order = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 1, 'is_bbm' => array('in', array(0, 2))))->where($list)->count();
        //昨日成交额
        $yesterday_amount = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 1, 'is_bbm' => array('in', array(0, 2))))->where($list)->sum('real_money');

        $storeData['now_order'] = $now_order ? $now_order : 0;
        $storeData['now_amount'] = $now_amount ? $now_amount : 0;
        $storeData['yesterday_order'] = $yesterday_order ? $yesterday_order : 0;
        $storeData['yesterday_amount'] = $yesterday_amount ? $yesterday_amount : 0;

        //上架商品数量
        $onlin_goods = db::name('goods')->where(array('store_id' => $storeData['id'], 'goods_status' => 'onlin'))->where('is_delete', 0)->count();
        //下架商品数量
        $obtained_goods = db::name('goods')->where(array('store_id' => $storeData['id'], 'goods_status' => 'obtained'))->where('is_delete', 0)->count();
        //审核中商品数量
        $uncheck_goods = db::name('goods')->where(array('store_id' => $storeData['id'], 'goods_status' => 'uncheck'))->where('is_delete', 0)->count();
        //未通过商品数量
        $reject_goods = db::name('goods')->where(array('store_id' => $storeData['id'], 'goods_status' => 'reject'))->where('is_delete', 0)->count();
        $storeData['onlin_goods'] = $onlin_goods ? $onlin_goods : 0;
        $storeData['obtained_goods'] = $obtained_goods ? $obtained_goods : 0;
        $storeData['uncheck_goods'] = $uncheck_goods ? $uncheck_goods : 0;
        $storeData['reject_goods'] = $reject_goods ? $reject_goods : 0;

        //提现记录
        $withdraw_info = array();
        $map = array();
        $starttime = strtotime(date("Y-m-d", strtotime("-3 day")));
        $map['create_time'] = array('between', array($starttime, time()));
        $withdraw_info = db::name('store_withdrawal')->where(array('store_id' => $storeData['id'], 'withdraw_status' => 2))->where($map)->order('create_time desc')->field('money,create_time')->find();
        if ($withdraw_info) {
            $withdraw_info['time'] = $withdraw_info['create_time'] ? time_tran($withdraw_info['create_time']) : '';
        }

        $storeData['withdraw_info'] = $withdraw_info ? $withdraw_info : null;

        //待审核数量
        $uncheck_num = db::name('updetail')->where(array('store_uid' => $uid))->where('status', 'in', array('uncheck'))->count();

        $storeData['uncheck_num'] = $uncheck_num ? $uncheck_num : 0;

        //管理费是否到期
        $storeData['managerfee_time'] = time() > $storeData['manager_fee_time'] ? '1' : '0';
        if (time() > $storeData['manager_fee_time']) {
            $storeData['overdue_time'] = -1;
        } else {
            $storeData['overdue_time'] = ceil(($storeData['manager_fee_time'] - time()) / 3600 / 24);
        }
        $funds = $storeData['funds'] + $storeData['win_funds'];
        $storeData['funds'] = (string)round($funds, 2);
        return json_return($storeData);
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

    /**
     * 资金标签
     */
    public function moneyLabel()
    {
        $data = array(array('name' => "全部", 'value' => 0), array('name' => "提现", 'value' => 1), array('name' => "收入", 'value' => 2));
        return json_return($data, 200, '获取成功');
    }


    /**
     *
     *预览店铺商品信息
     * */
    public function preview_store_goods()
    {
        $store_id=Db::name('store')->where('user_id',$this->user_id)->getField('id');
        //$store_id = input('store_id', 22);

        $keyword = input('keyword', '');
        if (empty($store_id)) {
            return json_return('', 400, '参数有误！');
        }
        $store = array();
        $data = array();
        $goods = array();
        //店铺信息
        $store = db::name('store')
            ->where(array('id' => $store_id, 'state' => 'finish'))
            ->field('id,store_name,logo_image,cover_status,self_cover_image,type')
            ->find();
        if ($store) {
            $store['logo_image'] = add_image_pre($store['logo_image']);
            $store['self_cover_image'] = add_image_pre($store['self_cover_image']);
        }
        if (empty($keyword)) {
            //店铺商品
            $goods = db::name('goods')->where(array('store_id' => $store_id, 'goods_status' => 'onlin', 'is_delete' => 0))->whereOr(array('is_bbm' => 1))->field('goods_id,goods_name,shop_price,goods_thumb,promotion_method,is_bbm')->select();
            if ($goods) {
                foreach ($goods as $key => $value) {
                    $goods[$key]['goods_thumb'] = add_image_pre($value['goods_thumb']);
                }
            }
        } else {
            //店铺商品
            $goods = db::name('goods')->where(array('store_id' => $store_id, 'goods_status' => 'onlin', 'is_delete' => 0))->where('goods_name', 'like', "%" . $keyword . "%")->whereOr(array('is_bbm' => 1))->field('goods_id,goods_name,shop_price,goods_thumb,promotion_method,is_bbm')->select();
            if ($goods) {
                foreach ($goods as $key => $value) {
                    $goods[$key]['goods_thumb'] = add_image_pre($value['goods_thumb']);
                }
            }
        }

        $data['store'] = $store;
        $data['goods'] = $goods;

        return json_return($data);


    }

    public function shop()
    {

        return $this->fetch();
    }

    /*
  * 续费
  * */
    public function store_renewal_fee()
    {
        $uid = $this->user_id;
        $store_id = input('store_id');
        if (!$store_id) {
            return json_return('', 400, '参数错误');
        }
        //用户信息
        $user = db::name('user')->where('id', $uid)->field('user_money')->find();
        //费用设置信息
        $set = db::name('store_config')->order('id desc')->find();

        //$log_count = db::name('managerfee')->where('user_id',$uid)->count();
        $store = db::name('store')->where('user_id', $uid)->field('manager_fee_start,manager_fee_time')->find();

        $data = array(
            'store_id' => $store_id,
            //'month_fee' => $set['month_fee'],
            'season_fee' => $set['season_fee'],
            'mid_year_fee' => $set['mid_year_fee'],
            'year_fee' => $set['year_fee'],
            'user_money' => $user['user_money'],
            'manager_fee_start' => $store['manager_fee_start'],
            'manager_fee_end' => $store['manager_fee_time'],
        );

        return json_return($data);
    }

    /*
 * 店铺支付续费费用
 * */
    public function pay_renewal_fee()
    {
        $data = array(
            'user_id' => $this->user_id,
            'pay_id' => input('pay_id/d', 1),//支付方式，1=余额，2=支付宝
            'password' => input('password'),//安卓支付密码
            'pwd' => input('pwd'),//ios支付密码
            //'fee_type' => input('fee_type'),//支付费用类型，1=年费，2=月费
            'fee_type' => input('fee_type'),//支付费用类型，1=半年费，2=季度费 3=年费
            'store_id' => input('store_id')//店铺id
        );
        if ($data['password'] == '') {
            $data['password'] = $data['pwd'];
        }
        //用户信息
        $user = db::name('user')
            ->where('id', $data['user_id'])
            ->field('id,user_money,level,parent,payment_salt,payment_password,operator_id,username,mobile')
            ->find();
        if (empty($data['fee_type'])) {
            return json_return('', 400, '请选择缴费金额！');
        }
        //店铺信息
        $store = db::name('store')->where('id', $data['store_id'])->field('id,manager_fee_time')->find();
        if (empty($store)) {
            return json_return('', 400, '店铺信息有误！');
        }
        //费用设置信息
        $set = db::name('store_config')->order('id desc')->find();
        $fee_start = time();
        if ($fee_start < $store['manager_fee_time']) {
            $fee_start = $store['manager_fee_time'];
        }
        if ($data['fee_type'] == 1) {
            $fee = $set['mid_year_fee'];
            $type = 'mid_year';
            $remark = '余额支付管理半年费用';
            $message = $set['mid_year_fee'] . '元/半年';
            $manager_fee_time = getManagerDays($fee_start, 6) + 24 * 3600 - 1;
        } elseif ($data['fee_type'] == 2) {
            $fee = $set['season_fee'];
            $type = 'season';
            $remark = '余额支付管理季度费';
            $message = $set['season_fee'] . '元/季度';
            $manager_fee_time = getManagerDays($fee_start, 3) + 24 * 3600 - 1;
        } else {
            $fee = $set['year_fee'];
            $type = 'year';
            $remark = '余额支付管理年费';
            $message = $set['year_fee'] . '元/年';
            $manager_fee_time = getManagerDays($fee_start, 12) + 24 * 3600 - 1;
        }

        if ($data['pay_id'] == '1') {
            //支付密码验证
            if ($user['payment_salt'] == '') {
                return json_return('', 10001, '支付密码为空');
            }
            //验证支付密码
            $password = md5(md5($user['payment_salt'] . $data['password']));
            if ($password != $user['payment_password']) {
                return json_return('', 400, '支付密码错误');
            }
            //验证余额是否足够
            if ($user['user_money'] < $fee) {
                return json_return('', 400, '可用余额不足！');
            }
            //开启事务
            Db::startTrans();
            try {
                //创建缴费记录
                $manager = array(
                    'order_sn' => d_create_order_num(),
                    'user_id' => $data['user_id'],
                    'store_id' => $data['store_id'],
                    'type' => $type,
                    'fee' => $fee,
                    'status' => 'pay',
                    'createtime' => time(),
                    'expiretime' => $manager_fee_time
                );
                $bool_managerfee = db::name('managerfee')->insertGetId($manager);
                //修改用户余额
                $balance = $user['user_money'] - $fee;
                $bool_user = db::name('user')->where('id', $data['user_id'])->update(array('user_money' => $balance));
                //添加用户的资金变化记录
                $discount = get_user_discount($data['user_id']);
                $bool_user_money = db::name('user_money_log')->insertGetId(array(
                    'user_id' => $data['user_id'],
                    'order_sn' => $manager['order_sn'],
                    'money' => -$fee,
                    'type' => 7,
                    'pay_type' => 1,
                    'before_money' => $user['user_money'],
                    'after_money' => $balance,
                    'before_discount' => $discount['discount'] ?: '0',
                    'after_discount' => $discount['discount'] ?: '0',
                    'before_discount_all' => $discount['discountAll'] ?: '0',
                    'after_discount_all' => $discount['discountAll'] ?: '0',
                    'remark' => $remark,
                    'createtime' => time()
                ));
                //修改店铺管理费到期时间
                $bool_store = db::name('store')->where('id', $data['store_id'])->update(array('manager_fee_start' => time(), 'manager_fee_time' => $manager_fee_time));
                //消息提醒
                $m_data = array(
                    "user_id" => $data['user_id'],
                    "store_id" => $data['store_id'],
                    "message" => "您的店铺 " . $message . " 管理费已续费成功,店铺可正常使用，请点击查看",
                    "category" => 2,
                    "type" => 14,
                    "add_time" => time(),
                    "is_user_info" => 1
                );

                $m_m_bool = db::name('user_message')->insert($m_data);

                // 给予运营商奖励
//                $flag = 1;
//                if (!empty($user['operator_id'])) {
//                    $flag = getOperatorReward($user, 0, 1, 2, $type);
//                }

                if ($bool_managerfee && $bool_user && $bool_user_money && $bool_store && $m_m_bool && $flag) {
                    // 提交事务
                    Db::commit();
//                     $redis = Redis();
//                     $red_dev_code = $redis->get("dev_code".$data['user_id']);
//                     if($red_dev_code){
//                         jgSend2($red_dev_code,$m_data['message'],'',10016);
//                     }
                    return json_return('', 200, '支付成功！');
                } else {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '支付失败！');
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, $e->getMessage());
            }
        } elseif ($data['pay_id'] == '2') {//支付宝
            //创建缴费记录
            $manager = array(
                'order_sn' => d_create_order_num(),
                'user_id' => $data['user_id'],
                'store_id' => $data['store_id'],
                'type' => $type,
                'fee' => $fee,
                'createtime' => time(),
                'expiretime' => $manager_fee_time
            );
            $managerfee = db::name('managerfee')->insertGetId($manager);
            if ($managerfee) {
                vendor('Alipay.Alipay');
                $alipay = new \Alipay();
                $btype = "pay_manage_fee";
                $price = '' . $fee;
                $order_sn = $manager['order_sn'];
                $body = "您在凡商优店应用中用支付宝支付店铺管理费用，订单号为：${order_sn}，支付金额为：${price}元";
                $subject = "您在凡商优店应用中用支付宝支付店铺管理费用，订单号为：" . $order_sn;
                $orderInfo = $alipay->tradeAppPay($body, $subject, $order_sn, $price, $btype);
                exit('{"data":"' . $orderInfo . '","msg":"success","code":200}');
            } else {
                return json_return('', 400, '支付创建失败！');
            }
        } else {
            return json_return('', 400, '非法请求');
        }
    }

//返回用户的信息
    public function userInfo()
    {
        $rs = Db::name('user')->where('id', $this->user_id)->find();
        return json_return($rs);
    }


    /**
     *
     *批量操作商品
     * */
    public function operate_goods()
    {
        $data = array(
            'user_id'=>$this->user_id,
           // 'user_id' => input('user_id', 63),
            'goods_ids' => input('ids'),
            'type' => input('type'),
        );
        $res = array();
        if ($data['goods_ids'] == '') {
            return json_return('', 400, '请选择需要删除的商品！');
        }
        //查询用户的店铺信息
        $user = db::name("user")
            ->alias('a')
            ->join('shop_level o', 'a.level=o.up_level')
            ->join('goods g', 'g.user_id=a.id and g.is_bbm=' . Goods::WIN_LOCATION_CATE)
            ->where('a.id', $data['user_id'])
            ->where('a.store_status', 'in', array('onlin', 'offlin'))
            ->field('a.level,a.store_status,o.sale_gift_number,o.sale_goods_number,g.goods_number')
            ->find();

        switch ($data['type']) {
            case "delete"://批量删除商品
                $ids = trim($data['goods_ids'], ',');
                $goods_ids = explode(',', $ids);
                //开启事务
                Db::startTrans();
                try {
                    //删除商品
                    $bool_g = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_id', 'in', $goods_ids)
                        ->where('is_delete', 0)
                        ->update(array('is_delete' => 1));

                    if ($bool_g) {
                        // 提交事务
                        Db::commit();
                        return json_return('', 200, '删除成功！');
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return json_return('', 400, '删除失败！');
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, $e->getMessage());
                }
                break;
            case "obtained"://批量下架商品
                $ids = trim($data['goods_ids'], ',');
                $goods_ids = explode(',', $ids);
                //开启事务
                Db::startTrans();
                try {
                    //下架商品
                    $bool_g = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_id', 'in', $goods_ids)
                        ->where('is_delete', 0)
                        ->update(array('goods_status' => 'obtained'));


                    $winGoods = Db::name('goods')->where(['user_id' => $data['user_id'], 'is_bbm' => Goods::WIN_LOCATION_CATE])->find();
                    $all_goods = db::name('goods')->field('goods_id,store_id,user_id,is_recommend')->where('goods_id', 'in', $goods_ids)->select();
                    $insertData = [];
                    $insert = [];
                    $update = [];
                    $nums = count($goods_ids);
                    foreach ($all_goods as $k => $v) {
                        $insertData[$k] = [
                            'store_id' => $winGoods['store_id'],
                            'user_id' => $winGoods['user_id'],
                            'goods_id' => $v['goods_id'],
                            'money' => $winGoods['shop_price'],
                            'type' => 6,
                            'pay_type' => -1,
                            'numbers' => 1,
                            'remark' => '下架商品'
                        ];
                        if ($v['is_recommend'] == 1) {
                            $update[$k] = $v['goods_id'];
                            $insert[$k] = [
                                'store_id' => $winGoods['store_id'],
                                'user_id' => $winGoods['user_id'],
                                'goods_id' => $v['goods_id'],
                                'money' => $winGoods['shop_price'],
                                'type' => 2,
                                'pay_type' => -1,
                                'numbers' => 1,
                                'remark' => '取消展位'
                            ];
                            $nums++;
                        }
                    }
                    // 增加橱窗位
                    //Db::name('goods')->where(['user_id'=>$data['user_id'],'is_bbm'=>Goods::WIN_LOCATION_CATE])->setInc('goods_number',$nums);
                    if (!empty($update)) {
                        Db::name('goods')->where('goods_id', 'in', $update)->update(['is_recommend' => 0]);
                        Db::name('win_location_log')->insertAll($insert);
                    }

                    Db::name('win_location_log')->insertAll($insertData);

                    if ($bool_g) {
                        // 提交事务
                        Db::commit();
                        return json_return('', 200, '下架成功！');
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return json_return('', 400, '下架失败！');
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, $e->getMessage());
                }
                break;
            case "reject"://批量重新审核商品
                $ids = trim($data['goods_ids'], ',');
                $goods_ids = explode(',', $ids);
                //开启事务
                Db::startTrans();
                try {
                    //重新申请商品
                    $bool_g = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_id', 'in', $goods_ids)
                        ->where('is_delete', 0)
                        ->update(array('goods_status' => 'uncheck'));

                    $nums = count($goods_ids);
                    if ($nums > ($user['goods_number'] - getWinStock($data['user_id']))) {
                        return json_return('', 400, '你上架的普通商品已达到上限！');
                    }

                    // 减少橱窗位
                    //Db::name('goods')->where(['user_id'=>$data['user_id'],'is_bbm'=>Goods::WIN_LOCATION_CATE])->setDec('goods_number',$nums);
                    $winGoods = Db::name('goods')->where(['user_id' => $data['user_id'], 'is_bbm' => Goods::WIN_LOCATION_CATE])->find();
                    $insertData = [];
                    foreach ($goods_ids as $k => $v) {
                        $insertData[$k] = [
                            'store_id' => $winGoods['store_id'],
                            'user_id' => $winGoods['user_id'],
                            'goods_id' => $v,
                            'money' => $winGoods['shop_price'],
                            'type' => 5,
                            'numbers' => 1,
                            'remark' => '上架商品'
                        ];
                    }
                    Db::name('win_location_log')->insertAll($insertData);

                    if ($bool_g) {
                        $pc_data = array(
                            "title" => "商品审核",
                            "content" => "有新的商品申请上架审核,点击前往处理",
                            "type" => "5"
                        );
                        uptoPC($pc_data);

                        // 提交事务
                        Db::commit();
                        return json_return('', 200, '审核提交成功！');
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return json_return('', 400, '审核提交失败！');
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, $e->getMessage());
                }
                break;
            case "onlin"://批量上架商品
                $ids = trim($data['goods_ids'], ',');
                $goods_ids = explode(',', $ids);
                //开启事务
                Db::startTrans();
                try {
                    //判断已经上架的商品
                    $storeGoods = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_status', 'onlin')
                        ->where('is_delete', 0)
                        ->where('is_bbm', '<>', 2)
                        ->group('is_bbm')
                        ->field('is_bbm,count(goods_id) as num')
                        ->select();
                    //重新上架商品的类型
                    $ShelfGoods = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_id', 'in', $goods_ids)
                        ->where('goods_status', 'offlin')
                        ->where('is_delete', 0)
                        ->where('is_bbm', '<>', 2)
                        ->group('is_bbm')
                        ->field('is_bbm,count(goods_id) as num')
                        ->select();
                    //获取设置展位的商品
                    $rec = db::name('goods')->where(['user_id' => $data['user_id'], 'goods_status' => 'onlin', 'is_delete' => '0'])->count();

                    foreach ($storeGoods as $key => $value) {
                        foreach ($ShelfGoods as $k => $v) {
                            if ($value['is_bbm'] == '1') {
                                if ($v['is_bbm'] == '1') {
                                    if ($value['num'] + $v['num'] > $user['sale_gift_number']) {
                                        // 回滚事务
                                        Db::rollback();
                                        return json_return('', 400, '您上架的帮卖产品已经达到上限！');
                                    }
                                }
                            } else {
                                if ($v['is_bbm'] == '0') {
                                    if ($value['num'] + $v['num'] + $rec > $user['goods_number']) {
                                        // 回滚事务
                                        Db::rollback();
                                        return json_return('', 400, '你上架的普通商品已达到上限！');
                                    }
                                }
                            }
                        }
                    }

                    //$nums =count($goods_ids);
                    // 减少橱窗位
                    //Db::name('goods')->where(['user_id'=>$data['user_id'],'is_bbm'=>Goods::WIN_LOCATION_CATE])->setDec('goods_number',$nums);
                    $winGoods = Db::name('goods')->where(['user_id' => $data['user_id'], 'is_bbm' => Goods::WIN_LOCATION_CATE])->find();
                    $insertData = [];
                    foreach ($goods_ids as $k => $v) {
                        $insertData[$k] = [
                            'store_id' => $winGoods['store_id'],
                            'user_id' => $winGoods['user_id'],
                            'goods_id' => $v,
                            'money' => $winGoods['shop_price'],
                            'type' => 5,
                            'numbers' => 1,
                            'remark' => '上架商品'
                        ];
                    }
                    Db::name('win_location_log')->insertAll($insertData);

                    //上架商品
                    $bool_g = db::name('goods')
                        ->where('user_id', $data['user_id'])
                        ->where('goods_id', 'in', $goods_ids)
                        ->where('is_delete', 0)
                        ->update(array('goods_status' => 'onlin'));
                    if ($bool_g) {
                        // 提交事务
                        Db::commit();
                        return json_return('', 200, '上架成功！');
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return json_return('', 400, '上架失败！');
                    }
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, $e->getMessage());
                }
                break;
            default:
                return json_return('', 400, '操作有误！');
        }
    }

    /**
     *
     *店铺商品管理
     * */
    public function goods_manage()
    {
        $data = array(
           // 'user_id' => input('user_id', 63),
            'user_id' => input('user_id', $this->user_id),
            'status' => input('status') ?: 'uncheck',
            'key' => input('key') ?: '',
            'page' => input('page/d') ?: 1,
            'limit' => input('limit/d') ?: 10
        );
        $where['user_id'] = $data['user_id'];
//        $where['store_status'] = $data['status'];
        $where['goods_status']=$data['status'];
        $where['is_delete'] = 0;
        if ($data['key'] != '') {
            $where['goods_name'] = ['like', "%" . $data['key'] . "%"];
        }
        $res = db::name("goods")
            ->where($where)
            ->field('goods_id,is_bbm,goods_name,shop_price,goods_number,goods_status,beizhu,goods_thumb,cat_id,goods_desc,goods_brief,is_recommend')
            ->page($data['page'])
            ->limit($data['limit'])
            ->order('goods_id desc')
            ->select();
       // dump( db::name("goods")->getLastSql());die;
        $count = db::name("goods")->where($where)->count();
        $cate = Goods::WIN_LOCATION_CATE;
        $wl = db::name('goods')->where(['user_id' => $data['user_id'], 'is_bbm' => $cate])->find();
        if (!empty($res)) {
            $store = db::name('store')->where('user_id', $data['user_id'])->field('type,cat_id')->find();
            foreach ($res as $key => $value) {
                $res[$key]['goods_thumb'] = $value['goods_thumb'] ? add_image_pre($value['goods_thumb']) : $value['goods_thumb'];
                //查询商品规格
                $goods_attr = db::name('goods_attr')->where('goods_id', $value['goods_id'])->field('attr_value')->select();
                $sttr = '';
                foreach ($goods_attr as $k => $v) {
                    $sttr .= $v['attr_value'] . ';';
                }
                $res[$key]['goods_attr'] = mb_strlen($sttr, 'utf8') > 10 ? assoc_substr($sttr, 10) . '...' : $sttr;

                $res[$key]['goods_url'] = config('app_url') . '/goodsDetail.html?goods_id=' . $value['goods_id'];

                //店铺类型以及店铺分类
                $res[$key]['store_type'] = $store['type'];
                $res[$key]['cat_id'] = $value['cat_id'] ? $this->cate_parent($value['cat_id']) : $value['cat_id'];
            }
        }
        $data['location_num'] = $wl['goods_number'] - getWinStock($data['user_id']);
        $data['data'] = $res;
        return json_return_layui($data, $count);
    }

    /**
     *店铺类型
     * */
    private function cate_parent($cat_id)
    {
        $category = db::name('goods_category')->where('cat_id', $cat_id)->field('cat_id,parent_id')->find();
        if ($category['parent_id'] == 0) {
            return $cat_id;
        } else {
            return $this->cate_parent($category['parent_id']);
        }
    }

    /**
     *店铺地址
     * */
    public function store_address()
    {
        $uid = $this->user_id;
        //$uid = input('user_id', 63);
        $store = db::name('store')->where('user_id', $uid)->field('province,city,district,street,house_number,type')->find();
        if ($store) {
            if ($store['type'] == 'onlin') {
                $data = $store['province'] . ' ' . $store['city'] . ' ' . $store['district'] . ' ' . $store['street'];
            } else {
                $data = $store['province'] . ' ' . $store['city'] . ' ' . $store['district'] . ' ' . $store['street'] . ' ' . $store['house_number'];
            }
        } else {
            $data = null;
        }

        return json_return($data);
    }

    /**
     *商品发布
     * */
    public function publish_goods1()
    {
         $uid = $this->user_id;
       // $uid = input('user_id', 63);
        //商品名称
        $goods_name = input('post.goods_name');
        if (empty($goods_name)) {
            return json_return('', 400, '商品名称不能为空！');
        }
        $goods_weight = input('post.goods_weight');
        if (empty($goods_weight)) {
            return json_return('', 400, '商品重量不能为空！');
        }
        $goods_img = '';

        if (input('goods_img/a') != '') {
            $goods_imgs = input('goods_img/a');
        } else {
            return json_return('', 400, '图片为空！');
        }

//        if (!empty($_FILES)) {
//            $goods_img = oss_upload_file('goods_img', '', true);
//        } else {
//            return json_return('', 400, '图片为空！');
//        }
//        $attr_name = input('post.attr_name/a');
//        if (empty($attr_name)) {
//            return json_return('', 400, '请输入商品规格名称！');
//        }
//        $attr_arrs = input('post.attr_arr/a');
//        if (empty($attr_arrs)) {
//            return json_return('', 400, '请输入商品规格！');
//        }

        $goods_brief = input('post.goods_brief') ?: '';
//        if (!empty($_FILES)) {
//           $img_desc = oss_upload_file('img_desc', '', true);
//        } else {
//            $img_desc = '';
//        }
        if (input('goods_desc/a') != '') {
            $img_desc = input('goods_desc/a', '');
            $img_desc = implode(',', $img_desc);
        } else {
            $img_desc = '';
        }

        if (empty($goods_brief) && empty($img_desc)) {
            return json_return('', 400, '商品描述和商品详情图不能同时为空！');
        }

        $cat_id = input('post.cat_id');
        if (empty($cat_id)) {
            return json_return('', 400, '请选择分类！');
        }

        $goods_number = input('post.goods_number');
        if (empty($goods_number)) {
            return json_return('', 400, '商品库存不能为空！');
        }
        $shop_price = input('post.shop_price');
        if (empty($shop_price) || 100 * $shop_price <= 0) {
            return json_return('', 400, '请输入商品价格！');
        }
        $fare = input('post.fare');
        if (100 * $fare < 0) {
            return json_return('', 400, '请输入运费！');
        }
        $promotion_method = input('post.promotion_method');


        $promotion_designation_price = input('post.promotion_designation_price', 0);
        $promotion_universal_price = input('post.promotion_universal_price', 0);

        switch ($promotion_method) {
            case 1:
                if (empty($promotion_designation_price)) {
                    return json_return('', 400, '请输入指定优惠金额！');
                }
                break;
            case 2:
                if (empty($promotion_universal_price)) {
                    return json_return('', 400, '请输入全网通优惠金额！');
                }
                break;
            case 3:
                if (empty($promotion_designation_price) || empty($promotion_universal_price)) {
                    return json_return('', 400, '请输入优惠金额！');
                }
                break;
        }

//        $promotion_price = input('post.promotion_price');
//        if (!empty($promotion_method)) {
//            if (empty($promotion_price)) {
//                return json_return('', 400, '请输入优惠金额！');
//            }
//        }


        $store = db::name('store')->where(array('user_id' => $uid, 'state' => 'finish'))->field('id,store_name,type,house_number,longitude,latitude,cat_id')->find();
        $Random = new Random();
        //商品详情
        // $goods_imgs = explode(',', $goods_img);
        // $attr_arrs = explode('、', $attr_arr);
        //商品表
        $data = array(
            'store_id' => $store['id'],
            'store_status' => $store['type'],
            'user_id' => $uid,
            'cat_id' => $cat_id,
            'goods_sn' => $Random->build('nozero', 12),
            'goods_name' => $goods_name,
            'goods_weight' => $goods_weight,
            'goods_number' => $goods_number,
            'fare' => $fare,
            'goods_brief' => $goods_brief,
            'goods_desc' => $img_desc,
            'shop_price' => $shop_price,
            'goods_thumb' => $goods_imgs[0],
            'goods_img' => $goods_imgs[0],
            'original_img' => $goods_imgs[0],
            'add_time' => time(),
            'promotion_method' => $promotion_method,
            'promotion_designation_price' => $promotion_designation_price,
            'promotion_universal_price' => $promotion_universal_price,
            'location' => $store['house_number'],
            'longitude' => $store['longitude'],
            'latitude' => $store['latitude']
        );
        //开启事务
        Db::startTrans();
        try {

            //添加商品表数据
            $goods = db::name("goods")->insertGetId($data);

            $ret = locationEdit($goods, 5);
            if (!$ret['state']) {
                Db::rollback();
                return json_return('', 400, $ret['msg']);
            }

            //商品类型
            $goods_type = db::name('goods_type')->insertGetId(array(
                'user_id' => $uid,
                'cat_name' => $data['goods_sn'] . '_' . $data['goods_name']
            ));


            //todo 商品规格属性

            //属性分类
            $attr = input('post.attr/a');
            if (!empty($attr)) {
                $attr_cat = [];
                $attr_goods = [];
                foreach ($attr as $cat_k => $cat_v) {
                    $cat_attr_id = db::name('attribute')->insertGetId(['cat_id' => $goods_type, 'attr_name' => $cat_v['attr_name'], 'attr_values' => implode($cat_v['attr_value'], ',')]);
                    $attr_cat[$cat_k] = $cat_attr_id;
                    foreach ($cat_v['attr_value'] as $k => $v) {
                        $attr_data = array(
                            'goods_id' => $goods,
                            'attr_id' => $cat_attr_id,
                            'attr_value' => $v
                        );
                        $goods_attr_id = db::name('goods_attr')->insertGetId($attr_data);
                        $attr_goods[$cat_attr_id . '_' . $v] = $goods_attr_id;
                    }
                }

                $product = input('post.product/a');
                $productData = [];
                foreach ($product as $key => $value) {
                    $product_name = explode('|', $value['product_name']);

                    foreach ($product_name as $kk => $vv) {
                        $product_name[$kk] = $attr_goods[$attr_cat[$kk] . '_' . $vv];
                    }

                    $productData[$key]['goods_attr'] = implode($product_name, '|');
                    $productData[$key]['goods_id'] = $goods;
                    $productData[$key]['product_market_price'] = $value['product_price'];
                    $productData[$key]['product_price'] = $value['product_price'];
                    $productData[$key]['product_number'] = $value['product_number'];
                }

                $product_ret = db::name('products')->insertAll($productData);
                if (!$product_ret) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '插入商品规格失败！');
                }
            }


//
//            //属性名称
//            $attribute = db::name('attribute')->insertGetId(array(
//                'cat_id' => $goods_type,
//                'attr_name' => $attr_name,
//                'attr_values' => $attr_arrs[0]
//            ));
//
//            //商品属性
//            $list = array();
//            foreach ($attr_arrs as $v) {
//                $list[] = array(
//                    'goods_id' => $goods,
//                    'attr_id' => $attribute,
//                    'attr_value' => $v
//                );
//            }
//
//            $goods_attr = db::name('goods_attr')->insertAll($list);
            //商品图片
            $map = array();
            foreach ($goods_imgs as $value) {
                $map[] = array(
                    'goods_id' => $goods,
                    'img_url' => $value,
                    'thumb_url' => $value,
                    'img_original' => $value,
                );
            }
            $goods_gallery = db::name('goods_gallery')->insertAll($map);


            if ($goods && $goods_gallery) {
                $pc_data = array(
                    "title" => "商品审核",
                    "content" => "有新的商品申请上架审核,点击前往处理",
                    "type" => "5"
                );
                uptoPC($pc_data);

                // 提交事务
                Db::commit();
                return json_return('', 200, '提交成功！');
            } else {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, '提交失败！');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json_return('', 400, $e->getMessage());
        }
    }

    public function publish_goods()
    {
        $uid = $this->user_id;
        //商品名称
        $goods_name = input('post.goods_name');
        if (empty($goods_name)) {
            return json_return('', 400, '商品名称不能为空！');
        }
        $goods_weight = input('post.goods_weight');
        if (empty($goods_weight)) {
            return json_return('', 400, '商品重量不能为空！');
        }
        $goods_img = input('post.goods_img/a');

        if (empty($goods_img)) {
            return json_return('', 400, '图片不能为空！');
        }

//        if (!empty($_FILES)) {
//            $goods_img = oss_upload_file('goods_img', '', true);
//        } else {
//            return json_return('', 400, '图片为空！');
//        }
//        $attr_name = input('post.attr_name');
//        if (empty($attr_name)) {
//            return json_return('', 400, '请输入商品规格名称！');
//        }
//        $attr_arr = input('post.attr_arr');
//        if (empty($attr_arr)) {
//            return json_return('', 400, '请输入商品规格！');
//        }
        $goods_brief = input('post.goods_brief') ?: '';
        $img_desc = input('post.goods_desc/a') ?:[];
        $img_desc = implode(',',$img_desc);

        //dump($img_desc);die;

        if (empty($goods_brief) && empty($img_desc)) {
            return json_return('', 400, '商品描述和商品详情图不能同时为空！');
        }
        $cat_id = input('post.cat_id');
        if (empty($cat_id)) {
            return json_return('', 400, '请选择分类！');
        }

        $goods_number = input('post.goods_number');
        if (empty($goods_number)) {
            return json_return('', 400, '商品库存不能为空！');
        }
        $shop_price = input('post.shop_price');
        if (empty($shop_price) || 100 * $shop_price <= 0) {
            return json_return('', 400, '请输入商品价格！');
        }
        $fare = input('post.fare');
        if (100 * $fare < 0) {
            return json_return('', 400, '请输入运费！');
        }
        $promotion_method = input('post.promotion_method');

        $promotion_designation_price = input('post.promotion_designation_price', 0);
        $promotion_universal_price = input('post.promotion_universal_price', 0);

        switch ($promotion_method) {
            case 1:
                if (empty($promotion_designation_price)) {
                    return json_return('', 400, '请输入指定优惠金额！');
                }
                break;
            case 2:
                if (empty($promotion_universal_price)) {
                    return json_return('', 400, '请输入全网通优惠金额！');
                }
                break;
            case 3:
                if (empty($promotion_designation_price) || empty($promotion_universal_price)) {
                    return json_return('', 400, '请输入优惠金额！');
                }
                break;
        }

        $store = db::name('store')->where(array('user_id' => $uid, 'state' => 'finish'))->field('id,store_name,type,house_number,longitude,latitude,cat_id')->find();
        $Random = new Random();


        //$goods_imgs = explode(',', $goods_img);

        //商品表
        $data = array(
            'store_id' => $store['id'],
            'store_status' => $store['type'],
            'user_id' => $uid,
            'cat_id' => $cat_id,
            'goods_sn' => $Random->build('nozero', 12),
            'goods_name' => $goods_name,
            'goods_weight' => $goods_weight,
            'goods_number' => $goods_number,
            'fare' => $fare,
            'goods_brief' => $goods_brief,
            'goods_desc' => $img_desc,
            'shop_price' => $shop_price,
            'goods_thumb' => $goods_img[0],
            'goods_img' => $goods_img[0],
            'original_img' => $goods_img[0],
            'add_time' => time(),
            'promotion_method' => $promotion_method,
            'promotion_designation_price' => $promotion_designation_price,
            'promotion_universal_price' => $promotion_universal_price,
            'location' => $store['house_number'],
            'longitude' => $store['longitude'],
            'latitude' => $store['latitude']
        );


        //开启事务
        Db::startTrans();
        try {

            //添加商品表数据
            $goods = db::name("goods")->insertGetId($data);
            $ret = locationEdit($goods, 5);
            if (!$ret['state']) {
                Db::rollback();
                return json_return('', 400, $ret['msg']);
            }


            //商品类型
            $goods_type = db::name('goods_type')->insertGetId(array(
                'user_id' => $uid,
                'cat_name' => $data['goods_sn'] . '_' . $data['goods_name']
            ));



            //todo 商品规格属性

            //属性分类
            $attr = input('post.attr/a');
            if (!empty($attr)) {
                $attr_cat = [];
                $attr_goods = [];
                foreach ($attr as $cat_k => $cat_v) {
                    $cat_attr_id = db::name('attribute')->insertGetId(['cat_id' => $goods_type, 'attr_name' => $cat_v['attr_name'], 'attr_values' => implode($cat_v['attr_value'], ',')]);
                    $attr_cat[$cat_k] = $cat_attr_id;
                    foreach ($cat_v['attr_value'] as $k => $v) {
                        $attr_data = array(
                            'goods_id' => $goods,
                            'attr_id' => $cat_attr_id,
                            'attr_value' => $v
                        );
                        $goods_attr_id = db::name('goods_attr')->insertGetId($attr_data);
                        $attr_goods[$cat_attr_id . '_' . $v] = $goods_attr_id;
                    }
                }

                $product = input('post.product/a');
                $productData = [];
                foreach ($product as $key => $value) {
                    $product_name = explode('|', $value['product_name']);

                    foreach ($product_name as $kk => $vv) {
                        $product_name[$kk] = $attr_goods[$attr_cat[$kk] . '_' . $vv];
                    }

                    $productData[$key]['goods_attr'] = implode($product_name, '|');
                    $productData[$key]['goods_id'] = $goods;
                    $productData[$key]['product_market_price'] = $value['product_price'];
                    $productData[$key]['product_price'] = $value['product_price'];
                    $productData[$key]['product_number'] = $value['product_number'];
                }

                $product_ret = db::name('products')->insertAll($productData);
                if (!$product_ret) {
                    // 回滚事务
                    Db::rollback();
                    return json_return('', 400, '插入商品规格失败！');
                }
            }




//            //属性名称
//            $attribute = db::name('attribute')->insertGetId(array(
//                'cat_id' => $goods_type,
//                'attr_name' => $attr_name,
//                'attr_values' => $attr_arr[0]
//            ));
//            //商品属性
//            $list = array();
//            foreach ($attr_arrs as $v) {
//                $list[] = array(
//                    'goods_id' => $goods,
//                    'attr_id' => $attribute,
//                    'attr_value' => $v
//                );
//            }

            //$goods_attr = db::name('goods_attr')->insertAll($list);
            //商品图片
            $map = array();
            foreach ($goods_img as $value) {
                $map[] = array(
                    'goods_id' => $goods,
                    'img_url' => $value,
                    'thumb_url' => $value,
                    'img_original' => $value,
                );
            }
            $goods_gallery = db::name('goods_gallery')->insertAll($map);


            if ($goods && $goods_gallery) {
                $pc_data = array(
                    "title" => "商品审核",
                    "content" => "有新的商品申请上架审核,点击前往处理",
                    "type" => "5"
                );
                uptoPC($pc_data);

                // 提交事务
                Db::commit();
                return json_return('', 200, '提交成功！');
            } else {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, '提交失败！');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json_return('', 400, $e->getMessage());
        }
    }
    /**
     * 是否推荐(在星级权益下显示)
     * @return bool
     */
    public function goods_recommend()
    {
       // $uid = input('user_id', 63);
        $uid = $this->user_id;
        $goods_id = input('post.ids');
        $is_recommend = input('post.is_recommend');
        $cate = Goods::WIN_LOCATION_CATE;
        //开启事务
        Db::startTrans();
        try {
            $goodsList = db::name('goods')->where(['user_id' => $uid, 'is_bbm' => $cate])->find();
            if ($is_recommend == 1) {

                if (($goodsList['goods_number'] - getWinStock($uid)) < 1) {
                    Db::rollback();
                    return json_return('', 400, '库存不足');
                }
                $insertData = [
                    'store_id' => $goodsList['store_id'],
                    'user_id' => $uid,
                    'goods_id' => $goods_id,
                    'money' => $goodsList['shop_price'],
                    'type' => '1',
                    'pay_type' => '-1',
                    'remark' => '设置展位'
                ];
                //$winRet = Db::name('goods')->where(['user_id'=>$uid,'is_bbm'=>$cate])->setDec('goods_number');
                $winRec = Db::name('win_location_log')->insert($insertData);
            } else {
                $insertData = [
                    'store_id' => $goodsList['store_id'],
                    'user_id' => $uid,
                    'goods_id' => $goods_id,
                    'money' => $goodsList['shop_price'],
                    'type' => '2',
                    'pay_type' => '-1',
                    'remark' => '取消展位'
                ];
                //$winRet = Db::name('goods')->where(['user_id'=>$uid,'is_bbm'=>$cate])->setInc('goods_number');
                $winRec = Db::name('win_location_log')->insert($insertData);
            }
            $goodsRet = db::name('goods')->where('goods_id', $goods_id)->setField('is_recommend', $is_recommend);
            //if($winRet && $goodsRet && $winRec){
            if ($goodsRet && $winRec) {
                Db::commit();
                return json_return('', 200, '编辑成功！');
            }
            Db::rollback();
            return json_return('', 400, '编辑失败！');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json_return('', 400, $e->getMessage());
        }
    }

    /**
     *
     *线下店铺最高级类别
     * */
    public function offlin_category()
    {
        $key = input('post.key');
        $select_cat = input('post.select_cat');
        if (empty($select_cat)) {//类别信息
            if (!empty($key)) {
                $category = Db::name('goods_category')
                    ->where('cat_name', 'like', "%" . $key . "%")
                    ->where('is_offline', 0)
                    ->where('is_show', 1)
                    ->field('cat_id,cat_name')
                    ->select();
            } else {
                $category = Db::name('goods_category')
                    ->where('is_offline', 0)
                    ->where('parent_id', 0)
                    ->where('is_show', 1)
                    ->field('cat_id,cat_name')
                    ->select();
            }
            if ($category) {
                foreach ($category as $key => $value) {
                    //判断是否存在下级分类
                    $last = Db::name('goods_category')->where('parent_id', $value['cat_id'])->where('is_show', 1)->field('cat_id,cat_name')->select();
                    if (!empty($last)) {
                        //还有下级分类
                        $category[$key]['optional'] = '1';
                    } else {
                        $category[$key]['optional'] = '0';
                    }
                }
            }

            return json_return($category);
        } else {//存储临时选定的分类
            $list = array();
            $map = array();
            $category = array();
            $cate = Db::name('goods_category')->where('cat_id', $select_cat)->where('is_show', 1)->field('cat_id,cat_name,parent_id')->find();
            //判断是否有上级
            if ($cate['parent_id'] == 0) {
                $list[] = array(
                    'cat_id' => $select_cat,
                    'parent_id' => $cate['parent_id']
                );
            } else {
                $map[] = array(
                    'cat_id' => $select_cat,
                    'parent_id' => $cate['parent_id']
                );
                $list = $this->get_child($cate['parent_id'], $map);
            }
            krsort($list);

            if ($list) {
                $category = Db::name('goods_category')
                    ->where('is_offline', 0)
                    ->where('parent_id', 0)
                    ->where('is_show', 1)
                    ->field('cat_id,cat_name')
                    ->select();
                foreach ($category as $k => $v) {
                    if ($v['cat_id'] == $list[count($list) - 1]['cat_id']) {
                        $category[$k]['check'] = '1';
                    } else {
                        $category[$k]['check'] = '0';
                    }
                    //判断是否存在下级分类
                    $last = Db::name('goods_category')->where('parent_id', $v['cat_id'])->where('is_show', 1)->field('cat_id,cat_name')->select();
                    if (!empty($last)) {
                        //还有下级分类
                        $category[$k]['optional'] = '1';
                    } else {
                        $category[$k]['optional'] = '0';
                    }
                }
            }
            return json_return($category);
        }
    }

    /**
     *
     *获取上级等级信息
     * */
    private function get_child($parent_id, $list)
    {
        $cate = Db::name('goods_category')->where('cat_id', $parent_id)->where('is_show', 1)->field('cat_id,cat_name,parent_id')->find();
        //判断是否有上级
        if ($cate['parent_id'] == 0) {
            $list[] = array(
                'cat_id' => $parent_id,
                'parent_id' => $cate['parent_id']
            );
            return $list;
        } else {
            $list[] = array(
                'cat_id' => $parent_id,
                'parent_id' => $cate['parent_id']
            );
            return $this->get_child($cate['parent_id'], $list);
        }
    }

    /*
    * 提醒用户付款
     * @user wj
    * */
    public function remind_payment()
    {
        $id = $this->user_id;
        //$id = input('user_id', 62);
        $order_sn = input('order_sn');
        if (empty($order_sn))
            return json_return('', 400, '订单号不能为空');
        $orderData = Db::name('order')
            ->field('order_id,order_status,order_sn,pay_status,user_id,store_name')
            ->where(['order_sn' => $order_sn, 'b_uids' => $id])
            ->find();
        if (empty($orderData))
            return json_return('', 400, '查无此订单');
        if ($orderData['pay_status'] == 1)
            return json_return('', 400, '此订单已付款');
        $message = "您在" . $orderData['store_name'] . "的订单" . $orderData['order_sn'] . "请尽快付款";
        $count = Db::name('user_message')->where(['order_sn' => $orderData['order_sn'], 'category' => 1])->count();
        if ($count > 2)
            return json_return('', 400, '已提醒买家，请耐心等待!');
        $messageData = [
            'user_id' => $orderData['user_id'],
            'order_id' => $orderData['order_id'],
            'order_sn' => $orderData['order_sn'],
            'message' => $message,
            'type' => 4,
            'category' => 1,
            'add_time' => time(),
            'is_user_info' => 1
        ];
        $result = Db::name('user_message')->insert($messageData);

        $redis = Redis();
        $red_dev_code = $redis->get("dev_code" . $orderData['user_id']);
        if ($red_dev_code) {
            jgSend2($red_dev_code, $message, ["order_id" => $orderData['order_id']], 10020);
        }

        if ($result !== false) {
            return json_return('', 200, '已提醒该用户');
        } else {
            return json_return('', 400, '处理失败');
        }
    }

    public function goods_import()
    {
        //数据库连接配置字符串
        $dbConfig = 'mysql://root:zxm111111@47.52.200.197:3306/goudiw#utf8';

        //将配置字符串做为connect()的参数传入
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
        $where['add_time'] = array('between', array($beginToday, $endToday));
        $result = Db::connect($dbConfig)->table('dsc_goods')
            ->field('goods_id,cat_id,user_id,goods_sn,goods_name,goods_name_style,click_count,goods_number,goods_weight,market_price,cost_price,shop_price,keywords,goods_brief,goods_desc,goods_thumb,goods_img,original_img,is_real,add_time,sort_order,is_delete,is_new,is_hot,is_promote,is_volume,last_update,goods_type,group_number,sales_volume,comment_num,pinyin_keyword,goods_tag,stages_rate,freight,shipping_fee,tid,goods_unit,goods_cause,commission_rate,from_seller,detailpara,is_tixian,is_xiangounum,originalurl,beizhu')
            //->where($where)
            ->limit(2)
            ->select();
        foreach ($result as $key => $value) {
            $arr = Db::name('goods_attr')->field('goods_id,admin_id,goods_attr_id', true)->where('goods_id', $value['goods_id'])->select();
            $result[$key]['attr_son'] = $arr;
            unset($result[$key]['goods_id']);
        }
        $data = [];
        $data['token'] = md5('fsyd399.com');
        $data['data'] = json_encode($result);
        //dump(json_encode($data));die;
        $url = config('fsurl.goods_import_url');
        $a = _request($url, false, 'post', $data);
        dump(json_decode($a, true));

        //Debug::dump($result);die;
        //$rs = Db::name('goods')->insertAll($result);
        //var_dump($rs);die;
    }

    public function sell_info()
    {
        //数据库连接配置字符串

        $dbConfig = 'mysql://root:zxm111111@47.52.200.197:3306/goudiw#utf8';
        //将配置字符串做为connect()的参数传入
        //$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        // $where['add_time'] = array('between', array($beginToday,$endToday));

        $result = Db::connect($dbConfig)->table('dsc_seller_shopinfo')
            //  ->where($where)
            ->limit(2)
            ->select();


    }

    public function upload()
    {

        return $this->fetch();
    }

    /**
     *
     * 阿里云上传方法
     */
    function oss_upload_file_logoimage()
    {
        $water = false;
        //     $is_array = input('is_array', false);
        $config = config('oss_params');
        $ossClient = new \OSS\OssClient($config['oss_key_id'], $config['key_secret'], $config['endpoint']);
        //$imageName = !$imageName ? 'imageName' : $imageName;
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        if ($file == '') {
            return '';
        }
        //   if (!$is_array) {
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf', 'apk']))
            return false;
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                $imageUrl = str_replace('\\', '/', 'public/uploads/' . $info->getSaveName());
                $image_name = $info->getFilename();
                if ($water) {
                    $image = \think\Image::open($imageUrl);
                    // 给原图左上角添加水印并保存water_image.png
                    $image->water('./water.png', \think\Image::WATER_SOUTHEAST)->save($imageUrl);
                }
                $info = $ossClient->uploadFile($config['bucket'], $imageUrl, $imageUrl);
                @unlink($imageUrl);
//                    return imagesParse($info['oss-request-url']);
                $img_url = imagesParse($info['oss-request-url']);

                json_return($img_url);
                //return $imageUrl;
            } else {
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
//        } elseif ($is_array) {
//            $images = [];
//            $info = [];
//            foreach ($file as $k => $file) {
//                $fileInfo = $file->getInfo();
//                $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
//                if (!in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']))
//                    return FALSE;
//                // 移动到框架应用根目录/public/uploads/ 目录下
//                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
//                if ($info) {
//                    $images[$k] = str_replace('\\', '/', 'uploads/' . $info->getSaveName());
//                    if ($water) {
//                        $image = \think\Image::open($images[$k]);
//                        // 给原图左上角添加水印并保存water_image.png
//                        $image->water('./water.png', \think\Image::WATER_SOUTHEAST)->save($images[$k]);
//                    }
//                    $imageUrl[] = $ossClient->uploadFile($config['bucket'], $images[$k], $images[$k]);
//                    //unlink($images[$k]);
//                } else {
//                    // 上传失败获取错误信息
//                    return $file->getError();
//                }
//            }
//            //处理阿里返回来的路径
//            delLocalImage($images);
//            $images = imagesUrlReturn($imageUrl);
//            return imagesParse($images);
//        } else {
//            return FALSE;
//        }
    }

    //多图上传
    function oss_upload_file_logoimages()
    {
        $water = false;
        $config = config('oss_params');
        $ossClient = new \OSS\OssClient($config['oss_key_id'], $config['key_secret'], $config['endpoint']);

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        $images = [];
        $info = [];
        foreach ($file as $k => $file) {
            $fileInfo = $file->getInfo();
            $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
            if (!in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']))
                return FALSE;
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $files->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $images[$k] = str_replace('\\', '/', 'uploads/' . $info->getSaveName());
                if ($water) {
                    $image = \think\Image::open($images[$k]);
                    // 给原图左上角添加水印并保存water_image.png
                    $image->water('./water.png', \think\Image::WATER_SOUTHEAST)->save($images[$k]);
                }
                $imageUrl[] = $ossClient->uploadFile($config['bucket'], $images[$k], $images[$k]);
                //unlink($images[$k]);
            } else {
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
        //处理阿里返回来的路径
        delLocalImage($images);
        $images = imagesUrlReturn($imageUrl);
        return imagesParse($images);
    }

//    function oss_upload_file_logoimages(){
//        //接收上传的文件
//        $file = $this->request->file('file');
//
//        if(!empty($file)){
//            //图片存的路径
//            $imgUrl= ROOT_PATH . 'public' . DS . 'uploads'. '/' .  date("Y/m/d");
//
//            // 移动到框架应用根目录/public/uploads/ 目录下
//
//            $info = $file->validate(['size'=>1048576,'ext'=>'jpg,png,gif'])->rule('uniqid')->move($imgUrl);
//            $error = $file->getError();
//            //验证文件后缀后大小
//            if(!empty($error)){
//                dump($error);exit;
//            }
//            if($info){
//                // 成功上传后 获取上传信息
//                //获取图片的名字
//                $imgName = $info->getFilename();
//                //获取图片的路径
//                $photo=$imgUrl . "/" . $imgName;
//
//            }else{
//                // 上传失败获取错误信息
//                $file->getError();
//            }
//        }else{
//            $photo = '';
//        }
//        if($photo !== ''){
//           // return ['code'=>1,'msg'=>'成功','photo'=>$photo];
//            return json_return($photo);
//        }else{
//            return json_return($photo,400);
//        }
//    }
    /**
     * 店铺历史审核记录
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="page", type="integer", required=false, description="页码")
     * @ApiParams   (name="limit", type="integer", required=false, description="页数")
     * */
    public function store_check_info()
    {
        $data = array(
            'user_id' => $this->user_id,
      //      'user_id' => input('user_id', 53),
            'page' => input('page') ?: 1,
            'limit' => input('limit/d') ?: 10
        );
        $res = array();
        $res = db::name('updetail')
            ->alias('a')
            ->join('shop_user o', 'a.user_id=o.id')
            ->where(array('a.store_uid' => $data['user_id']))
            ->where('a.status', 'in', array('finish', 'reject'))
            ->field('a.id,a.user_up_level,a.order_image,a.order_image_two,a.status,a.note,o.username,a.checktime,o.real_name,o.avatar,o.is_activation,o.mobile')
            ->limit($data['limit'])
            ->page($data['page'])
            ->order('id desc')
            ->select();

        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $res[$key]['order_image'] = add_image_pre($value['order_image']);
                $res[$key]['order_image_two'] = add_image_pre($value['order_image_two']);
                $res[$key]['avatar'] = add_image_pre($value['avatar']);
                $res[$key]['real_name'] = $value['username'] ? substr_cut($value['username']) : $value['username'];
            }
        }
        $count = db::name('updetail')
            ->alias('a')
            ->join('shop_user o', 'a.user_id=o.id')
            ->where(array('a.store_uid' => $data['user_id']))
            ->where('a.status', 'in', array('finish', 'reject'))
            ->count();
        return json_return_layui($res, $count);
    }

    /**
     * 店铺类型
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * */
    public function store_type()
    {
         $uid = $this->user_id;
        //$uid = input('user_id', 63);
        $store = array();
        $store = db::name('store')->where('user_id', $uid)->field('state,cat_id,type,is_all')->find();
        if ($store) {
            if ($store['state'] == 'finish') {
                $store['type'] = $store['type'];
            } else {
                $store['type'] = 'personal';
            }
            $store['cat_id'] = $this->cate_parent($store['cat_id']);
            unset($store['state']);
        } else {
            $store['type'] = 'personal';
            $store['cat_id'] = 0;
        }
        //各等级上架商品限制数
        $data = db::name('user')
            ->alias('a')
            ->join('shop_level o', 'a.level=o.up_level')
            ->join('goods g', 'g.user_id=a.id and g.is_bbm=' . Goods::WIN_LOCATION_CATE)
            ->where('a.id', $uid)
            ->where('a.store_status', '<>', 'personal')
            ->field('a.id,a.level,o.sale_goods_number,g.goods_number')
            ->find();
        //计算该用户上架商品数量以及待审核数量之和
        $goods_num = db::name('goods')->where(array('user_id' => $uid, 'is_delete' => 0, 'is_bbm' => 0, 'goods_status' => array('in', array('uncheck', 'onlin'))))->count() ?: 0;
        if (!empty($goods_num)) {
            //if ($data['sale_goods_number'] > $goods_num) {
            if ($data['goods_number'] - getWinStock($uid) > 0) {
                $store['up_goods'] = 1;
            } else {
                $store['up_goods'] = 0;
            }
        } else {
            $store['up_goods'] = 1;
        }
        return json_return($store);
    }

    /**
     * 线下店铺获取下级类别
     * @ApiMethod   (POST)
     * @param integer $cat_id 上级ID
     * @param integer $select_id 分类ID
     * */
    public function get_under_category()
    {
        $cat_id = input('cat_id');
        $select_id = input('select_id');
        if (empty($cat_id)) {
            return json_return('', 400, '参数有误！');
        }
        if (!empty($select_id)) {
            $category = db::name('goods_category')->where('parent_id', $cat_id)->where('is_show', 1)->field('cat_id,cat_name')->select();

            $cate = Db::name('goods_category')->where('cat_id', $select_id)->where('is_show', 1)->field('cat_id,cat_name,parent_id')->find();
            //获取所有上级信息
            $map[] = array(
                'cat_id' => $select_id,
                'parent_id' => $cate['parent_id']
            );
            $list = $this->get_child($cate['parent_id'], $map);

            if (!empty($category)) {
                foreach ($category as $key => $value) {
                    //判断是否存在下级分类
                    $last = db::name('goods_category')->where('parent_id', $value['cat_id'])->where('is_show', 1)->field('cat_id,cat_name')->select();
                    if (!empty($last)) {
                        //还有下级分类
                        $category[$key]['optional'] = '1';
                    } else {
                        $category[$key]['optional'] = '0';
                    }
                    $category[$key]['check'] = '0';
                    foreach ($list as $k => $v) {
                        if ($v['cat_id'] == $value['cat_id']) {
                            $category[$key]['check'] = '1';
                        }
                    }
                }
            }
        } else {
            //类别信息
            $category = array();
            $category = db::name('goods_category')->where('parent_id', $cat_id)->where('is_show', 1)->field('cat_id,cat_name')->select();
            if (!empty($category)) {
                foreach ($category as $key => $value) {
                    //判断是否存在下级分类
                    $last = db::name('goods_category')->where('parent_id', $value['cat_id'])->where('is_show', 1)->field('cat_id,cat_name')->select();
                    if (!empty($last)) {
                        //还有下级分类
                        $category[$key]['optional'] = '1';
                    } else {
                        $category[$key]['optional'] = '0';
                    }
                }
            }
        }


        return json_return($category);
    }

    /**
     * 店铺信息
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * */
    public function store_info()
    {
        $uid = $this->user_id;
        //$uid = input('user_id', 63);
        //店铺信息
        $store = db::name('store')->alias('s')
            ->join('shop_user u', 'u.id=s.user_id')
            ->where(array('s.user_id' => $uid))
            //->where(array('user_id' => $uid, 'state' => 'finish'))
            ->field('s.id,s.store_name,s.logo_image,s.province,s.city,s.district,s.street,s.province_id,s.city_id,s.district_id,s.street_id,s.house_number,s.is_edit,s.wx_number,s.mobile,s.realname,s.cover_status,s.self_cover_image,s.type,s.longitude,s.latitude,s.qq,s.cat_id,s.is_openlocation,s.store_desc,s.wechat_code_img,s.check_status,u.level,s.card_img_front,s.card_img_back')
            ->find();
        if ($store) {
            $store['img_url'] = config('fzk_img_url');
            if ($store['type'] == 'offlin') {
                $store['store_img'] = db::name('storeimg')->where('store_id', $store['id'])->field('id,image')->select();
                $store['store_advantage'] = db::name('store_tag')->where('store_id', $store['id'])->field('id,tag')->select();
                $store['category'] = db::name('goods_category')->where('cat_id', $store['cat_id'])->value('cat_name');
            }
            $store['wx_number'] = $store['wx_number'] ?: db::name('user')->where('id', $uid)->value('wx_id');
        }
        //var_dump($store);die;
        return json_return($store);
    }


    /**
     * 编辑店铺信息
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @param file $logo_image 头像LOGO
     * @param string $store_name 商铺名称
     * @param string $house_number 具体地址
     * @param string $longitude 定位
     * @param string $latitude 定位
     * @param integer $province_id 省份
     * @param integer $city_id 市
     * @param integer $district_id 区
     * @param integer $street_id 街道
     * @param string $wx_number 店家微信
     * @param integer $qq qq
     * @param file $self_cover_image 商铺背景图
     * @param file $cover_status 封面
     * @ApiParams   (name="images", type="string", required=false, description="商品图")
     * @ApiParams   (name="tag", type="string", required=false, description="店铺标签")
     * */
    public function edit_store_info()
    {

        $uid = $this->user_id;
        //$uid = input('user_id', 63);
        $store = db::name('store')->where(array('user_id' => $uid, 'state' => 'finish'))->field('id,province_id,city_id,district_id,street_id,house_number,is_edit,type,is_all')->find();

        $bool = true;

        if ($store['type'] == 'offlin') {
            $logo_image = input('post.logo_image');
            if (empty($logo_image)) {
                return json_return('', 400, '店铺logo不能为空！');
            }

            $store_name = input('post.store_name');
            if (empty($store_name)) {
                return json_return('', 400, '店铺名称不能为空！');
            }

//             $cat_id = input('post.cat_id');
//             if(empty($cat_id)){
//                 return json_return('', 400, '店铺类别不能为空！');
//             }
            $house_number = input('post.house_number');
            if (empty($house_number)) {
                return json_return('', 400, '具体地址不能为空！');
            }

            $wx_number = input('post.wx_number');
            if (empty($wx_number)) {
                return json_return('', 400, '店家微信不能为空！');
            }
            $qq = input('post.qq');
            if (empty($qq)) {
                return json_return('', 400, '店家qq不能为空！');
            }
            $longitude = input('post.longitude');
            if (empty($longitude)) {
                return json_return('', 400, '请选择定位！');
            }

            $latitude = input('post.latitude');
            if (empty($latitude)) {
                return json_return('', 400, '请选择定位！');
            }

            $store_desc = input('post.store_desc');
            if (empty($store_desc)) {
                return json_return('', 400, '商家信息不能为空！');
            }

            $tag = input('post.tag/a', '');
//             if(empty($tag)){
//                 return json_return('', 400, '请填写店铺标签！');
//             }

            $images = input('post.images/a');
            //  var_dump($images);die;
            if (empty($images)) {
                return json_return('', 400, '请上传商品图！');
            }

            // $self_img = explode(',', $images);

            $data = array(
                'user_id' => $uid,
                'cover_status' => 'self',
                'logo_image' => $logo_image,
                'self_cover_image' => $images[0],
                'store_desc' => $store_desc,
                'house_number' => $house_number,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'store_name' => $store_name,
                'wx_number' => $wx_number,
                'qq' => $qq,
                'updatetime' => time()
            );
            if ($store['is_all'] == 0) {
                $data['is_all'] = 1;
            }

            if ($store['is_edit'] == 1) {
                $province_id = input('post.province');
                if (empty($province_id)) {
                    return json_return('', 400, '请选择省份！');
                }
                $city_id = input('post.city');
                if (empty($city_id)) {
                    return json_return('', 400, '请选择市！');
                }
                $district_id = input('post.district');
                if (empty($district_id)) {
                    return json_return('', 400, '请选择区！');
                }
                $street_id = input('post.street');
                if (empty($street_id)) {
                    return json_return('', 400, '请选择街道！');
                }
                if ($province_id == $store['province_id'] && $city_id == $store['city_id'] && $district_id == $store['district_id'] && $street_id == $store['street_id']) {
                    $data['is_edit'] = $store['is_edit'];
                } else {
                    $data['province'] = get_area($province_id);
                    $data['city'] = get_area($city_id);
                    $data['district'] = get_area($district_id);
                    $data['street'] = get_area($street_id);
                    $data['province_id'] = $province_id;
                    $data['city_id'] = $city_id;
                    $data['district_id'] = $district_id;
                    $data['street_id'] = $street_id;
                    $data['is_edit'] = 0;
                }
            } else {
                $data['is_edit'] = $store['is_edit'];
            }
        } else {
            $logo_image = input('post.logo_image');
            if (empty($logo_image)) {
                return json_return('', 400, '头像LOGO不能为空！');
            }
            $store_name = input('post.store_name');
            if (empty($store_name)) {
                return json_return('', 400, '店铺名称不能为空！');
            }

            $wx_number = input('post.wx_number');
            if (empty($wx_number)) {
                return json_return('', 400, '店家微信不能为空！');
            }
            $qq = input('post.qq');
            if (empty($qq)) {
                return json_return('', 400, '店家qq不能为空！');
            }
//            $cover_statuse = input('post.cover_status');
//            if (empty($cover_statuse)) {
//                return json_return('', 400, '请选择封面！');
//            }
            $data = array(
                'user_id' => $uid,
                'logo_image' => $logo_image,
                'cover_status' => $cover_statuse,
                'store_name' => $store_name,
                'wx_number' => $wx_number,
                'qq' => $qq,
                'updatetime' => time()
            );
            if ($cover_statuse == 'self') {
                $self_cover_image = input('post.self_cover_image');
                if (empty($self_cover_image)) {
                    return json_return('', 400, '商铺背景图不能为空！');
                }
                $data['self_cover_image'] = $self_cover_image;
            }

            if ($store['is_edit'] == 1) {
                $province_id = input('post.province');
                if (empty($province_id)) {
                    return json_return('', 400, '请选择省份！');
                }
                $city_id = input('post.city');
                if (empty($city_id)) {
                    return json_return('', 400, '请选择市！');
                }
                $district_id = input('post.district');
                if (empty($district_id)) {
                    return json_return('', 400, '请选择区！');
                }
                $street_id = input('post.street');
                if (empty($street_id)) {
                    return json_return('', 400, '请选择街道！');
                }
                if ($province_id == $store['province_id'] && $city_id == $store['city_id'] && $district_id == $store['district_id'] && $street_id == $store['street_id']) {
                    $data['is_edit'] = $store['is_edit'];
                } else {
                    $data['province'] = get_area($province_id);
                    $data['city'] = get_area($city_id);
                    $data['district'] = get_area($district_id);
                    $data['street'] = get_area($street_id);
                    $data['province_id'] = $province_id;
                    $data['city_id'] = $city_id;
                    $data['district_id'] = $district_id;
                    $data['province_id_id'] = $street_id;
                    $data['is_edit'] = 0;
                }
            } else {
                $data['is_edit'] = $store['is_edit'];
            }
        }
        Db::startTrans();
        try {
            $bool_add_tag = true;
            $bool_add_image = true;
            $bool_del_tag = true;
            $bool_del_image = true;
            //更改店铺信息
            $res = db::name("store")->where('id', $store['id'])->update($data);
            if ($store['type'] == 'offlin') {
                //删除图片
                $is_image = db::name('storeimg')->where('store_id', $store['id'])->select();
                if ($is_image) {
                    $bool_del_image = db::name('storeimg')->where('store_id', $store['id'])->delete();
                }

                //删除标签
                $is_tag = db::name('store_tag')->where('store_id', $store['id'])->select();
                if ($is_tag) {
                    $bool_del_tag = db::name('store_tag')->where('store_id', $store['id'])->delete();
                }
                //添加图片
                $list = array();
                if (!empty($images)) {
                    // $store_imgs = explode(',', $images);
                    foreach ($images as $key => $value) {
                        $list[] = array(
                            'store_id' => $store['id'],
                            'user_id' => $uid,
                            'image' => $value,
                            'createtime' => time()
                        );
                    }
                    $bool_add_image = db::name('storeimg')->insertAll($list);
                }

                //添加标签
                $map = array();
                if (!empty($tag)) {
                    //$tags = explode(',', $tag);
                    foreach ($tag as $k => $v) {
                        $map[] = array(
                            'store_id' => $store['id'],
                            'user_id' => $uid,
                            'tag' => $v,
                            'createtime' => time()
                        );
                    }
                    $bool_add_tag = db::name('store_tag')->insertAll($map);
                }
            }
            if ($bool_add_tag && $bool_add_image && $bool_del_tag && $bool_del_image && $res) {
                // 提交事务
                Db::commit();
                return json_return('', 200, '编辑成功！');
            } else {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, '编辑失败！');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json_return('', 400, $e->getMessage());
        }
    }

    /**
     * 商品详情
     * @ApiMethod   (POST)
     * @param integer $goods_id 商品ID
     * */
    public function goods_info()
    {
        //$uid = $this->uid;
       // $uid = input('user_id', 61);
        $goods_id = input('post.goods_id');
        if (empty($goods_id)) {
            return json_return('', 400, '请选择编辑的商品！');
        }
        //店铺信息
        //$store = db::name('store')->where(array('user_id'=>$uid,'state'=>'finish'))->field('id,store_name,type')->find();
        //商品信息
        $goods = db::name("goods")->where('goods_id', $goods_id)->where('is_bbm', 0)->field('store_id,store_status,cat_id,user_id,goods_name,goods_number,goods_weight,fare,goods_desc,shop_price,promotion_method,promotion_price,promotion_designation_price,promotion_universal_price,goods_brief')->find();
        if (empty($goods)) {
            return json_return('', 400, '该商品不存在！');
        }
        //商品类别名称
        $cat_name = db::name('goods_category')->where('cat_id', $goods['cat_id'])->value('cat_name');
        $goods['cat_name'] = $cat_name ? $cat_name : '';

        if ($goods['goods_desc']) {
            $goods['goods_img'] = $goods['goods_desc'] ? explode(',', $goods['goods_desc']) : null;

            $goods['note'] = $goods['goods_brief'];
        } else {
            $goods['goods_img'] = array();
            $goods['note'] = $goods['goods_brief'];
        }
        //商品展示图
        $goods_gallery = db::name('goods_gallery')->where('goods_id', $goods_id)->field('img_url')->select();

        $goods['gallery'] = $goods_gallery ? $goods_gallery : array();

        //商品属性
        $goods_attr = db::name('goods_attr')
            ->alias('a')
            ->join('shop_attribute m', 'a.attr_id=m.attr_id')
            ->where('a.goods_id', $goods_id)
            ->group('a.attr_id')
            ->field('a.attr_id,m.attr_name')
            ->select();

        if ($goods_attr) {
            $goods['attr'] = $goods_attr;
            $goods_attr_ids = [];
            foreach ($goods_attr as $k => $v) {

                //查询规格
                $attr = db::name('goods_attr')->where('attr_id', $v['attr_id'])->field('goods_attr_id,attr_value')->select();

                $goods['attr'][$k]['attr_value'] = $attr;
                foreach ($attr as $a=>$r){
                    $goods_attr_ids[$r['goods_attr_id']] = $r['attr_value'];
                }
//                $attr_value = '';
//                foreach ($attr_name as $key => $value) {
//                    $attr_value .= $value['attr_value'] . '、';
//                }
//                $goods_attr[$k]['attr_value'] = trim($attr_value, '、');
            }

            $products = db::name('products')->where('goods_id',$goods_id)->field('product_id,goods_attr,product_number,product_price')->select();
            foreach ($products as $key=>$value){
                $prod = explode('|',$value['goods_attr']);

                foreach ($prod as $kk=>$vv){
                    $prod[$kk] = $goods_attr_ids[$vv];
                }
                $products[$key]['product_name'] = implode($prod,'|');
            }
            $goods['product'] = $products;
        }



        //$goods['attr'] = $goods_attr ? $goods_attr : array();
        $goods['img_url'] = config('fzk_img_url');

        return json_return($goods);
    }

    /**
     * 编辑商品
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @param integer $goods_id 商品ID
     * @param string $goods_name 商品名称
     * @param string $attr_name 商品规格名称
     * @param string $attr_arr 商品规格
     * @param integer $cat_id 分类
     * @param file $goods_img 商品图片
     * @ApiParams   (name="good_desc", type="string", required=false, description="商品描述")
     * @ApiParams   (name="img_desc", type="file", required=false, description="商品详情图")
     * @param integer $goods_number 商品库存
     * @param string $shop_price 商品价格
     * @param string $fare 运费
     * @param integer $promotion_method 优惠方式 1：指定店铺，2：全网通用，3：指定和通用都具有
     * @ApiParams   (name="promotion_designation_price", type="string", required=false, description="指定优惠金额")
     * @ApiParams   (name="promotion_universal_price", type="string", required=false, description="全网通优惠金额")
     * */
    public function edit_goods_info(Request $request)
    {
        $uid = $this->user_id;
        $input = $request->param();
       // $uid = input('user_id', 63);
        //商品名称
        $goods_id = input('post.goods_id');
        if (empty($goods_id)) {
            return json_return('', 400, '商品id不能为空！');
        }
        //商品名称
        $goods_name = input('post.goods_name');
        if (empty($goods_name)) {
            return json_return('', 400, '商品名称不能为空！');
        }
//        $attr_name = input('post.attr_name');
//        if (empty($attr_name)) {
//            return json_return('', 400, '请输入商品规格名称！');
//        }
//        $attr_arr = input('post.attr_arr/a');
//        if (empty($attr_arr)) {
//            return json_return('', 400, '请输入商品规格！');
//        }
        $cat_id = input('post.cat_id');
        if (empty($cat_id)) {
            return json_return('', 400, '请选择分类！');
        }
        $goods_imgs = input('post.goods_img/a');
        if (empty($goods_imgs)) {
            return json_return('', 400, '图片为空！');
        }
        $goods_brief = input('post.goods_brief');
        //$goods_desc = input('post.img_desc');
        $goods_desc = $input['goods_desc'];
        if (empty($goods_brief) && empty($goods_desc)) {
            return json_return('', 400, '商品描述和商品详情图不能同时为空！');
        }

        $goods_number = input('post.goods_number');
        if (empty($goods_number)) {
            return json_return('', 400, '商品库存不能为空！');
        }
        $shop_price = input('post.shop_price');
        if (empty($shop_price) || 100 * $shop_price <= 0) {
            return json_return('', 400, '请输入商品价格！');
        }
        $fare = input('post.fare');
        if (100 * $fare < 0) {
            return json_return('', 400, '请输入运费！');
        }
        $promotion_method = input('post.promotion_method');

//        $promotion_price = input('post.promotion_price');
        $promotion_designation_price = input('post.promotion_designation_price');
        $promotion_universal_price = input('post.promotion_universal_price');
        if (!empty($promotion_method)) {
            //            if (empty($promotion_price)) {
//                return json_return('', 400, '请输入优惠金额！');
//            }
            switch ($promotion_method) {
                case 1:
                    if (empty($promotion_designation_price)) {
                        return json_return('', 400, '请输入指定优惠金额！');
                    }
                    break;
                case 2:
                    if (empty($promotion_universal_price)) {
                        return json_return('', 400, '请输入全网通优惠金额！');
                    }
                    break;
                case 3:
                    if (empty($promotion_designation_price) || empty($promotion_universal_price)) {
                        return json_return('', 400, '请输入优惠金额！');
                    }
                    break;
            }
        }

        $store = db::name('store')->where(array('user_id' => $uid, 'state' => 'finish'))->field('id,store_name,type,house_number,longitude,latitude')->find();
        $Random = new Random();
        //商品详情
//         $imgs = '';
//         if($img_desc){
//             $imgs_desc = explode(',', $img_desc);
//             foreach ($imgs_desc as $val){
//                 $img  = add_image_pre($val);
//                 $imgs .= '<img src="'.$img.'"><br>';
//             }
//         }
//         if($good_desc){
//             $goods_desc = '<p>'.$good_desc.'</p>'.$imgs;
//         }else{
//             $goods_desc = $imgs;
//         }

        // $goods_imgs = explode(',', $goods_img);
        //$attr_arrs = explode('、', $attr_arr);
        //商品表
        $data = array(
            'goods_name' => $goods_name,
            'goods_number' => $goods_number,
            //'goods_status' => 'uncheck',
            'fare' => $fare,
            'cat_id' => $cat_id,
            'goods_brief' => $goods_brief,
            'goods_desc' => $goods_desc,
            'shop_price' => $shop_price,
            'goods_thumb' => $goods_imgs[0],
            'goods_img' => $goods_imgs[0],
            'original_img' => $goods_imgs[0],
            'promotion_method' => $promotion_method,
//            'promotion_price' => $promotion_price,
            'promotion_designation_price' => $promotion_designation_price,
            'promotion_universal_price' => $promotion_universal_price,
            'location' => $store['house_number'],
            'longitude' => $store['longitude'],
            'latitude' => $store['latitude'],
            'checktime' => 0
        );

        //开启事务
        Db::startTrans();
        try {

            $goods = db('Goods')->where('goods_id', $goods_id)->find();
            //重新审核
            if ($goods['goods_status'] != 'onlin') {
                locationEdit($goods_id, 5);
            }
            if ($goods['is_recommend'] == 1) {
                locationEdit($goods_id, 2);
            }
            $data['goods_status'] = 'uncheck';

            //商品信息
            $goods_info = db::name("goods")->where('goods_id', $goods_id)->field('goods_sn')->find();
            //添加商品表数据
            $goods = db::name("goods")->where('goods_id', $goods_id)->update($data);

            $attr_state = input('post.attr_state');
            //todo 属性规格 0=无变化 1=修改 2=新增/删除
            $goodsAttrMD = new GoodsAttr();
            $attrMD = new Attribute();
            $productMD = new Products();

            //商品类型
            $goods_type_id = db::name('goods_type')->where('cat_name', 'like', $goods_info['goods_sn'] . "%")->where('user_id', $uid)->field('cat_id,cat_name')->find();
            if ($goods_info['goods_sn'] . '_' . $data['goods_name'] == $goods_type_id['cat_name']) {
                $goods_type = true;
            } else {
                $goods_type = db::name('goods_type')->where('cat_id', $goods_type_id['cat_id'])->update(array('cat_name' => $goods_info['goods_sn'] . '_' . $data['goods_name']));
            }
            $attr = input('post.attr/a');
            $product = input('post.product/a');
            switch ($attr_state) {
                case 1:
                    $goods_attr_data = [];
                    $attr_data = [];
                    foreach ($attr as $k => $v) {
                        $attr_data[$k]['attr_id'] = $v['goods_attr_id'];
                        $attr_data[$k]['attr_name'] = $v['attr_name'];
                        $attr_data[$k]['attr_values'] = '';
                        foreach ($v['attr_value'] as $kk => $vv) {
                            $attr_data[$k]['attr_values'] .= $vv['attr_value'] . ',';
                            $goods_attr_data[] = [
                                'goods_attr_id' => $vv['goods_attr_id'],
                                'attr_value' => $vv
                            ];
                        }

                        $attr_data[$k]['attr_values'] = rtrim($attr_data[$k]['attr_values'], ',');
                    }
                    $attrRet = $attrMD->saveAll($attr_data);
                    $goodsAttrRet = $goodsAttrMD->saveAll($goods_attr_data);

                    $productRet = $productMD->allowField((['product_number', 'product_price']))->saveAll($product);
                    break;
                case 2:
                    //删除商品属性
                    $goodsAttrDel = GoodsAttr::destroy(['goods_id' => $goods_id]);
                    $productDel = Products::destroy(['goods_id' => $goods_id]);
                    $attrDel = Attribute::destroy(['cat_id' => $goods_type_id['cat_id']]);

                    if (!empty($attr)) {
                        $attr_cat = [];
                        $attr_goods = [];

                        foreach ($attr as $cat_k => $cat_v) {
                            $cat_attr_id = db::name('attribute')->insertGetId(['cat_id' => $goods_type_id['cat_id'], 'attr_name' => $cat_v['attr_name']]);
                            $cat_values = '';
                          //  dump($cat_v);
                            foreach ($cat_v['attr_value'] as $k => $v) {
                                $cat_values .= $v . ',';

                                $attr_data = array(
                                    'goods_id' => $goods_id,
                                    'attr_id' => $cat_attr_id,
                                    'attr_value' => $v
                                );
                                $goods_attr_id = db::name('goods_attr')->insertGetId($attr_data);
                                $attr_goods[$cat_attr_id . '_' . $v] = $goods_attr_id;
                            }
                            $attr_cat[$cat_k] = $cat_attr_id;
                            $cat_attr_up = db::name('attribute')->where('attr_id', $cat_attr_id)->update(['attr_values' => $cat_values]);
                        }
                      //  dump($attr_cat);die;
                        $productData = [];

                        foreach ($product as $key => $value) {
                            $product_name = explode('|', $value['product_name']);
                            foreach ($product_name as $kk => $vv) {
                                $product_name[$kk] = $attr_goods[$attr_cat[$kk] . '_' . $vv];
                            }

                            $productData[$key]['goods_attr'] = implode($product_name, '|');
                            $productData[$key]['goods_id'] = $goods_id;
                            $productData[$key]['product_market_price'] = $value['product_price'];
                            $productData[$key]['product_price'] = $value['product_price'];
                            $productData[$key]['product_number'] = $value['product_number'];
                        }

                        $product_ret = db::name('products')->insertAll($productData);
//                        dump($productData);die;
                        if (!$product_ret) {
                            // 回滚事务
                            Db::rollback();
                            return json_return('', 400, '插入商品规格失败！');
                        }
                    }
                    break;

            }



            //删除之前的图片
            $goods_gallery_del = db::name('goods_gallery')->where('goods_id', $goods_id)->delete();
            //商品图片
            $map = array();
            foreach ($goods_imgs as $value) {
                $map[] = array(
                    'goods_id' => $goods_id,
                    'img_url' => $value,
                    'thumb_url' => $value,
                    'img_original' => $value,
                );
            }
            $goods_gallery = db::name('goods_gallery')->insertAll($map);

            if ($goods && $goods_type && $goods_gallery) {
                $pc_data = array(
                    "title" => "商品审核",
                    "content" => "有新的商品申请上架审核,点击前往处理",
                    "type" => "5"
                );
                uptoPC($pc_data);
                // 提交事务
                Db::commit();
                return json_return('', 200, '提交成功！');
            } else {
                // 回滚事务
                Db::rollback();
                return json_return('', 400, '提交失败！');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json_return('', 400, $e->getMessage());
        }
    }
    //店铺资金记录
    public function storeMoneyLog_Handle(){
        $page = input('page', 1);
        $limit = input('limit', '');
        $offset = ($page - 1) * $limit;
        $page_offset = "$offset,$limit";
        $list = Db::name('store_money_log')->where(['user_id' => $this->user_id, 'type' => ['in', '1,5'],'is_show'=>1])->limit($page_offset)->order('createtime desc')->select();

        $count = Db::name('store_money_log')->where(['user_id' => $this->user_id, 'type' => ['in', '1'],'is_show'=>1])->count();
        return json_return_layui($list, $count);

    }
    public function storeMoneyLog()
    {
        return $this->fetch();
    }
    public function deleteMoneyLog(){
        $id = input('post.id');
        $rs = Db::name('store_money_log')->where('id', $id)->update(['is_show'=>0]);
        if ($rs) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Store/storeMoneyLog')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }

    }

}