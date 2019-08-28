<?php
use think\Log;
use think\Db;
use app\admin\model\Task;
/**
 * 管理员操作记录
 * @param $log_info string 记录信息
 */
function adminLog($log_info)
{
    $add['log_time'] = time();
    $add['admin_id'] = session('admin_id');
    $add['log_info'] = $log_info;
    $add['log_ip'] = request()->ip();
    $add['log_url'] = request()->baseUrl();
    M('admin_operatorlog')->add($add);
}


/**
 * 平台支出记录
 * @param $data
 */
function expenseLog($data)
{
    $data['addtime'] = time();
    $data['admin_id'] = session('admin_id');
    M('expense_log')->add($data);
}

function getAdminInfo($admin_id)
{

    $rs = D('admin_operator')->where("admin_id", $admin_id)->find();
    $rs['province'] = M('region')->where(array('region_id' => $rs['province_id']))->getField('region_name');
    $rs['city'] = M('region')->where(array('region_id' => $rs['city_id']))->getField('region_name');
    $rs['district'] = M('region')->where(array('region_id' => $rs['district_id']))->getField('region_name');
    return $rs;
}

function tpversion()
{
    //在线升级系统
    if (!empty($_SESSION['isset_push']))
        return false;
    $_SESSION['isset_push'] = 1;
    error_reporting(0);//关闭所有错误报告
    $app_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
    $version_txt_path = $app_path . '/application/admin/conf/version.php';
    $curent_version = file_get_contents($version_txt_path);

    $vaules = array(
        'domain' => $_SERVER['HTTP_HOST'],
        'last_domain' => $_SERVER['HTTP_HOST'],
        'key_num' => $curent_version,
        'install_time' => INSTALL_DATE,
        'cpu' => '0001',
        'mac' => '0002',
        'serial_number' => SERIALNUMBER,
    );
    $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&" . http_build_query($vaules);
    stream_context_set_default(array('http' => array('timeout' => 3)));
    file_get_contents($url);
}

/**
 * 面包屑导航  用于后台管理
 * 根据当前的控制器名称 和 action 方法
 */
function navigate_admin()
{
    $navigate = include APP_PATH . 'admin/conf/navigate.php';
    $location = strtolower('Admin/' . CONTROLLER_NAME);
    $arr = array(
        '后台首页' => 'javascript:void();',
        $navigate[$location]['name'] => 'javascript:void();',
        $navigate[$location]['action'][ACTION_NAME] => 'javascript:void();',
    );
    return $arr;
}

/**
 * 导出excel
 * @param $strTable    表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable, $filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=" . $filename . "_" . date('Y-m-d') . ".xls");
    header('Expires:0');
    header('Pragma:public');
    echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . $strTable . '</html>';
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 根据id获取地区名字
 * @param $regionId id
 */
function getRegionName($regionId)
{
    $data = M('region')->where(array('id' => $regionId))->field('name')->find();
    return $data['name'];
}

function getMenuArr()
{
    $menuArr = include APP_PATH . 'admin/conf/menu.php';
    $act_list = session('act_list');
    if ($act_list != 'all' && !empty($act_list)) {
        $right = M('system_menu')->where("id in ($act_list)")->cache(true)->getField('right', true);
        $role_right = '';
        foreach ($right as $val) {
            $role_right .= $val . ',';
        }
        foreach ($menuArr as $k => $val) {
            foreach ($val['child'] as $j => $v) {
                foreach ($v['child'] as $s => $son) {
                    if (strpos($role_right, $son['op'] . '@' . $son['act']) === false) {
                        unset($menuArr[$k]['child'][$j]['child'][$s]);//过滤菜单
                    }
                }
            }
        }
        foreach ($menuArr as $mk => $mr) {
            foreach ($mr['child'] as $nk => $nrr) {
                if (empty($nrr['child'])) {
                    unset($menuArr[$mk]['child'][$nk]);
                }
            }
        }
    }
    return $menuArr;
}


function respose($res)
{
    exit(json_encode($res));
}

if (!function_exists('json_return')) {
    /**
     * json返回方法
     */
    function json_return($data = '', $code = '', $msg = '')
    {
        $code = $code !== '' ? $code : 200;
        if (empty($code) && !is_numeric($code))
            return FALSE;
        $msg = !empty($msg) ? $msg : '返回成功！';
        if ($code == 400) {
            $msg = !empty($msg) ? $msg : '返回失败！';
        }
        if (empty($data))
            $data = NULL;
        exit(json_encode(array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        )));
    }
}
if (!function_exists('json_return_layui')) {
    /**
     * json返回方法
     */
    function json_return_layui($data = '', $count='',$code = '', $msg = '')
    {
        $code = $code !== '' ? $code: 0;

        $msg = !empty($msg) ? $msg : '返回成功！';
        if ($code == 1) {
            $msg = !empty($msg) ? $msg : '返回失败！';
        }
        if (empty($data))
            $data = NULL;
        exit(json_encode(array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'count'=>$count
        )));
    }
}
if (!function_exists('check_code')) {
    /**
     * 验证短信验证码
     */
    function check_code($mobile, $code)
    {
        $result = db('sms')->where('mobile', $mobile)->order('id desc')->find();
        if (!$result)
            return array('msg' => '验证码不存在', 'status' => 0);
        else if ($result['end_time'] < time())
            return array('msg' => '验证码已过期！', 'status' => 0);
        else if ($code == $result['code'])
            return array('msg' => '验证成功', 'status' => 1);
        else if ($code !== $result['code'])
            return array('msg' => '验证码不存在', 'status' => 0);
    }

}
    if (!function_exists('add_image_pre')) {
        /**
         * 给图片路径添加阿里路径
         */
        function add_image_pre($image)
        {
            if (preg_match_all("/^[http:]|^[https:]/", $image))
                return $image;
            if (!$image)
                return '';

            $image = ltrim(trim($image, ' '), '/');
            $image = config('fzk_img_url') . '/' . $image;

            return $image;
        }
    }


    if (!function_exists('Redis')) {
        /*
         * @瞿亮
         * 调用redis简化步骤
         * */
        function Redis()
        {
            $redis = new \Redis();
            $redis->connect('47.111.167.36', 6379);
            $redis->select(2);
            return $redis;
        }
    }

    if (!function_exists('jgSend2')) {
        /*
         * @瞿亮
         * fid 收起人
         * msg 消息描述
         * code 消息码
         */
        function jgSend2($fid, $msg, $data, $code)
        {
            $jiguang = new \app\admin\controller\Push();
            $oid = empty($data) ? '' : $data['order_id'];
            $jiguang->pushMSG($fid, $msg, '{"msg":"' . $msg . '","code":' . $code . ',"data":"' . $oid . '","time":' . time() . '}');
            return true;
        }
    }
    /**
     *
     * curl请求
     */
    if (!function_exists('_request')) {
        function _request($curl, $https = true, $method = 'get', $data = null)
        {
            $ch = curl_init();//初始化
            curl_setopt($ch, CURLOPT_URL, $curl);//设置访问的URL
            curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
            if ($https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不做服务器认证
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不做客户端认证
            }
            if ($method == 'post') {
                curl_setopt($ch, CURLOPT_POST, true);//设置请求是POST方式
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置POST请求的数据
            }
            $str = curl_exec($ch);//执行访问，返回结果
            curl_close($ch);//关闭curl，释放资源
            return $str;
        }
    }
    if (!function_exists('GetAppointMonth')) {
//获取指定月份的第一天和最后一天
        function GetAppointMonth($date)
        {
            $start_time = strtotime($date);//开始时间
            $end_time = strtotime("$date +1 month -1 day");//结束时间
            return array($start_time, $end_time + 86399);
        }
    }

    if (!function_exists('GetBookMonth')) {
//获取当月的第一天和最后一天
        function GetBookMonth()
        {
            $firstDate = date('Y-m-01', strtotime(date("Y-m-d")));
            $lastData = date('Y-m-d', strtotime("$firstDate +1 month -1 day"));
            return array(strtotime($firstDate), strtotime($lastData) + 86399);
        }
    }


    /* 添加路径前缀  */
    if(!function_exists("aimg")){
        function aimg($img="",$type=1){
            if($img==''){
                return $img;
            }
            if($type>0){
                if(preg_match("/http\:|https\:/",$img)) return $img;
                // return rtrim(config2("alioss.cdnurl"),'/').'/'.ltrim($img,'/');
                return rtrim(config("fzk_img_url"),'/').'/'.ltrim($img,'/');
            }else{
                if(preg_match("/http\:|https\:/",$img)) return $img;
                return rtrim(config("gdw_img_url"),'/').'/'.ltrim($img,'/');
            }

        }
    }
    if(!function_exists('get_store_config')){
        /**
         * 获取店铺相关费用
         *  User: wj
         */
        function get_store_config()
        {
            $storeConfig = Db::name('store_config')->field('withdraw_fee,month_fee,year_fee,win_fee')->find();
            return $storeConfig;
        }
    }

    if(!function_exists('substr_cut')){
        /**
         *
         * 隐藏部分用户名
         */
        function substr_cut($user_name){
            $strlen     = mb_strlen($user_name, 'utf-8');
            $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
            $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
        }
    }
//寻找运营商父级id
function getParentOperatorList($cid){
    $pids = array();
    $parent_id =  Db::name('admin_operator')->where(array('admin_id'=>$cid))->getField('parent_id');
    if($parent_id != 0){
        array_push($pids,$parent_id);
        $npids = getParentOperatorList($parent_id);
        if(!empty($npids)){
            $pids = array_merge($pids,$npids);
        }

    }
    return $pids;
}
/**
 *
 * 营利分红-运营商奖励
 * parms payment_type 1: 账户余额 2：支付宝 3店铺余额
 * type  类型说明:1店铺提现 2店铺开通 3运营商提现 4营利分红 5运营商店铺提现
 */
if(!function_exists('getOperatorReward')){
    function getOperatorReward($user,$money=0,$payment_type,$type,$store_manage_type='mid_year'){
        $flag=1;
        $ids = getParentOperatorList($user['operator_id']);
        $arr[]=$user['operator_id'];
       // dump($arr);die;
        $operator_ids = array_merge($ids, $arr);
        $operator_config = Db::name('operator_config')->find(1);
        foreach ($operator_ids as $v){
            $admin_operator = db::name("admin_operator")
                ->alias('ao')
                ->join('user u', 'ao.user_id=u.id', 'left')
                ->where('ao.admin_id', $v)
                ->field('ao.*,u.user_money')
                ->find();

            switch ($type){
                case 1:
                    $reward_fee = $admin_operator['operator_type'] == 1 ? $operator_config['store_withdrawals_operator_reward'] : $operator_config['store_withdrawals_shareholder_reward'];
                    $reward_amount = $money* ($reward_fee / 1000);

                    $withdraw_data=[
                        'user_id'=>$user['user_id'],
                        'operator_id'=>$v,
                        'level'=>$user['level'],
                        'username'=>$user['username'],
                        'mobile'=>$user['mobile'],
                        'update_money'=>$reward_amount,
                        'type_money'=>$user['money'],
                        'before_money'=>$admin_operator['user_money'],
                        'after_money'=>$admin_operator['user_money']+$reward_amount,
                        'createtime'=>time(),
                        'payment_type'=>$payment_type,
                        'type'=>$type
                    ];
                    break;
                case 2:
                    switch ($store_manage_type) {
                        //半年年费
                        case 'mid_year':
                            $reward_fee = $operator_config['store_mid_year_reward'];
                            break;
                        //季费
                        case 'season':
                            $reward_fee = $operator_config['store_season_reward'];
                            break;
                        //年费
                        case 'year':
                            $reward_fee = $operator_config['store_year_reward'];
                            break;
                        default:
                            return false;
                    }

                    switch($admin_operator['operator_type']){
                        case '1':
                            //运营商奖励金额
                            $reward_amount = $money * ($reward_fee / 100);
                            break;
                        case $admin_operator['operator_type']>1:
                            //如果直推人不是股东，则享受额外奖励
                            if($user['parent']==$admin_operator['user_id']){
                                $reward_amount = $money * ($reward_fee / 100);
                            }else{
                                //股东奖励金额
                                $reward_amount = (1+($operator_config['managefee_extra_reward']/100))*($money * ($reward_fee / 100));
                            }
                            break;
                    }
                    $withdraw_data = [
                        'user_id' => $user['id'],
                        'operator_id' => $v,
                        'level'=>$user['level'],
                        'username' => $user['username'],
                        'mobile' => $user['mobile'],
                        'update_money' => $reward_amount,
                        'type_money' => $money,
                        'before_money'=>$admin_operator['user_money'],
                        'after_money'=>$admin_operator['user_money']+$reward_amount,
                        'createtime' => time(),
                        'payment_type'=>$payment_type,
                        'type' => $type
                    ];
                    break;

                case 4:
                    $reward_ratio = $admin_operator['operator_type'] == 1 ? $operator_config['operator_profit_dividend_ratio'] : $operator_config['shareholder_profit_dividend_ratio'];
                    $reward_amount= $money*($reward_ratio/100);

                    $withdraw_data=[
                        'user_id'=>$user['id'],
                        'operator_id'=>$v,
                        'level'=>$user['level'],
                        'username'=>$user['username'],
                        'mobile'=>$user['mobile'],
                        'update_money'=>$reward_amount,
                        'type_money'=>$money,
                        'before_money'=>$admin_operator['user_money'],
                        'after_money'=>$admin_operator['user_money']+$reward_amount,
                        'createtime'=>time(),
                        'payment_type'=>$payment_type,
                        'type'=>$type
                    ];
                    break;
            }

            $rs=Db::name('user')->where('id', $admin_operator['user_id'])->setInc('user_money', $reward_amount);
            if(!$rs){
                $flag=0;
            }

            Db::name('operator_money_log')->insert($withdraw_data);

        }
        return $flag;
    }
}
    if(!function_exists('assoc_substr')){
        /**
         *
         * 中英混合的字符串截取
         * @param unknown_type $sourcestr
         * @param unknown_type $cutlength
         */
        function assoc_substr($sourcestr, $cutlength) {
            $returnstr = '';
            $i = 0;
            $n = 0;
            $str_length = strlen ( $sourcestr ); //字符串的字节数
            while ( ($n < $cutlength) and ($i <= $str_length) ) {
                $temp_str = substr ( $sourcestr, $i, 1 );
                $ascnum = Ord ( $temp_str ); //得到字符串中第$i位字符的ascii码
                if ($ascnum >= 224) {//如果ASCII位高与224，
                    $returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                    $i = $i + 3; //实际Byte计为3
                    $n ++; //字串长度计1
                } elseif ($ascnum >= 192){ //如果ASCII位高与192，
                    $returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                    $i = $i + 2; //实际Byte计为2
                    $n ++; //字串长度计1
                } elseif ($ascnum >= 65 && $ascnum <= 90) {//如果是大写字母，
                    $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                    $i = $i + 1; //实际的Byte数仍计1个
                    $n ++; //但考虑整体美观，大写字母计成一个高位字符
                }elseif ($ascnum >= 97 && $ascnum <= 122) {
                    $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                    $i = $i + 1; //实际的Byte数仍计1个
                    $n ++; //但考虑整体美观，大写字母计成一个高位字符
                } else {//其他情况下，半角标点符号，
                    $returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
                    $i = $i + 1;
                    $n = $n + 0.5;
                }
            }
            return $returnstr;
        }
    }


    /**
     * @王坏坏
     *处理阿里返回来的路径
     **/
    function imagesUrlReturn($info = array())
    {
        $imagesUrl = [];
        foreach($info as $k=>$v)
        {
            $imagesUrl[] = $v['oss-request-url'];
        }
        return implode(',', $imagesUrl);
    }
    /**
     * @王坏坏
     *处理阿里图片去掉前缀
     */
    function imagesParse($images = NULL)
    {
        $images = explode(',', $images);
        $newImages = [];
        foreach($images as $k=>$v)
        {
            $arr = parse_url($v);
            $newImages[] = $arr['path'];
        }
        return implode(',', $newImages);
    }
    /**
     * @王坏坏
     * 删除本地图片
     * @param type $images
     */
    function delLocalImage($images = array())
    {
        foreach($images as $k=>$v)
        {
            @unlink($v);
        }
    }

    /*杨鹏
     * 获取商品的店铺信息
     * */
    function obtain_store($store_id, $user_id)
    {
        $shop_info['store_id'] = $store_id;
        $shop_info['user_id'] = $user_id;
        if ($store_id > 0) {
            $shop = db::name('store')
                ->field('store_name as shop_name,mobile,logo_image,qq')
                ->where('id', $store_id)
                ->find();
            if (!empty($shop)) {
                $shop_info['shop_name'] = $shop['shop_name'];
                $shop_info['mobile'] = $shop['mobile'];
                $shop_info['logo_image'] = aimg($shop['logo_image']);
            } else {
                $shop_info['shop_name'] = '';
                $shop_info['mobile'] = '';
                $shop_info['logo_image'] = '';
            }
            $shop_info['qq'] = $shop['qq'];
            return $shop_info;
        } else {
            $sql = 'SELECT shop_name,kf_tel,shop_logo FROM shop_seller_shopinfo WHERE ru_id = \'' . $user_id . '\' LIMIT 1';
            $shop = db::query($sql);
            if (empty($shop)) {
                $shop_info['shop_name'] = '凡商优店自营店';
                $shop_info['logo_image'] = aimg('/logo/zy_logo.jpg');
                $shop_info['mobile'] = '';
            } else {
                $shop_info['logo_image'] = aimg('/logo/zy_logo.jpg');
                if ($shop[0]['shop_logo'] != '') {
                    $shop_info['logo_image'] = aimg($shop[0]['shop_logo']);
                }
                $shop_info['shop_name'] = $shop[0]['shop_name'];
                $shop_info['mobile'] = $shop[0]['kf_tel'];
            }
            $shop_info['qq'] = config('kf_qq');
            return $shop_info;
        }
    }


if(!function_exists('get_user_discount')) {
    /**
     * 获取用户优惠券信息
     *user:wj
     */
    function get_user_discount($userId){
        $discount = Db::name('user_discount')->where(['type'=>1,'user_id'=>$userId,'discount_state'=>['in',[1,2]]])->sum('money_balance');
        $discountAll = Db::name('user_discount')->where(['type'=>2,'user_id'=>$userId,'discount_state'=>['in',[1,2]]])->value('money_balance');
        return array('discount'=>$discount,'discountAll'=>$discountAll);
    }
}
if (!function_exists('get_area')) {
    /*
     * @瞿亮
     * 根据地区id获得该地区名称
     * */
    function get_area($id) {
        $area = '';
        $area = db::name('china')->where('id',$id)->value('name');
        return $area;
    }
}
if(!function_exists("getManagerDays")){
    /**
     * 获取天数
     * @param $date       [当天时间]
     * @param $month_num  [月数]
     * @return false|int
     */
    function getManagerDays($date, $month_num){
        $month_num = $month_num+1;
        $firstday = date('Y-m-01', $date);
        $lastday = strtotime("$firstday +".$month_num." month -1 day");
        $day_lastday = date('d', $lastday); //获取下个月份的最后一天
        $day_benlastday = date('d', strtotime("$firstday +1 month -1 day")); //获取本月份的最后一天

        //获取当天日期
        $Same_day = date('d', $date);
        //判断当天是否是最后一天   或 下月最后一天 等于 本月的最后一天
        if($Same_day == $day_benlastday ||$day_lastday == $Same_day){
            $day = $day_lastday;

        }else{
            $day = $Same_day;

        }
        $day = date('Y',$lastday).'-'.date('m',$lastday).'-'.$day;

        return strtotime($day);
    }
}
function Alicurl($bank="", $params = false, $ispost = 0)
{
    if(empty($bank)) error("查询卡号不能为空");
    $url="https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo=" . str_replace(" ","",$bank) . "&cardBinCheck=true";
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    $response = curl_exec($ch);
    if ($response === FALSE) {
        echo "cURL Error: " . curl_error($ch);
        return false;
    }

    curl_close($ch);
    return $response;
}
if(!function_exists('uptoPC')){
    /*
     * @瞿亮
     * 添加提示信息给后台
     * */
    function uptoPC($data){
        $data["time"] = date('Y-m-d H:i:s',time());
        if(db::name("pc_msg")->insertGetId($data)){
            return true;
        }else{
            return false;
        }
    }
}
if(!function_exists('locationEdit')) {

    function locationEdit($goods_id, $type, $num = 1, $pay_type = '-1')
    {
        $list = [
            1 => '设置展位',
            2 => '取消展位',
            5 => '上架商品',
            6 => '下架商品',
            7 => '拒绝提交商品'
        ];
        $goods = db('Goods')->where('goods_id', $goods_id)->find();

        //1=设置展位;2=取消展位;5上架商品;6:下架商品；7拒绝提交商品;8赠送橱窗
        $arr = [1, 5];
        $winGoods = Db::name('goods')->where(['user_id' => $goods['user_id'], 'is_bbm' => \app\admin\model\Goods::WIN_LOCATION_CATE])->find();
        $ret = true;
        if (in_array($type, $arr)) {
            //$ret = Db::name('goods')->where(['user_id'=>$goods['user_id'],'is_bbm'=>\app\common\model\Goods::WIN_LOCATION_CATE])->setDec('goods_number');
            if (($winGoods['goods_number'] - getWinStock($goods['user_id'])) < 0) {
                return ['state' => false, 'code' => 400, 'msg' => '库存不足'];
            }
            //if($type==1 && $ret){
            if ($type == 1) {
                $ret = Db::name('goods')->where('goods_id', $goods_id)->setField('is_recommend', 1);
            }
        } else {
//            $ret = Db::name('goods')->where(['user_id'=>$goods['user_id'],'is_bbm'=>\app\common\model\Goods::WIN_LOCATION_CATE])->setInc('goods_number');
            //if($type==2 && $ret){
            if ($type == 2) {
                $ret = Db::name('goods')->where('goods_id', $goods_id)->setField('is_recommend', 0);
            }
            if ($type == 6 && ($goods['is_recommend'] == 1) && $ret) {
                $ret = Db::name('goods')->where('goods_id', $goods_id)->setField('is_recommend', 0);
                if ($ret) {
//                    $ret = Db::name('goods')->where(['user_id'=>$goods['user_id'],'is_bbm'=>\app\common\model\Goods::WIN_LOCATION_CATE])->setInc('goods_number');
                    $data = [
                        'store_id' => $winGoods['store_id'],
                        'user_id' => $goods['user_id'],
                        'goods_id' => $goods_id,
                        'money' => $winGoods['shop_price'],
                        'type' => 2,
                        'pay_type' => -1,
                        'numbers' => 1,
                        'remark' => '取消展位'
                    ];
                    $ret = Db::name('win_location_log')->insert($data);
//                    if($ret){
//                        $ret = Db::name('win_location_log')->insert($data);
//                    }
                }
            }
        }
        $insertData = [
            'store_id' => $winGoods['store_id'],
            'user_id' => $goods['user_id'],
            'goods_id' => $goods_id,
            'money' => $winGoods['shop_price'],
            'type' => $type,
            'pay_type' => $pay_type,
            'numbers' => $num,
            'remark' => $list[$type]
        ];
        $retLog = Db::name('win_location_log')->insert($insertData);
        if ($retLog && $ret) {
            return ['state' => true, 'code' => 200, 'msg' => 'success'];
        }
        return ['state' => false, 'code' => 400, 'msg' => 'fail'];
    }
}

if(!function_exists('checkUpgrade111')) {
    //升级审核逻辑
    function checkUpgrade111($up_id, $user_id)
    {
        //验证
        $info = db::name('updetail')
            ->alias('a')
            ->join('shop_store m', 'a.store_id=m.id')
            ->join('shop_user u', 'u.id=a.parent', 'left')
            ->where('a.id', $up_id)
            ->where('a.status', '<>', 'finish')
            ->field('a.user_id,a.user_level,a.user_up_level,a.order_id,a.store_id,m.store_name,m.user_id as store_uid,m.funds,a.parent,u.level as p_level')
            ->find();
        //修改状态
        $updetail = db::name('updetail')->where('id', $up_id)->update(array('status' => 'finish', 'checktime' => time()));
        //查询这一级申请的状态
        //判断是否满足升级条件
        $level = db::name('level')->where('up_level', $info['user_up_level'])->find();
        $all_state = db::name('updetail')->where(array('user_id' => $info['user_id'], 'user_level' => $info['user_level']))->select();
        $bool_up = true;
        $bool_user = true;
        $message = "您的审核店家 " . $info['store_name'] . ' 已审核通过！';
        if ($all_state) {
//                $i = 1;
//                if ($level['second_check_level'] > 0) {
//                    if (count($all_state) == 2) {
//                        foreach ($all_state as $key => $value) {
//                            if ($value['status'] == 'uncheck' || $value['status'] == 'reject' || $value['status'] == 'progress') {
//                                $i = 0;
//                            }
//                        }
//                    } else {
//                        $i = 0;
//                    }
//                }
//                //$bool_up = true;
//                //$bool_user = true;
//                if ($i == 1) {
//                    //todo   橱窗位分类1373
//                    $winCate = Goods::WIN_LOCATION_CATE;
//                    $winUrl = Goods::WIN_LOCATION_URL;
//                    //用户升级为1星会员
//                    if ($info['user_up_level'] == 1) {
//                        $parent_win = db::name('goods')->where(['user_id' => $info['parent'], 'is_bbm' => $winCate])->find();
//                        $num = db::name('user')->where('parent',$info['parent'])->where('level','>',0)->count();
//                        $location_num = locationSetInc($num);
//                        if ($location_num>0) {
//                            db::name('goods')->where(['user_id' => $info['parent'], 'is_bbm' => $winCate])->setInc('goods_number', $location_num);
//                            $insertData = [
//                                'store_id'=>$parent_win['store_id'],
//                                'user_id'=>$parent_win['user_id'],
//                                'goods_id'=>$parent_win['goods_id'],
//                                'money'=>$parent_win['shop_price'],
//                                'type'=>8,
//                                'pay_type'=>-1,
//                                'numbers'=>$location_num,
//                                'remark'=>'赠送橱窗位'
//                            ];
//                            Db::name('win_location_log')->insert($insertData);
//                            $message = [
//                                'user_id'=>$parent_win['user_id'],
//                                'message'=>'您已成功推荐'.($num+1).'名一心会员，额外增加'.$location_num.'个橱窗位，请前往查看',
//                                'category'=>2,
//                                'type'=>15, //赠送橱窗
//                                'add_time'=>time()
//                            ];
//                            Db::name('user_message')->insert($message);
//                        }
//
//                        $storeCount = db::name('store')->where(['user_id' => $info['user_id']])->count();
//
//                        if($storeCount>0){
//                            db::name('goods')->where(['user_id' => $info['user_id'], 'is_bbm' => $winCate])->setInc('goods_number', 2);
//                        }
//                    }
//                    if ($info['user_up_level'] > 1) {
//                        //升级累加1橱窗
//                        db::name('goods')->where(['user_id' => $info['user_id'], 'is_bbm' => $winCate])->setInc('goods_number');
//                    }

            //修改升级申请状态以及用户等级

            $bool_up = db::name('updetail')->where(array('user_id' => $info['user_id'], 'user_level' => $info['user_level']))->update(array('state' => 'finish'));
            $bool_user = db::name('user')->where('id', $info['user_id'])->update(array('level' => $info['user_up_level']));
            $message = "您的审核店家 " . $info['store_name'] . ' 已审核通过，恭喜您成功升级为' . $info['user_up_level'] . '心商铺！';
        }


        if ($updetail && $bool_up && $bool_user) {
            //给用户分配优惠券
            $set = db::name('store_config')->order('id desc')->field('general_ticket,special_ticket')->find();
            //判断用户是否存在全网通的卡券
            $all_discount = db::name('user_discount')->where(array('user_id' => $info['user_id'], 'type' => 2))->find();
            if (empty($all_discount)) {
                $all = array(
                    'discount_no' => get_discount_number(1),
                    'user_id' => $info['user_id'],
                    'money' => $set['general_ticket'],
                    'type' => 2,
                    'money_balance' => $set['general_ticket'],
                    'createtime' => time()
                );
                $result_all = db::name('user_discount')->insertGetId($all);

                $before_discount_all = 0;
                $discount_no = $all['discount_no'];
            } else {
                $result_all = db::name('user_discount')->where('id', $all_discount['id'])->update(array(
                    'money' => $all_discount['money'] + $set['general_ticket'],
                    'money_balance' => $all_discount['money_balance'] + $set['general_ticket'],
                    'is_read' => 1
                ));

                $before_discount_all = $all_discount['money_balance'] ?: 0;
                $discount_no = $all_discount['discount_no'];
            }
            //用户通用券增加资金记录
            $user_money = db::name('user')->where('id', $info['user_id'])->value('user_money');
            $order_sn = db::name('order')->where('order_id', $info['order_id'])->value('order_sn');
            $money_balance = Db::name('user_discount')->where(array('type' => 1, 'user_id' => $info['user_id']))->sum('money_balance');
            $user_all_discount = array(
                'user_id' => $info['user_id'],
                'order_sn' => $order_sn,
                'discount_no' => $discount_no,
                'money' => 0,
                'type' => 5,
                'before_money' => $user_money,
                'after_money' => $user_money,
                'before_discount' => $money_balance ? $money_balance : 0,
                'after_discount' => $money_balance ? $money_balance : 0,
                'before_discount_all' => $before_discount_all,
                'after_discount_all' => $before_discount_all + $set['general_ticket'],
                'remark' => '获得通用券额度',
                'createtime' => time()
            );
            $user_all_log = db::name('user_money_log')->insertGetId($user_all_discount);

            //判断用户是否存在指定店铺的卡券
            $store_discount = db::name('user_discount')->where(array('user_id' => $info['user_id'], 'store_id' => $info['store_id'], 'type' => 1))->find();
            if (empty($store_discount)) {
                $assign = array(
                    'discount_no' => get_discount_number(2),
                    'user_id' => $info['user_id'],
                    'store_id' => $info['store_id'],
                    'money' => $set['special_ticket'],
                    'type' => 1,
                    'money_balance' => $set['special_ticket'],
                    'createtime' => time()
                );
                $result_assign = db::name('user_discount')->insertGetId($assign);

                $before_discount = 0;
                $discount_no = $assign['discount_no'];
            } else {
                $result_assign = db::name('user_discount')->where('id', $store_discount['id'])->update(array(
                    'money' => $store_discount['money'] + $set['special_ticket'],
                    'money_balance' => $store_discount['money_balance'] + $set['special_ticket'],
                    'is_read' => 1
                ));

                $before_discount = $store_discount['money_balance'];
                $discount_no = $store_discount['discount_no'];
            }
            //用户指定店铺券增加资金记录
            $user_assign_discount = array(
                'user_id' => $info['user_id'],
                'order_sn' => $order_sn,
                'money' => 0,
                'type' => 6,
                'discount_no' => $discount_no,
                'before_money' => $user_money,
                'after_money' => $user_money,
                'before_discount' => $before_discount,
                'after_discount' => $before_discount + $set['special_ticket'],
                'before_discount_all' => Db::name('user_discount')->where(array('type' => 2, 'user_id' => $user_id))->value('money_balance') ?: 0,
                'after_discount_all' => Db::name('user_discount')->where(array('type' => 2, 'user_id' => $user_id))->value('money_balance') ?: 0,
                'remark' => '获得指定券额度',
                'createtime' => time()
            );
            $user_assign_log = db::name('user_money_log')->insertGetId($user_assign_discount);

            //消息通知
            $m_data = array(
                "user_id" => $info['user_id'],
                "message" => $message,
                "category" => 1,
                "type" => 1,
                "add_time" => time(),
                "is_user_info" => 1
            );

            $m_m_bool = db::name('user_message')->insert($m_data);
            if ($result_all && $user_all_log && $result_assign && $user_assign_log && $m_m_bool) {
                return ['state' => true, 'msg_data' => $m_data, 'data' => $info];
            } else {
                return ['state' => false];
            }
        } else {
            return ['state' => false];
        }

    }
}


if(!function_exists('checkUpgrade')){
    //升级审核逻辑
    function checkUpgrade($up_id){
        //验证
        $info = db::name('updetail')
            ->alias('a')
            ->join('shop_store m', 'a.store_id=m.id')
            ->join('shop_user u', 'u.id=a.parent','left')
            ->where('a.id', $up_id)
            ->where('a.status', '<>', 'finish')
            ->field('a.user_id,a.user_level,a.user_up_level,a.order_id,a.store_id,m.store_name,m.user_id as store_uid,m.funds,a.parent,u.level as p_level')
            ->find();
        if(!$info){
            return ['state'=>false];
        }
        //修改状态
        $updetail = db::name('updetail')->where('id', $up_id)->update(array('status' => 'finish', 'checktime' => time()));
        //查询这一级申请的状态
        //判断是否满足升级条件
        $level = db::name('level')->where('up_level', $info['user_up_level'])->find();
        $all_state = db::name('updetail')->where(array('user_id' => $info['user_id'], 'user_level' => $info['user_level']))->select();
        $bool_up = true;
        $bool_user = true;
        $message = "您的审核店家 " . $info['store_name'] . ' 已审核通过！';
        if ($all_state) {
            $i = 1;
            if ($level['second_check_level'] > 0) {
                if (count($all_state) == 2) {
                    foreach ($all_state as $key => $value) {
                        if ($value['status'] == 'uncheck' || $value['status'] == 'reject' || $value['status'] == 'progress') {
                            $i = 0;
                        }
                    }
                } else {
                    $i = 0;
                }
            }
            $bool_up = true;
            $bool_user = true;
            if ($i == 1) {
                //todo   橱窗位分类1373
                //$winCate = Goods::WIN_LOCATION_CATE;
                //$winUrl = Goods::WIN_LOCATION_URL;
                //用户升级为1星会员
                if($info['user_up_level']==3){
                    $taskRet = db::name('task')->where(['user_id'=>$info['user_id'],'type'=>1,'end_time'=>['egt',time()]])->find();
                    if($taskRet){
                        db::name('task')->where('id',$taskRet['id'])->update(['state'=>2]);
                        $taskList = [
                            [
                                'user_id'=>$info['user_id'],
                                'type'=>2,
                                'start_time'=>time(),
                                'end_time'=>strtotime('+'.Task::TASK_TWO_DAY.' day'),
                                'state'=>'1',
                                'createtime'=>time(),
                                'updatetime'=>time(),
                            ],
                            [
                                'user_id'=>$info['user_id'],
                                'type'=>3,
                                'start_time'=>time(),
                                'end_time'=>strtotime('+'.Task::TASK_THREE_DAY.' day'),
                                'state'=>'1',
                                'createtime'=>time(),
                                'updatetime'=>time(),
                            ]
                        ];
                        db::name('task')->insertAll($taskList);
                    }
                }

                if($info['user_up_level']==4){
                    $user = $user = db('user')->where('id',$info['user_id'])->find();
                    $taskRet2 = db::name('task')->where(['user_id'=>$info['user_id'],'type'=>2,'end_time'=>['egt',time()]])->find();
                    $discount = get_user_discount($info['user_id']);
                    if($taskRet2){
                        $user_money = $user['user_money']+Task::TASK_TWO_PRICE;
                        $user_task = array(
                            'user_id' => $info['user_id'],
                            'money' => Task::TASK_TWO_PRICE,
                            'type' => 9,
                            'before_money' => $user['user_money'],
                            'after_money' => $user_money,
                            'before_discount'     => $discount['discount']? : '0',
                            'after_discount'      => $discount['discount']? : '0',
                            'before_discount_all' => $discount['discountAll']? : '0',
                            'after_discount_all'  => $discount['discountAll']? : '0',
                            'remark'              => '升级任务获取额度',
                            'createtime' => time()
                        );
                        db::name('task')->where('id',$taskRet2['id'])->update(['state'=>2]);
                        db::name('task')->where(['user_id'=>$info['user_id'],'type'=>3])->update(['state'=>3]);
                        db::name('user')->where('id',$info['user_id'])->update(['user_money'=>$user_money]);
                        db::name('user_money_log')->insertGetId($user_task);
                    }else{
                        $taskRet3 = db::name('task')->where(['user_id'=>$info['user_id'],'type'=>3,'end_time'=>['egt',time()]])->find();
                        if($taskRet3){
                            $user_money = $user['user_money']+Task::TASK_THREE_PRICE;
                            $user_task = array(
                                'user_id' => $info['user_id'],
                                'money' => Task::TASK_THREE_PRICE,
                                'type' => 9,
                                'before_money' => $user['user_money'],
                                'after_money' => $user_money,
                                'before_discount'     => $discount['discount']? : '0',
                                'after_discount'      => $discount['discount']? : '0',
                                'before_discount_all' => $discount['discountAll']? : '0',
                                'after_discount_all'  => $discount['discountAll']? : '0',
                                'remark'              => '升级任务获取额度',
                                'createtime' => time()
                            );
                            db::name('task')->where('id',$taskRet3['id'])->update(['state'=>2]);
                            db::name('user')->where('id',$info['user_id'])->update(['user_money'=>$user_money]);
                            db::name('user_money_log')->insertGetId($user_task);
                        }
                    }

                }


                //修改升级申请状态以及用户等级
                $bool_up = db::name('updetail')->where(array('user_id' => $info['user_id'], 'user_level' => $info['user_level']))->update(array('state' => 'finish'));
                $bool_user = db::name('user')->where('id', $info['user_id'])->update(array('level' => $info['user_up_level']));
                $message = "您的审核店家 " . $info['store_name'] . ' 已审核通过，恭喜您成功升级为' . $info['user_up_level'] . '心商铺！';
            }
        }

        if ($updetail && $bool_up && $bool_user) {
            //给用户分配优惠券
            $set = db::name('store_config')->order('id desc')->field('general_ticket,special_ticket')->find();
            //判断用户是否存在全网通的卡券
            $all_discount = db::name('user_discount')->where(array('user_id' => $info['user_id'], 'type' => 2))->find();
            if (empty($all_discount)) {
                $all = array(
                    'discount_no' => get_discount_number(1),
                    'user_id' => $info['user_id'],
                    'money' => $set['general_ticket'],
                    'type' => 2,
                    'money_balance' => $set['general_ticket'],
                    'createtime' => time()
                );
                $result_all = db::name('user_discount')->insertGetId($all);

                $before_discount_all = 0;
                $discount_no = $all['discount_no'];
            } else {
                $result_all = db::name('user_discount')->where('id', $all_discount['id'])->update(array(
                    'money' => $all_discount['money'] + $set['general_ticket'],
                    'money_balance' => $all_discount['money_balance'] + $set['general_ticket'],
                    'is_read' => 1
                ));
                $before_discount_all = $all_discount['money_balance'] ?: 0;
                $discount_no = $all_discount['discount_no'];
            }
            //用户通用券增加资金记录
            $user_money = db::name('user')->where('id', $info['user_id'])->value('user_money');
            $order_sn = db::name('order')->where('order_id', $info['order_id'])->value('order_sn');
            $money_balance = Db::name('user_discount')->where(array('type' => 1, 'user_id' => $info['user_id']))->sum('money_balance');
            $user_all_discount = array(
                'user_id' => $info['user_id'],
                'order_sn' => $order_sn,
                'discount_no' => $discount_no,
                'money' => 0,
                'type' => 5,
                'before_money' => $user_money,
                'after_money' => $user_money,
                'before_discount' => $money_balance ? $money_balance : 0,
                'after_discount' => $money_balance ? $money_balance : 0,
                'before_discount_all' => $before_discount_all,
                'after_discount_all' => $before_discount_all + $set['general_ticket'],
                'remark' => '获得通用券额度',
                'createtime' => time()
            );
            $user_all_log = db::name('user_money_log')->insertGetId($user_all_discount);

            //判断用户是否存在指定店铺的卡券
            $store_discount = db::name('user_discount')->where(array('user_id' => $info['user_id'], 'store_id' => $info['store_id'], 'type' => 1))->find();
            if (empty($store_discount)) {
                $assign = array(
                    'discount_no' => get_discount_number(2),
                    'user_id' => $info['user_id'],
                    'store_id' => $info['store_id'],
                    'money' => $set['special_ticket'],
                    'type' => 1,
                    'money_balance' => $set['special_ticket'],
                    'createtime' => time()
                );
                $result_assign = db::name('user_discount')->insertGetId($assign);

                $before_discount = 0;
                $discount_no = $assign['discount_no'];
            } else {
                $result_assign = db::name('user_discount')->where('id', $store_discount['id'])->update(array(
                    'money' => $store_discount['money'] + $set['special_ticket'],
                    'money_balance' => $store_discount['money_balance'] + $set['special_ticket'],
                    'is_read' => 1
                ));

                $before_discount = $store_discount['money_balance'];
                $discount_no = $store_discount['discount_no'];
            }
            //用户指定店铺券增加资金记录
            $user_assign_discount = array(
                'user_id' => $info['user_id'],
                'order_sn' => $order_sn,
                'money' => 0,
                'type' => 6,
                'discount_no' => $discount_no,
                'before_money' => $user_money,
                'after_money' => $user_money,
                'before_discount' => $before_discount,
                'after_discount' => $before_discount + $set['special_ticket'],
                'before_discount_all' => Db::name('user_discount')->where(array('type' => 2, 'user_id' => $info['user_id']))->value('money_balance') ?: 0,
                'after_discount_all' => Db::name('user_discount')->where(array('type' => 2, 'user_id' => $info['user_id']))->value('money_balance') ?: 0,
                'remark' => '获得指定券额度',
                'createtime' => time()
            );
            $user_assign_log = db::name('user_money_log')->insertGetId($user_assign_discount);

            //消息通知
            $m_data = array(
                "user_id" => $info['user_id'],
                "message" => $message,
                "category" => 1,
                "type" => 1,
                "add_time" => time(),
                "is_user_info" => 1
            );

            $m_m_bool = db::name('user_message')->insert($m_data);
            if ($result_all && $user_all_log && $result_assign && $user_assign_log && $m_m_bool) {
                return ['state'=>true,'msg_data'=>$m_data,'data'=>$info];
            } else {
                return ['state'=>false];
            }
        } else {
            return ['state'=>false];
        }
    }
}

if(!function_exists('get_discount_number')){
    /**
     * 获取抵扣卷卡号1：全网通用;2：指定专属店
     * User: wj
     */
    function get_discount_number($type)
    {
        switch ($type){
            case 1://全网通卡
                $first = '68880100';
                $hasNo = Db::name('user_discount')->where('type',2)->order('id desc')->value('discount_no');
                if ($hasNo){
                    $end = substr($hasNo,-4);
                    $end = '1585'.$end;
                }else{
                    $end = '15850000';
                }
                break;
            case 2://指定专属店卡
                $first = '88880100';
                $hasNo = Db::name('user_discount')->where('type',1)->order('id desc')->value('discount_no');
                if ($hasNo){
                    $end = substr($hasNo,-4);
                    $end = '4086'.$end;
                }else{
                    $end = '40860000';
                }
                break;
        }
        $end = $end + 1;
        $no = $first . $end;
        return $no;
    }
}

/**
 * @王坏坏
 * 获取下级分类id / 城市下级
 */
if(!function_exists('getNextCatId')){
    function getNextCatId($catId = 0, $fieldName = 'cat_id', $type = 1)
    {
        if($type == 1)
            $catIds = db('GoodsCategory')->field('cat_id')->where('parent_id', $catId)->select();
        else
            $catIds = db('China')->field('id')->where('id', $catId)->select();
        $catIds = array_column($catIds, $fieldName);
        $catIds = array_merge($catIds, [(int)$catId]);
        return $catIds;
    }
}

if(!function_exists('squre_point')){
    /*
     *
    * 根据特定经纬度和一定范围获取经纬度范围
    * @param $lat -- 纬度
    * @param $lng -- 经度
    * @param $distince -- 距离范围 单位km
    */
    function squre_point($lat, $lng, $distance = 2)
    {
        $dlng = 2 * asin(sin($distance / (2 * 6378.137)) / cos(deg2rad($lat)) );
        $dlng =rad2deg($dlng);
        $dlat = ($distance / 6378.137) ;
        $dlat = rad2deg($dlat);
        return array(
            'latmax'=>$lat+$dlat,
            'latmin'=>$lat-$dlat,
            'lngmax'=>$lng+$dlng,
            'lngmin'=>$lng-$dlng
        );
    }
}