<?php
namespace app\admin\controller;

use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Db;
use app\common\logic\Goods;

class Index extends Base
{

    public function index()
    {
        // $this->pushVersion();
        $admin_info = getAdminInfo(session('admin_id'));
        $unread = Db::name('user_message')->where(['user_id' => $this->user_id, 'operator_read' => 1,'type' => ['in', '2,5,6,16']])->count();
        //  $order_amount = M('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        $this->assign('admin_info', $admin_info);
        $this->assign('unread', $unread);
        $this->assign('menu', getMenuArr());   //view2
        return $this->fetch();
    }

    public function welcome()
    {
        $uid = $this->user_id;
        $cate = Goods::WIN_LOCATION_CATE;
        $storeData = db::name("user")
            ->alias('a')
            ->join('shop_store o', 'a.id=o.user_id', 'left')
            ->join('goods g', 'a.id=g.user_id and g.is_bbm=' . $cate, 'left')
            ->where('a.id', $uid)
            ->field('o.id,a.id as user_id,o.store_name,o.logo_image,o.funds,o.win_funds,o.type,a.level,o.manager_fee_time,o.state as store_state,a.store_status,a.level,o.cat_id,o.is_all,g.goods_number as num,note')
            ->find();
        if($storeData['id']){
            //待付款订单数量
            $unpay_order = db::name('order')->where(array('store_id' => $storeData['id'], 'pay_status' => 0, 'is_bbm' => 0, 'order_status' => 0))->count();
            //待发货订单数量
            $ungive_order = db::name('order')->where(array('store_id' => $storeData['id'], 'order_status' => 1, 'is_bbm' => 0, 'pay_status' => 1))->count();
            //待收货订单数量
            $unget_order = db::name('order')->where(array('store_id' => $storeData['id'], 'order_status' => 2, 'is_bbm' => 0, 'pay_status' => 1))->count();
            //待审核
            $uncheck_num = db::name('updetail')->where(array('store_uid' => $uid))->where('status', 'in', array('uncheck'))->count();
        }else{
            $unpay_order=$ungive_order=$unget_order=$uncheck_num=0;
        }
        $this->assign('unpay_order',$unpay_order);
        $this->assign('ungive_order',$ungive_order);
        $this->assign('unget_order',$unget_order);
        $this->assign('uncheck_num',$uncheck_num);
        $this->assign('sys_info', $this->get_sys_info());
        return $this->fetch();
    }

    public function get_sys_info()
    {
        $sys_info['os'] = PHP_OS;
        $sys_info['zlib'] = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode'] = (boolean)ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
        $sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl'] = function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv'] = phpversion();
        $sys_info['ip'] = GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown';
        $sys_info['max_ex_time'] = @ini_get("max_execution_time") . 's'; //脚本最大执行时间
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain'] = $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit'] = ini_get('memory_limit');
        $sys_info['version'] = file_get_contents(APP_PATH . 'admin/conf/version.php');
        $mysqlinfo = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version'] = $mysqlinfo[0]['version'];
        if (function_exists("gd_info")) {
            $gd = gd_info();
            $sys_info['gdinfo'] = $gd['GD Version'];
        } else {
            $sys_info['gdinfo'] = "未知";
        }
        return $sys_info;
    }

    // 在线升级系统
    public function pushVersion()
    {
        if (!empty($_SESSION['isset_push']))
            return false;
        $_SESSION['isset_push'] = 1;
        error_reporting(0);//关闭所有错误报告
        $app_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
        $version_txt_path = $app_path . '/application/admin/conf/version.php';
        $curent_version = file_get_contents($version_txt_path);

        $vaules = array(
            'domain' => $_SERVER['SERVER_NAME'],
            'last_domain' => $_SERVER['SERVER_NAME'],
            'key_num' => $curent_version,
            'install_time' => INSTALL_DATE,
            'serial_number' => SERIALNUMBER,
        );
        $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&" . http_build_query($vaules);
        stream_context_set_default(array('http' => array('timeout' => 3)));
        file_get_contents($url);
    }

    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal()
    {
        $table = I('table'); // 表名
        $id_name = I('id_name'); // 表主键id名
        $id_value = I('id_value'); // 表主键id值
        $field = I('field'); // 修改哪个字段
        $value = I('value'); // 修改字段值
        M($table)->where([$id_name => $id_value])->save(array($field => $value)); // 根据条件保存修改的数据
    }

    /**
     * 地址选择 省市区镇
     * @param integer $pid 上级ID
     * */
    public function get_region()
    {
        $pid = input('pid', 0);
        $data = db::name('china')->field('id,pid,name')->where(['pid' => $pid])->select();
        return json_return($data, 200, '获取成功');
    }

//    public function about(){
//    	return $this->fetch();
//    }
}