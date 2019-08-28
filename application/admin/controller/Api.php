<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: JY
 * Date: 2015-09-23
 */

namespace app\admin\controller;
use think\Db;

class Api extends Base {
    /*
     * 获取地区
     */
    public function getRegion(){
        $parent_id = I('get.parent_id',1);
        $data = M('region')->where("parent_id", $parent_id)->select();
        $html = '';
        if($data){
            foreach($data as $h){
                $html .= "<option value='{$h['region_id']}'>{$h['region_name']}</option>";
            }
        }
        echo $html;
    }

//短信发送
    public function sendSms()
    {
        header("Access-Control-Allow-Origin: *");
        $mobile =input('post.mobile');
        $work = input('post.work');
        $role = input('post.role');
        $statusStr = array(
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        );
        $smsapi = "http://api.smsbao.com/";
        $user = "fanzuke123"; //短信平台帐号
        $pass = md5("fanzuke"); //短信平台密码
        $code = mt_rand(100000, 999999);
        switch ($work){
            case 1:
                $content="【凡商优店】验证码{$code}，您正在进行身份验证，打死不要告诉别人哦！";
                $phone = "$mobile";
                $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
                break;
            case 2:
                $password='66666666';
                $content = "【凡商优店】尊敬的运营商：您的初始密码为：{$password}，请及时修改！";
                $phone = "$mobile";//要发送短信的手机号码
                $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
                break;
           
        }
        if($work == 1){
            if(!DB::name('admin_operator')->where('mobile', $mobile)->getField("mobile"))
                $this->ajaxReturn(['code'=>1003,'data'=> '' , 'msg'=>'该手机号未注册']);
        }

        $result = file_get_contents($sendurl);
        if($result == 0){
            DB::name('sms')->insert([
                'mobile' => $mobile,
                'code' => $code,
                'role' => $role,
                'work' => $work,
                'ip' => request()->ip(),
                'create_time' => date('Y-m-d H:i:s'),
                'end_time' => time() + 300
            ]);
            $this->ajaxReturn(['status'=>1,'data'=>'','msg'=>'验证码发送成功']);
        } else {
            $this->ajaxReturn(['status'=>0,'data'=>'','msg'=>$statusStr[$result]]);
        }
    }


//    public function getGoodsSpec(){
//        $goods_id = I('get.goods_id/d');
//        $temp = DB::name('spec_goods_price')->field("GROUP_CONCAT(`key` SEPARATOR '_' ) as goods_spec_item")->where('goods_id', $goods_id)->select();
//        $goods_spec_item = $temp[0]['goods_spec_item'];
//        $goods_spec_item = array_unique(explode('_',$goods_spec_item));
//        if($goods_spec_item[0] != ''){
//            $spec_item = DB::query("SELECT i.*,s.name FROM __PREFIX__spec_item i LEFT JOIN __PREFIX__spec s ON s.id = i.spec_id WHERE i.id IN (".implode(',',$goods_spec_item).") ");
//            $new_arr = array();
//            foreach($spec_item as $k=>$v){
//                $new_arr[$v['name']][] = $v;
//            }
//            $this->assign('specList',$new_arr);
//        }
//       return $this->fetch();
//    }
//    /*
//     * 获取商品价格
//     */
//    public function getSpecPrice(){
//        $spec_id = I('post.spec_id/d');
//        $goods_id = I('get.goods_id/d');
//        if(!is_array($spec_id)){
//            exit;
//        }
//        $item_arr = array_values($spec_id);
//        sort($item_arr);
//        $key = implode('_',$item_arr);
//        $goods = M('spec_goods_price')->where(array('key'=>$key,'goods_id'=>$goods_id))->find();
//        $info = array(
//            'status' => 1,
//            'msg' => 0,
//            'data' =>$goods['price'] ? $goods['price'] : 0
//        );
//        exit(json_encode($info));
//    }
//
//    //商品价格计算
//    public function calcGoods(){
//        $goods_id = I('post.goods/d'); // 添加商品id
//        $price_type = I('post.price') ? I('post.price') : 3; // 价钱类型
//        $goods_info = M('goods')->where(array('goods_id'=>$goods_id))->find();
//        if(!$goods_info['goods_id'] > 0)
//            exit; // 不存在商品
//        switch($price_type){
//            case 1:
//                $goods_price = $goods_info['market_price']; //市场价
//                break;
//            case 2:
//                $goods_price = $goods_info['shop_price']; //市场价
//                break;
//            case 3:
//                $goods_price = I('post.goods_price'); //自定义
//                break;
//        }
//
//        $goods_num = I('post.goods_num/d'); // 商品数量
//
//        $total_price = $goods_price * $goods_num; // 计算商品价格
//
//        $info = array(
//            'status'=>1,
//            'msg'=>'',
//            'data'=>$total_price
//        );
//        exit(json_encode($info));
//
//    }
//
//    public function checkNewVersion(){
//    	$last_d='last_d';$param = array($last_d.'omain'=>$_SERVER['HTTP_HOST'],'serial_number'=>time().mt_rand(100, 999),'install_time'=>time());$prl = 'http://ser';$vr = 'vice.tp-s';
//    	$crl = 'hop.cn/ind'.'ex.php';$drl = '?m=Ho'.'me&c=Ind'.'ex&a=us'.'er_pu'.'sh';httpRequest($prl.$vr.$crl.$drl,'post',$param);
//    }
}