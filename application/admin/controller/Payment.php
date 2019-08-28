<?php
namespace app\admin\controller;
use think\Db;
use think\Request;

/**
 * 付款回调 接口
 */
class Payment
{

    /**
     * 购买升级礼包--支付宝回调 (new)
     * */
    public function  pay_notify_url_operator(){
        vendor('Alipay.Alipay');
        //接收支付宝返回的异步消息
        $request =$_POST;
        $payment_id = 4;
        $order_sn = $request['out_trade_no'];
        $trade_no = $request['trade_no'];
        //debug_log('AlipayNotify', $request);
        //获取签名类型
        $signType = $request['sign_type'];
        //验证签名
        $alipay = new \Alipay();
        $flag = $alipay->rsaCheck($request, $signType)?:0;
        //debug_log("alipay_callback_dalibao",json_encode($request)."--".$flag);
        //判断验证签名是否成功
        if ($flag){
            //支付成功:TRADE_SUCCESS   交易完成：TRADE_FINISHED
            if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED'){
                $orders = db::name("pay_order")
                    ->where("order_num",$order_sn)
                    ->where('is_pay',0)
                    ->find();
                if($orders){
                    Db::startTrans();
                    try{
                        //支付总额
                        $order_amount = $orders['total_goods_price'] + $orders['total_shipping_price'];
                        $up_detail = db::name('updetail')->where('order_id',$orders['order_ids'])->find();
                        //用户信息
                        $user = db::name('user')
                            ->where('id',$up_detail['store_uid'])
                            ->field('id,user_money,username,level,mobile,payment_salt,payment_password,parent,operator_id')
                            ->find();
                        //修改付款订单状态
                        $bool_pay_order = db::name('pay_order')->where('id',$orders['id'])->update(array('is_pay'=>1));
                        //修改订单状态
                        $bool_order = db::name('order')
                            ->where('order_id','in',$orders['order_ids'])
                            ->where(array('order_status'=>7,'is_bbm'=>1))
                            ->update(array('order_status'=>3,'pay_status'=>1,'pay_id'=>2,'pay_name'=>'支付宝','real_money'=>$order_amount,'pay_time'=>time()));

                        //添加用户的资金变化记录
                        $discount = get_user_discount($user['id']);
                        $bool_user_money = db::name('user_money_log')->insertGetId(array(
                            'user_id'             => $user['id'],
                            'order_sn'            => $orders['order_ids'],
                            'trade_no'            => $trade_no,
                            'money'               => -$order_amount,
                            'type'                => 2,
                            'pay_type'                => 5,
                            'before_money'        => $user['user_money'],
                            'after_money'         => $user['user_money'],
                            'before_discount'     => $discount['discount']? : '0',
                            'after_discount'      => $discount['discount']? : '0',
                            'before_discount_all' => $discount['discountAll']? : '0',
                            'after_discount_all'  => $discount['discountAll']? : '0',
                            'remark'              => '支付宝支付升级商品',
                            'createtime'          => time()
                        ));

                        //todo 审核逻辑
                        $ret = checkUpgrade($up_detail['id'],$user['id']);

//                        //运营商奖励
//                        if(!empty($user['operator_id'])){
//                            $flag=getOperatorReward($user,$order_amount,2,4);
//                        }

                        if($bool_pay_order && $bool_order && $bool_user_money && $ret['state'] && $flag){
                            $redis = Redis();
                            $red_dev_code = $redis->get("dev_code".$ret['data']['user_id']);
                            if($red_dev_code){
                                jgSend2($red_dev_code,$ret['msg_data']['message'],'',10016);
                            }

                            Db::commit();
                            //success("","支付成功");
                            exit("success");
                        }else{
                            throw new \Exception("支付失败");
                        }
                    }catch(\Exception $e){
                        Db::rollback();
                        exit("fail");
                    }
                }else{
                   // exit("fail");
                    exit("success");
                }
            }else{
                exit("success");
            }
        }

    }

    /**
     * 购买升级礼包--支付宝同步回调 (new)
     * */
//    public function  pay_return_url_operator(){
//        vendor('Alipay.Alipay');
//        //接收支付宝返回的异步消息
//        $request =$_REQUEST;
//        $payment_id = 4;
//        $order_sn = $request['out_trade_no'];
//        $trade_no = $request['trade_no'];
//        //debug_log('AlipayNotify', $request);
//        //获取签名类型
//        $signType = $request['sign_type'];
//        //验证签名
//        $alipay = new \Alipay();
//        $flag = $alipay->rsaCheck($request, $signType)?:0;
//        //debug_log("alipay_callback_dalibao",json_encode($request)."--".$flag);
//        //判断验证签名是否成功
//        if ($flag){
//            //支付成功:TRADE_SUCCESS   交易完成：TRADE_FINISHED
//            if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED'){
//                $orders = db::name("pay_order")
//                    ->where("order_num",$order_sn)
//                    ->where('is_pay',0)
//                    ->find();
//                if($orders){
//                    Db::startTrans();
//                    try{
//                        //支付总额
//                        $order_amount = $orders['total_goods_price'] + $orders['total_shipping_price'];
//                        $up_detail = db::name('updetail')->where('order_id',$orders['order_ids'])->find();
//                        //用户信息
//                        $user = db::name('user')
//                            ->where('id',$up_detail['store_uid'])
//                            ->field('id,user_money,username,level,mobile,payment_salt,payment_password,parent,operator_id')
//                            ->find();
//                        //修改付款订单状态
//                        $bool_pay_order = db::name('pay_order')->where('id',$orders['id'])->update(array('is_pay'=>1));
//                        //修改订单状态
//                        $bool_order = db::name('order')
//                            ->where('order_id','in',$orders['order_ids'])
//                            ->where(array('order_status'=>7,'is_bbm'=>1))
//                            ->update(array('order_status'=>3,'pay_status'=>1,'pay_id'=>2,'pay_name'=>'支付宝','real_money'=>$order_amount,'pay_time'=>time()));
//
//                        //添加用户的资金变化记录
//                        $discount = get_user_discount($user['id']);
//                        $bool_user_money = db::name('user_money_log')->insertGetId(array(
//                            'user_id'             => $user['id'],
//                            'order_sn'            => $orders['order_ids'],
//                            'trade_no'            => $trade_no,
//                            'money'               => -$order_amount,
//                            'type'                => 2,
//                            'pay_type'                => 5,
//                            'before_money'        => $user['user_money'],
//                            'after_money'         => $user['user_money'],
//                            'before_discount'     => $discount['discount']? : '0',
//                            'after_discount'      => $discount['discount']? : '0',
//                            'before_discount_all' => $discount['discountAll']? : '0',
//                            'after_discount_all'  => $discount['discountAll']? : '0',
//                            'remark'              => '支付宝支付升级商品',
//                            'createtime'          => time()
//                        ));
//
//                        //todo 审核逻辑
//                        $ret = checkUpgrade($up_detail['id'],$user['id']);
//
////                        //运营商奖励
////                        if(!empty($user['operator_id'])){
////                            $flag=getOperatorReward($user,$order_amount,2,4);
////                        }
//
//                        if($bool_pay_order && $bool_order && $bool_user_money && $ret['state'] && $flag){
//                            $redis = Redis();
//                            $red_dev_code = $redis->get("dev_code".$ret['data']['user_id']);
//                            if($red_dev_code){
//                                jgSend2($red_dev_code,$ret['msg_data']['message'],'',10016);
//                            }
//
//                            Db::commit();
//                            success("","支付成功");
//                        }else{
//                            throw new \Exception("支付失败");
//                        }
//                    }catch(\Exception $e){
//                        Db::rollback();
//                        exit("fail");
//                    }
//                }else{
//                    exit("fail");
//                }
//            }else{
//                exit("success");
//            }
//        }
//
//    }


    public function pay_return_url_operator(Request $request){
        //接收支付宝返回的异步消息
        vendor('Alipay.Alipay');
        $input =$request->param();
        $payment_id = 4;
        $order_sn = $input['out_trade_no'];
        $trade_no = $input['trade_no'];
        db::name('pay_log')->insert(['info'=>json_encode($input)]);
        //debug_log('AlipayNotify', $request);
        //获取签名类型
        $signType = $input['sign_type'];
        //验证签名
        $alipay = new \Alipay();
        $flag = $alipay->rsaCheck($input, $signType)?:0;
        //判断验证签名是否成功
        if ($flag){
            //支付成功:TRADE_SUCCESS   交易完成：TRADE_FINISHED
            if ($input['trade_status'] == 'TRADE_SUCCESS' || $input['trade_status'] == 'TRADE_FINISHED'){
                $orders = db::name("pay_order")
                    ->where("order_num",$order_sn)
                    ->where('is_pay',0)
                    ->find();
                if($orders){
                    Db::startTrans();
                    try{
                        //支付总额
                        $order_amount = $orders['total_goods_price'] + $orders['total_shipping_price'];
                        $up_detail = db::name('updetail')->where('order_id',$orders['store_order_id'])->find();
                        //用户信息
                        $user = db::name('user')
                            ->where('id',$up_detail['store_uid'])
                            ->field('id,user_money,level,payment_salt,payment_password,parent')
                            ->find();

                        $store = db::name('store')->where('id',$up_detail['store_id'])->find();
                        //查询订单信息
                        $order = db::name('order')
                            ->alias('a')
                            ->join('shop_order_goods b', 'a.order_id=b.order_id')
                            ->join('shop_goods c', 'b.goods_id=c.goods_id')
                            ->where('a.order_id', $orders['store_order_id'])
                            ->field('a.store_id,b.goods_id,c.goods_number as num,c.sales_volume,a.total_amount,a.order_sn,a.pay_id')
                            ->find();

                        //修改付款订单状态
                        $bool_pay_order = db::name('pay_order')->where('id',$orders['id'])->update(array('is_pay'=>1));
                        //修改订单状态
                        $bool_order = db::name('order')
                            ->where('order_id','in',$orders['order_ids'])
                            ->where(array('order_status'=>7,'is_bbm'=>3))
                            ->update(array('order_status'=>3,'pay_status'=>1,'pay_id'=>2,'pay_name'=>'支付宝','real_money'=>$order_amount,'pay_time'=>time()));

                        //添加用户的资金变化记录
                        $discount = get_user_discount($user['id']);
                        $bool_user_money = db::name('user_money_log')->insertGetId(array(
                            'user_id'             => $user['id'],
                            'order_sn'            => $orders['order_ids'],
                            'trade_no'            => $trade_no,
                            'money'               => -$order_amount,
                            'type'                => 2,
                            'pay_type'                => 5,
                            'before_money'        => $user['user_money'],
                            'after_money'         => $user['user_money'],
                            'before_discount'     => $discount['discount']? : '0',
                            'after_discount'      => $discount['discount']? : '0',
                            'before_discount_all' => $discount['discountAll']? : '0',
                            'after_discount_all'  => $discount['discountAll']? : '0',
                            'remark'              => '支付宝支付升级商品',
                            'createtime'          => time()
                        ));

                        //修改商铺资金
                        $funds = $store['funds']+$order['total_amount'];
                        $bool_store = db::name('store')->where('id', $up_detail['store_id'])->update(array('funds' => $funds));
                        $pay_data = ['余额','支付宝','微信'];
                        $bool_store_money = db::name('store_money_log')->insertGetId(array(
                            'user_id' => $user['id'],
                            'order_sn' => $order['order_sn'],
                            'trade_no' => '',
                            'type' => 1,
                            'pay_type' => $order['pay_id']-1, // 0余额 1支付宝 2微信'
                            'money' => $order['total_amount'],
                            'before_money' => $store['funds'],
                            'after_money' => $funds,
                            'remark' => $pay_data[$order['pay_id']-1].'支付升级商品',
                            'createtime' => time()
                        ));

                        //todo 审核逻辑
                        $ret = checkUpgrade($up_detail['id'],$up_detail['user_id']);

                        if($bool_pay_order && $bool_order && $bool_user_money && $ret['state'] && $bool_store && $bool_store_money ){
                            $redis = Redis();
                            $red_dev_code = $redis->get("dev_code".$ret['data']['user_id']);
                            if($red_dev_code){
                                jgSend2($red_dev_code,$ret['msg_data']['message'],'',10016);
                            }
                            Db::commit();
                            success("","支付成功");
                        }else{
                            throw new \Exception("支付失败");
                        }
                    }catch(\Exception $e){
                        Db::rollback();
                        exit("fail");
                    }
                }else{
                    exit("fail");
                }
            }else{
                exit("success");
            }
        }
    }




}