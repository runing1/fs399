<?php

namespace app\admin\model;
use think\Model;
use think\Db;
/**
 * 订单model
 */
class Order extends Model
{

    /*
     * 订单状态
     * @user wj
     * */
    public static function get_status($id){
        $order_status=[];
        switch($id){
            case "0":/* 全部 */
                $order_status=[0,1,2,3,4,5,6];
                break;
            case "1":/* 待付款 */
                $order_status=[0];
                break;
            case "2":/* 待发货 */
                $order_status=[1];
                break;
            case "3":/* 待收货 */
                $order_status=[2];
                break;
            case "4":/* 已完成 */
                $order_status=[3];
                break;
            default:
                error("未知的订单状态");
        }
        return $order_status;
    }
    /*
     * 我的订单
     *@user wj
     * */
    public static function get_order($data){
        $res=[];
        $order_status=self::get_status($data['type']);
        $where='a.is_del=0 and a.is_bbm=0 and a.user_id='.$data['user_id'];
        if($data['key']!='') {
            //假设是订单号
            $where .= " and a.order_sn like'%" . $data['key'] . "%'";
            $order=db::name('order')
                ->alias('a')
                ->field('a.b_uids,a.order_id,a.order_sn,a.order_status,a.real_money,a.total_amount,a.shipping_price,store_id,store_name,rz_people,rz_mobile')
                ->where($where)
                ->where(['order_status'=>['in',$order_status]])
                ->limit($data['limit'])
                ->order('a.order_id desc')
                ->select();
            //如果是订单号
            if(!empty($order)){
                //获取对应的商品信息
                $order_id='';
                foreach ($order as $k=>$v){
                    $order_id.=$v['order_id'].',';
                    $order[$k]['type'] = Db::name('store')->where('id',$v['store_id'])->value('type');
                }
                $order_id=substr($order_id,0,-1);
                $goods=db::name('order_goods')
                    ->alias('a')
                    ->field('a.goods_num,a.goods_name,a.goods_price,a.spec_key_name,order_id,return_status,a.goods_id,a.goods_img as logo,rz_time_id')
                    ->where('a.order_id','in',$order_id)
                    ->where('a.deleted=0')
                    ->select();

                //合并订单
                foreach ($order as $k=>$v){
                    $res[$k]=$v;
                    $numn=0;
                    $arr=[];
                    foreach ($goods as $i=>$zo){
                        $zo['logo']=aimg($zo['logo']);
                        if (!empty($v['rz_time_id'])){
                            $days = explode(',',$zo['rz_time_id']);
                            $zo['rz_day'] = count($days);
                            $rz_time = Db::name('hotel_price')
                                ->field('max(on_day) as maxDay,min(on_day) as minDay')
                                ->where('id','in',$zo['rz_time_id'])
                                ->find();
                            $maxDay = $rz_time['maxDay'];
                            $rz_time['maxDay'] = date('Y-m-d',strtotime("$maxDay+1 day")) ;
                            $zo['rz_time'] = $rz_time;
                        }else{
                            $zo['rz_day'] = 0;
                            $zo['rz_time'] = null;
                        }
                        if($v['order_id']==$zo['order_id']){
                            $arr[$numn]=$zo;
                            $numn++;
                        }
                    }
                    $res[$k]['goods']=$arr;
                }
            }else{
                //搜索的是商品名
                $goods_where = " a.goods_name like'%" . $data['key'] . "%'";
                $goods_id=db::name('order_goods')
                    ->alias('a')
                    ->field('a.rec_id,a.goods_img as logo,a.goods_num,a.goods_name,a.goods_price,a.spec_key_name,a.order_id,a.return_status,rz_time_id')
                    ->where('c.user_id',$data['user_id'])
                    ->where('a.deleted=0')
                    ->where($goods_where)
                    ->where('order_status','in',$order_status)
                    ->join('shop_order c','c.order_id=a.order_id')
                    ->limit($data['limit'])
                    ->group('order_id')
                    ->order('a.order_id desc')
                    ->select();

                if(!empty($goods_id)){
                    $order_id='';
                    foreach ($goods_id as $k=>$v){
                        if (!empty($v['rz_time_id'])){
                            $days = explode(',',$v['rz_time_id']);
                            $goods[$k]['rz_day'] = count($days);
                            $rz_time = Db::name('hotel_price')
                                ->field('max(on_day) as maxDay,min(on_day) as minDay')
                                ->where('id','in',$v['rz_time_id'])
                                ->find();
                            $maxDay = $rz_time['maxDay'];
                            $rz_time['maxDay'] = date('Y-m-d',strtotime("$maxDay+1 day")) ;
                            $goods[$k]['rz_time'] = $rz_time;
                        }else{
                            $goods[$k]['rz_day'] = 0;
                            $goods[$k]['rz_time'] = null;
                        }
                        $order_id.=$v['order_id'].',';
                    }
                    $order_id=substr($order_id,0,-1);
                    $order=db::name('order')
                        ->alias('a')
                        ->field('a.b_uids,a.order_id,a.real_money,a.order_sn,a.order_status,a.total_amount,a.shipping_price,store_id,store_name,rz_people,rz_mobile')
                        ->where('order_id','in',$order_id)
                        ->select();
                    $goods=db::name('order_goods')
                        ->alias('a')
                        ->field('a.goods_img as logo,a.goods_num,a.goods_name,a.goods_price,a.spec_key_name,order_id,return_status,a.goods_id,rz_time_id')
                        ->where('a.order_id','in',$order_id)
                        ->select();
                    //合并订单

                    foreach ($order as $k=>$v){
                        $order[$k]['type'] = Db::name('store')->where('id',$v['store_id'])->value('type');
                        $res[$k]=$v;
                        $numz=0;
                        $arr=[];
                        foreach ($goods as $is=>$zs){
                            $zs['logo']=aimg($zs['logo']);
                            if($v['order_id']==$zs['order_id']){
                                $arr[$numz]=$zs;
                                $numz++;
                            }
                        }
                        $res[$k]['goods']=$arr;
                    }
                }
            }
        }else{
            //获取订单信息
            $order=db::name('order')
                ->alias('a')
                ->field('a.b_uids,a.order_id,a.order_sn,a.order_status,a.real_money,a.total_amount,a.shipping_price,store_id,store_name,rz_people,rz_mobile')
                ->where($where)
                ->where(['order_status'=>['in',$order_status],'order_type'=>'0'])
                ->limit($data['limit'])
                ->page($data['page'])
                ->order('a.order_id desc')
                ->select();

            if(!empty($order)){
                //获取对应的商品信息
                $order_id='';
                foreach ($order as $k=>$v){
                    $order_id.=$v['order_id'].',';
                    $order[$k]['type'] = Db::name('store')->where('id',$v['store_id'])->value('type');
                }
                $order_id=substr($order_id,0,-1);

                $goods=db::name('order_goods')
                    ->alias('a')
                    ->field('a.goods_img as logo,a.goods_num,a.goods_name,a.goods_price,a.spec_key_name,order_id,return_status,a.goods_id,rz_time_id')
                    ->where('a.deleted=0')
                    ->where('a.order_id','in',$order_id)
                    ->select();
                //合并订单
                foreach ($order as $k=>$v){
                    $arrz=[];
                    $res[$k]=$v;
                    $nums=0;
                    foreach ($goods as $ii=>$zz){
                        if (!empty($zz['rz_time_id'])){
                            $days = explode(',',$zz['rz_time_id']);
                            $zz['rz_day'] = count($days);
                            $rz_time = Db::name('hotel_price')
                                ->field('max(on_day) as maxDay,min(on_day) as minDay')
                                ->where('id','in',$zz['rz_time_id'])
                                ->find();
                            $maxDay = $rz_time['maxDay'];
                            $rz_time['maxDay'] = date('Y-m-d',strtotime("$maxDay+1 day")) ;
                            $zz['rz_time'] = $rz_time;
                        }else{
                            $zz['rz_day'] = 0;
                            $zz['rz_time'] = null;
                        }
                        $zz['logo']=aimg($zz['logo']);
                        if($v['order_id']==$zz['order_id']){
                            $arrz[$nums]=$zz;
                            $nums++;
                        }
                    }
                    $res[$k]['goods']=$arrz;
                }
            }
        }
        return $res;
    }

    /*
     * 订单详情
     * @user wj
     * */
    public static function get_order_info($data){
        //获取订单信息
        $where = array();
        if ($data['type']==1){
            $where['a.user_id'] = $data['user_id'];
        }elseif($data['type']==2){
            $where['a.b_uids'] = $data['user_id'];
        }else{
            return json_return('',400,'参数错误');
        }
        $order=db::name('order')->alias('a')
            ->join('store s','s.id=a.store_id','left')
            ->join('order_goods og','a.order_id=og.order_id','left')
            ->join('user_discount ud','og.discount_no=ud.discount_no','left')
            ->field('a.order_id,a.b_uids,a.shipping_time,a.confirm_time,a.pay_time,a.user_note,a.shipping_num,a.shipping_code,a.shipping_name,a.add_time,a.order_sn,a.order_status,a.consignee,a.province,a.city,a.district,a.twon,a.address,a.mobile,a.shipping_price,a.real_money,a.total_amount,a.store_id,a.store_name,a.use_coupon,a.coupon_amount,a.coupon_id,rz_people,rz_mobile,s.logo_image,s.mobile as storeMobile,s.type as storeType,ud.type')
            ->where($where)
            ->where('a.order_id',$data['order_id'])
            ->where('is_del=0')
            ->find();

        $res=[];
        if($order){
            //省份
            $order['province']=db::name('china')->where('id',$order['province'])->find()['name'];
            $order['city']=db::name('china')->where('id',$order['city'])->find()['name'];
            $order['district']=db::name('china')->where('id',$order['district'])->find()['name'];
            $order['twon']=db::name('china')->where('id',$order['twon'])->find()['name'];
            $order['logo_image']=aimg($order['logo_image']);
            //获取对应的商品信息
            $goods=db::name('order_goods')
                ->alias('a')
                ->field('a.rec_id,a.goods_img as logo,a.goods_id,a.goods_num,a.goods_name,a.goods_price,a.spec_key_name,return_status,a.freight,rz_time_id')
                ->where('order_id',$data['order_id'])
                ->select();
            foreach ($goods as $k=>$v){
                $goods[$k]['logo']=aimg($v['logo']);
                if (!empty($v['rz_time_id'])){
                    $days = explode(',',$v['rz_time_id']);
                    $goods[$k]['rz_day'] = count($days);
                    $rz_time = Db::name('hotel_price')
                        ->field('max(on_day) as maxDay,min(on_day) as minDay')
                        ->where('id','in',$v['rz_time_id'])
                        ->find();
                    $maxDay = $rz_time['maxDay'];
                    $rz_time['maxDay'] = date('Y-m-d',strtotime("$maxDay+1 day")) ;
                    $goods[$k]['rz_time'] = $rz_time;
                }else{
                    $goods[$k]['rz_day'] = 0;
                    $goods[$k]['rz_time'] = null;
                }
            }
            $res=$order;
            $res['goods']=$goods;
            $storeData = obtain_store($order['store_id'],$order['b_uids']);
            $res['logo_image'] = $storeData['logo_image'];
            $res['store_name'] = $storeData['shop_name'];
            $res['mobile'] = $storeData['mobile'];
            $res['qq'] = $storeData['qq'];
            /*if($order['store_id']>0){
                $storeData = Db::name('store')->field('qq,mobile')->where('id',$order['store_id'])->find();
                $res['qq']=$storeData['qq'];
                $res['mobile']=$storeData['mobile'];
            }else{
                $res['mobile']='';
                $res['qq']=config('kf_qq');
            }*/

        }
        return $res;
    }




    /*
     * 获取上级信息1
     * */
    public static function sup_user($data){
        $sup_user=db::name('user')
            ->field('id,invite_code,user_money,share_moisten_money,forecast_today,forecast_month,level,share_money')
            ->where('mobile',$data['invite_code'])
            ->find();
        return $sup_user;
    }





    /*
     * 订单投诉页面
     * @user wj
     * */
    public static function complain_order($userId,$order_sn){
        $data = Db::name('order')->alias('o')
            ->join('user u','u.id=o.user_id','left')
            ->field('u.username,u.mobile,order_sn,shipping_num')
            ->where(['order_sn'=>$order_sn,'o.user_id'=>$userId,'order_status'=>3])
            ->find();
        if (empty($data)){
            return json_return('',400,'查无此订单');
        }
        $data['mobile'] = hide_middle_mobile($data['mobile']);
        return json_return($data,200,'获取成功');
    }

    /*
     * 订单投诉处理
     * @user wj
     * */
    public static function complain_order_handle($userId,$order_sn,$goods_id,$remark,$image){
        $data = Db::name('order')->alias('o')
            ->join('user u','u.id=o.user_id','left')
            ->field('u.id,u.username,u.mobile,order_sn,b_uids,order_id,store_id,shipping_num')
            ->where(['order_sn'=>$order_sn,'u.id'=>$userId,'order_status'=>3])
            ->find();
        if(empty($data))
            return json_return('',400,'此订单不存在');
        $complaint = [
            'user_id'=>$userId,
            'username'=>$data['username'],
            'mobile'=>$data['mobile'],
            'goods_id'=>$goods_id,
            'order_id'=>$data['order_id'],
            'b_uids'=>$data['b_uids'],
            'order_sn'=>$order_sn,
            'shipping_num'=>$data['shipping_num'],
            'reason'=>$remark,
            'images'=>$image,
            'createtime'=>time()
        ];
        Db::startTrans();
        try{
            Db::name('complaint')->insert($complaint);
            Db::name('order')->where(['order_sn'=>$order_sn,'user_id'=>$userId])->update(['order_status'=>6,'is_return'=>1]);
            Db::name('order_goods')->where(['order_sn'=>$order_sn,'goods_id'=>$goods_id])->update(['return_status'=>2]);
            Db::commit();
            return json_return('',200,'处理成功');
        }catch (\Exception $e){
            Db::rollback();
            return json_return('',400,'处理失败');
        }
    }









}
