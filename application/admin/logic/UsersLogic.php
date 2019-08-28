<?php

/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: 当燃
 * Date: 2015-09-09
 */
 
namespace app\admin\logic;

use think\Model;
use think\Db;

class UsersLogic extends Model
{    
    
    /**
     * 获取指定用户信息
     * @param $uid int 用户UID
     * @param bool $relation 是否关联查询
     *
     * @return mixed 找到返回数组
     */
    public function detail($uid, $relation = true)
    {
        $user = M('users')->where(array('user_id' => $uid))->relation($relation)->find();
        return $user;
    }
    
    /**
     * 改变用户信息
     * @param int $uid
     * @param array $data
     * @return array
     */
    public function updateUser($uid = 0, $data = array())
    {
        $db_res = M('users')->where(array("user_id" => $uid))->data($data)->save();
        if ($db_res) {
            return array(1, "用户信息修改成功");
        } else {
            return array(0, "用户信息修改失败");
        }
    }
    
    
    /**
     * 添加用户
     * @param $user
     * @return array
     */
    public function addUser($user)
    {
		$user_count = Db::name('users')
				->where(function($query) use ($user){
//					if ($user['email']) {
//						$query->whereOr('email',$user['email']);
//					}
					if ($user['mobile']) {
						$query->where('mobile',$user['mobile']);
					}
				})
				->count();
		if ($user_count > 0) {
			return array('status' => -1, 'msg' => '账号已存在');
		}
    	$user['password'] = encrypt_a($user['password']);
    	$user['reg_time'] = time();
    	//系统生成推荐码
    	$user['yss_tjm']=substr(md5(uniqid(mt_rand(1000, 9999), true)), 20);
    	$user_id = M('users')->add($user);
    	if(!$user_id){
    		return array('status'=>-1,'msg'=>'添加失败');
    	}else{
//    		$pay_points = tpCache('basic.reg_integral'); // 会员注册赠送积分
//    		if($pay_points > 0)
//    			accountLog($user_id, 0 , $pay_points , '会员注册赠送积分'); // 记录日志流水
    		return array('status'=>1,'msg'=>'添加成功','user_id'=>$user_id);
    	}
    }

    /**
     * 设置提现密码
     * @param $user_id
     * @param $new_password  新密码
     * @param $confirm_password  确认密码
     * @return array
     */
    public function withdrawalPwd($user_id,$new_password,$confirm_password){
//        $data=[
//            'password' => $new_password,
//            'password2' =>$confirm_password,
//        ];
//        $UserRegvalidate = Loader::validate('User');
//        if(!$UserRegvalidate->scene('set_pwd')->check($data)){
//            return array('status'=>-1,'msg'=>$UserRegvalidate->getError(),'result'=>'');
//        }
        $row = M('admin_operator')->where("admin_id",$user_id)->update(array('payment_password'=>encrypt_a($new_password)));
        if(!$row){
            return array('status'=>-1,'msg'=>'修改失败','result'=>'');
        }
//        $url = session('payPriorUrl') ? session('payPriorUrl'): U('User/userinfo');
//        session('payPriorUrl',null);
        return array('status'=>1,'msg'=>'修改成功');
    }

}