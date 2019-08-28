<?php

namespace app\common\logic;

use think\Db;
use think\Session;

class AdminLogic
{
    public function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ['status' => 0, 'msg' => '请填写账号密码'];
        }

      //Saas::instance()->ssoAdmin($username, $password);
        $condition['a.user_name'] = $username;
        $condition['a.password'] = encrypt_a($password);
        $admin = Db::name('admin_operator')->alias('a')->join('__ADMIN_OPERATOR_ROLE__ ar', 'a.role_id=ar.role_id')->where($condition)->find();
//dump($admin);die;

        if (!$admin) {
            return ['status' => 0, 'msg' => '账号密码不正确'];
        }

        $this->handleLogin($admin, $admin['act_list']);
        //运营商推荐码存入session
        $_SESSION['operator_tjm']=$admin['operator_tjm'];
        session('user_id',$admin['user_id']);
        session('admin_id',$admin['admin_id']);

        $url = session('from_url') ? session('from_url') : U('Admin/Index/index');
        return ['status' => 1, 'url' => $url];
    }

    public function handleLogin($admin, $actList)
    {
        Db::name('admin_operator')->where('admin_id', $admin['admin_id'])->save([
            'last_login' => time(),
            'last_ip' => request()->ip()
        ]);

        $this->sessionRoleRights($admin, $actList);

        session('admin_id', $admin['admin_id']);
        session('user_id',$admin['user_id']);
        session('last_login_time', $admin['last_login']);
        session('last_login_ip', $admin['last_ip']);

        adminLog('后台登录');
    }

    public function sessionRoleRights($admin, $actList)
    {
        if (Saas::instance()->isNormalUser()) {
            $roleRights = Saas::instance()->getRoleRights($actList);
        } else {
            $roleRights = $actList;
        }

        session('act_list', $roleRights);
    }

    public function logout($adminId)
    {
        session_unset();
        session_destroy();
        Session::clear();

        Saas::instance()->handleLogout($adminId);
    }


    function random($length = 6, $numeric = 0) {
        (PHP_VERSION < '4.2.0') && mt_srand((double) microtime() * 1000000);
        if ($numeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }

        return $hash;
    }
}






