<?php
namespace app\admin\controller;
use think\Db;
use app\admin\model\Order as OrderModel;

class Order extends Base{
    /*
 * 提醒用户付款
  *
 * */
    public function remind_payment()
    {
        $id = $this->user_id;
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

    /**
     * 订单详情
     * @user wj
     * */
    public function order_info(){
        $data=[
            // 'user_id'=>$this->user_id,
            'user_id'=>input('user_id',61),
            'order_id'=>input('order_id/d',0),
            'type'=>input('type',1)//1用户订单详情2商家订单详情
        ];
        if($data['order_id']<1){
            return json_return('',400,'订单id错误');
        }
        $res=OrderModel::get_order_info($data);
        if($res){
            return json_return($res);
        }
        return json_return('',400,'订单不存在');
    }





}