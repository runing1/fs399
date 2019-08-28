<?php
namespace app\admin\controller;
use think\Db;
use app\admin\model\Goods as GoodsMD;
use app\admin\model\WinLocationLog;

class Goods extends Base{
    /**
     * 橱窗消费详情
     * @return bool
     */
    public function window_record(){
        $user_id= $this->user_id;
        $goods = GoodsMD::where(['user_id'=>$user_id,'is_bbm'=>GoodsMD::WIN_LOCATION_CATE])->find();
        $limit = input('limit','10');
        $page = input('page','1');
        if(!$goods){
            return json_return('',400,'暂无橱窗');
        }
        $where['store_id'] = $goods['store_id'];
        $where['type'] = 3;
        $where['created_at'] = ['between',date("Y-m-d",strtotime("-1 day")).','.date('Y-m-d')];
        $yesterday_money = WinLocationLog::where($where)->sum('money');
        $where['created_at'] = ['between',date('Y-m-d').','.date('Y-m-d',strtotime("+1 day"))];
        $today_money = WinLocationLog::where($where)->sum('money');
        $count = WinLocationLog::where('user_id',$user_id)->count();
        $record = WinLocationLog::getRecord($user_id,$page,$limit);

        $data = [];
        foreach ($record as $k=>$v){
            $data[$v['year']][] = $v;
        }
        $dataBuy = [];
        $total = 0;
        $monthRecord = WinLocationLog::getTotalAmount($goods['user_id']);
        foreach ($monthRecord as $k=>$v){
            $dataBuy[$v['year']] = $v['month_income'];
            $total += $v['month_income'];
        }
        $list = [];
        $list['today_income']= $today_money;
        $list['yesterday_income']= $yesterday_money;
        $list['remainder_num']= $goods['goods_number']-getWinStock($user_id);
        $list['transfer_fee']= $goods['shop_price'];
        $detail =[];
        $i = 0;
        foreach ($data as $key=>$value){
            foreach ($value as $k=>$v){
                $detail[$i]['year'] = $v['year'];
                $detail[$i]['month'] = $v['month'];
                $detail[$i]['month_income'] = isset($dataBuy[$key])?$dataBuy[$key]:'0.00';
                switch ($v['type']){
                    case 1:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '展位消耗-'.$v['goods_name'];
                        break;
                    case 2:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '展位恢复-'.$v['goods_name'];
                        break;
                    case 3:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '橱窗收入-'.$v['store_name'];
                        break;
                    case 4:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '橱窗位';
                        break;
                    case 5:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '上架-'.$v['goods_name'];
                        break;
                    case 6:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '下架-'.$v['goods_name'];
                        break;
                    case 7:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '审核拒绝-'.$v['goods_name'];
                        break;
                    case 8:
                        $detail[$i]['month_detail'][$k]['operation_name'] = '赠送橱窗位';
                        break;

                }
                $detail[$i]['month_detail'][$k]['operation_thumb'] = '';
                $detail[$i]['month_detail'][$k]['operation_num'] = $v['numbers'];
                $detail[$i]['month_detail'][$k]['operation_time'] = $v['created_at'];
                $detail[$i]['month_detail'][$k]['operation_type'] = $v['type'];
                $detail[$i]['month_detail'][$k]['operation_type'] = $v['type'];
            }
            $i++;
        }
        $list['total_income']= $total;
        $list['detail'] = $detail;
        $list['count'] = $count;
        return json_return($list);
    }

    /**
     * 验证支付密码是否存在
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * */
    public function yz_pay_password(){
        $user_id=$this->uid;
        $password=db::name('user')->where('id',$user_id)->value('payment_salt');
        if($password){
            return json_return('',200,'支付密码已设置');
        }
        return json_return('',10001,'支付密码尚未设置');
    }

}