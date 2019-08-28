<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Store extends Model {
    /*
         * 我的商铺订单
         *
         * */
    public static function get_order($data){
        $res  = array();
        $list = array();
        $comp = array();
        if($data['key'] != ''){
            $count=0;
        }else{
            /*
             *待付款 0
             *待发货 1
             *待收货 2
             **/
            $where = array(
                'a.store_is_del' => 0,
                'a.b_uids'       => $data['user_id'],
                'a.order_status' => $data['type']-1,
                'a.order_type' => '0'
            );
            //获取订单信息
            $order = db::name('order')
                ->alias('a')
                ->join('shop_store s','s.id=a.store_id')
                ->field('a.b_uids,a.order_id,a.order_sn,a.total_amount,a.shipping_price,a.store_id,a.store_name,s.type,a.order_status,a.user_id,a.real_money')
                ->where($where)
                ->limit($data['limit'])
                ->page($data['page'])
                ->order('a.order_id desc')
                ->select();
            $count=db::name('order')
                ->alias('a')
                ->join('shop_store s','s.id=a.store_id')
                ->where($where)
                ->count();
            if(!empty($order)){
                //获取对应的商品信息
                if($data['type'] == 7){
                    //被投诉订单
                    $order_id = '';
                    foreach ($order as $k => $v){
                        $order_id.=$v['order_id'].',';
                    }
                    $order_id = substr($order_id,0,-1);

                    $comp = db::name('complaint')
                        ->where('b_uids',$data['user_id'])
                        ->where('order_id','in',$order_id)
                        ->order('createtime desc')
                        ->field('order_sn,username,mobile,shipping_num,reason,images,createtime')
                        ->select();
                    foreach ($comp as $key => $val){
                        $image = explode(',', $val['images']);
                        foreach ($image as $value){
                            $comp[$key]['image'][] = add_image_pre($value);
                        }
                        $comp[$key]['time'] = date('Y-m-d H:i:s',$val['createtime']);
                    }
                }else{
                    $order_id = '';
                    foreach ($order as $k => $v){
                        $order_id.=$v['order_id'].',';
                    }
                    $order_id = substr($order_id,0,-1);
                    $goods = db::name('order_goods')
                        ->field('goods_img as logo,goods_num,goods_name,goods_price,spec_key_name,order_id,return_status,goods_id,rz_time_id')
                        ->where('deleted=0')
                        ->where('order_id','in',$order_id)
                        ->select();//dump($order_id);die;
                    //合并订单
                    foreach ($order as $k => $v){
                        $arrz    = array();
                        //用户昵称
                        $nickname = db::name('user')->where('id',$v['user_id'])->value('username');
                        $v['nickname'] = $nickname ? $nickname : '';

                        $res[$k] = $v;
                        $nums    = 0;
                        foreach ($goods as $key => $value){
                            if($v['store_id'] == 0){
                                $value['goods_price'] = strval($value['goods_price']/$value['goods_num']);
                            }
                            if($value['logo'] == ''){
                                $logo = db::name('goods')->where('id',$value['goods_id'])->value('goods_thumb');
                                $value['logo'] = add_image_pre($logo);
                            }else{
                                $value['logo'] = add_image_pre($value['logo']);
                            }
                            //如果是酒店
                            if (!empty($value['rz_time_id'])){
                                $days = explode(',',$value['rz_time_id']);
                                $value['rz_day'] = count($days);
                                $rz_time = Db::name('hotel_price')
                                    ->field('max(on_day) as maxDay,min(on_day) as minDay')
                                    ->where('id','in',$value['rz_time_id'])
                                    ->find();
                                $maxDay = $rz_time['maxDay'];
                                $rz_time['maxDay'] = date('Y-m-d',strtotime("$maxDay+1 day")) ;
                                $value['rz_time'] = $rz_time;
                            }else{
                                $value['rz_day']  = 0;
                                $value['rz_time'] = null;
                            }
                            if($v['order_id'] == $value['order_id']){
                                $arrz[$nums]  = $value;
                                $nums++;
                            }
                        }

                        $res[$k]['goods'] = $arrz;
                    }
                }
            }
        }
        $list = array(
            'comp' => $comp,
            'res'  => $res,
            'count'=>$count
        );
        return $list;
    }
}