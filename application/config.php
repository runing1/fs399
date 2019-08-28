<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace' => 'app',
    // 应用调试模式
    'app_debug' => true,
    // 应用Trace
    'app_trace' => false,
    // 应用模式状态
    'app_status' => '',
    // 是否支持多模块
    'app_multi_module' => true,
    // 入口自动绑定模块
    'auto_bind_module' => false,
    // 注册的根命名空间
    'root_namespace' => [],
    // 扩展函数文件
    'extra_file_list' => [THINK_PATH . 'helper' . EXT, APP_PATH . 'function.php'],
    // 默认输出类型
    'default_return_type' => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return' => 'html',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler' => 'callback',
    // 默认时区
    'default_timezone' => 'PRC',
    // 是否开启多语言
    'lang_switch_on' => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter' => 'htmlspecialchars',
    // 默认语言
    'default_lang' => 'zh-cn',
    // 应用类库后缀
    'class_suffix' => false,
    // 控制器类后缀
    'controller_suffix' => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module' => 'home',
    // 禁止访问模块
    'deny_module_list' => ['common'],
    // 默认控制器名
    'default_controller' => 'Index',
    // 默认操作名
    'default_action' => 'index',
    // 默认验证器
    'default_validate' => '',
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 操作方法后缀
    'action_suffix' => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo' => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr' => '/',
    // URL伪静态后缀
    'url_html_suffix' => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param' => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type' => 0,
    // 是否开启路由
    'url_route_on' => true,
    // 路由使用完整匹配
    'route_complete_match' => false,
    // 路由配置文件（支持配置多个）
    'route_config_file' => ['route'],
    // 是否强制使用路由
    'url_route_must' => false,
    // 域名部署
    'url_domain_deploy' => false,
    // 域名根，如thinkphp.cn
    'url_domain_root' => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => false,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method' => '_method',
    // 表单ajax伪装变量
    'var_ajax' => '_ajax',
    // 表单pjax伪装变量
    'var_pjax' => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache' => false,
    // 请求缓存有效期
    'request_cache_expire' => null,
    // 全局请求缓存排除规则
    'request_cache_except' => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template' => [
        // 模板引擎类型 支持 php think 支持扩展
        'type' => 'Think',
        // 模板路径
        'view_path' => '',
        // 模板后缀
        'view_suffix' => 'html',
        // 模板文件名分隔符
        'view_depr' => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin' => '{',
        // 模板引擎普通标签结束标记
        'tpl_end' => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end' => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件 
    'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
    // errorpage 错误页面
    'error_tmpl' => THINK_PATH . 'tpl' . DS . 'think_error.tpl',


    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg' => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH,
        // 日志记录级别
        'level' => [],
        // 日志开关  1 开启 0 关闭
        'switch' => 0,
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    /**/
    'cache' => [
        // 驱动方式
        'type' => 'File',
        // 缓存保存目录
        'path' => CACHE_PATH,
        // 缓存前缀
        'prefix' => '0b5b58dfc626961bb2cf07c1be8b9962',
        // 缓存有效期 0表示永久缓存
        'expire' => 1,
    ],

    /*
        'cache'                  => [
            // 驱动方式
            'type'   => 'redis',
            'host'       => '192.168.0.201', // 指定redis的地址
        ],
    */
    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'think',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie' => [
        // cookie 名称前缀
        'prefix' => '',
        // cookie 保存时间
        'expire' => 0,
        // cookie 保存路径
        'path' => '/',
        // cookie 有效域名
        'domain' => '',
        //  cookie 启用安全传输
        'secure' => false,
        // httponly设置
        'httponly' => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate' => [
        'type' => 'bootstrap',
        'var_page' => 'page',
        'list_rows' => 15,
    ],
    // 密码加密串
    'AUTH_CODE' => "TPSHOP", //安装完毕之后不要改变，否则所有密码都会出错
    'FREIGHT_TYPE' => [0 => '件数', 1 => '重量', 2 => '体积'],
    //短信使用场景
    'SEND_SCENE' => array(
        '1' => array('用户注册', '验证码${code}，用户注册新账号, 请勿告诉他人，感谢您的支持!', 'regis_sms_enable'),
        '2' => array('用户找回密码', '验证码${code}，用于密码找回，如非本人操作，请及时检查账户安全', 'forget_pwd_sms_enable'),
        '3' => array('客户下单', '您有新订单，收货人：${consignee}，联系方式：${phone}，请您及时查收.', 'order_add_sms_enable'),
        '4' => array('客户支付', '客户下的单(订单ID:${orderId})已经支付，请及时发货.', 'order_pay_sms_enable'),
        '5' => array('商家发货', '尊敬的${userName}用户，您的订单已发货，收货人${consignee}，请您及时查收', 'order_shipping_sms_enable'),
        '6' => array('身份验证', '尊敬的用户，您的验证码为${code}, 请勿告诉他人.', 'bind_mobile_sms_enable'),
        '7' => array('购买虚拟商品通知', '尊敬的用户，您购买的虚拟商品${goodsName}兑换码已生成,请注意查收.', 'virtual_goods_sms_enable'),
    ),

    'APP_TOKEN_TIME' => 60 * 60 * 24, //App保持token时间 , 此处为1天


    /*分页每页显示数*/
    'PAGESIZE' => 10,
    'finally_pay_time' => 1 * 24 * 3600,
    'WX_PAY2' => 1,

    /**假设这个访问地址是 www.tpshop.cn/home/goods/goodsInfo/id/1.html
     *就保存名字为 home_goods_goodsinfo_1.html
     *配置成这样, 指定 模块 控制器 方法名 参数名
     */
    'HTML_CACHE_ARR' => [
        ['mca' => 'home_Goods_goodsInfo', 'p' => ['id']],
        ['mca' => 'home_Index_index'],  // 缓存首页静态页面
        ['mca' => 'home_Goods_ajaxComment', 'p' => ['goods_id', 'commentType', 'p']],  // 缓存评论静态页面 http://www.tpshop2.0.com/index.php?m=Home&c=Goods&a=ajaxComment&goods_id=142&commentType=1&p=1
        ['mca' => 'home_Goods_ajax_consult', 'p' => ['goods_id', 'consult_type', 'p']],  // 缓存咨询静态页面 http://www.tpshop2.0.com/index.php?m=Home&c=Goods&a=ajax_consult&goods_id=142&consult_type=0&p=2
    ],


    'erasable_type' => ['.gif', '.jpg', '.jpeg', '.bmp', '.png', '.mp4', '.3gp', '.flv', '.avi', '.wmv'],
    'image_upload_limit_size' => 1024 * 1024 * 5,//上传图片大小限制
    'OPERATOR_TJMURL' => 'https://www.fs399.cn',
    'fzk_img_url' => 'https://fs399.oss-cn-hangzhou.aliyuncs.com',
    'oss_params' => [
        'oss_key_id' => 'LTAIfUff3UKxt0Et',
        'key_secret' => 'wK4BfiJW4Mjw3HzaLAZrlVUXgQROag',
        'endpoint' => 'oss-cn-hangzhou.aliyuncs.com',
        'bucket' => 'fs399'
    ],
    'alipay' => [
        'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',
        // 'app_id' => '2019040263716799',
        'app_id' => '2019062665683209',
        //编码格式
        'charset' => "UTF-8",
        'sign_type' => "RSA2",
        //'rsa_private_key' =>'MIIEpQIBAAKCAQEApWgsrTjXgv3/34IgnGzC4Xxswk9r0vaqZc7m3LjMZbxCtijJlOr7QjHyZuVEdqh+5PaGc4ipOnv9sFqOhf+tOyXrdOxIDwJhMJ8doyNIE8As1f/IW8u0Wi4shQJ3jIhtkHHqv/aNJhsGDp+yGGSCysWiw4y6Rj/OWNLBAgPNFXFlyXFYwVgit4muPmZVN7ixg1RGYVM/6GFo9/TZeot+zwf4ujZu8UTAf0PkjLgFo66HkTH2WQLpl+bzyFg/7jdw4KriOllVpKsNRMWKl1boHN0xdAz5w1WxCb9g2n8xGmYmFXGx9pTacpLd/KlkemSGjmuaXe4tLj1ugcdn5qNlSwIDAQABAoIBAQCE3oMM/x03r5vsSlax8Tg288FV8YCW7eiBccvHwHcIvksEkw0jIAOH8CFdgIwXmVCd0l68zinKvjFSidv1TiY03kgfG3LjA7FY4mhnVP8gRn59xUOIYcFVs8O1cP7hl/ITVz8QxxgpZQnfk2734z2hb1NJLHGx3q1+coXyfWUv9CX86J+lu+NXZl4JHsIZU8G7YD0X7lFcmzr1iF3mpQjDm2CB1metFeMZPUAVdvsgz/U51gYCR/SvcxGjE0VMGLONCbJ1NFvh6gD734Lmn4ADDp95ozLIx5PDG9R0VOdib10zqtNeOrlBrY6ATPn6thzyUXC1cCVgL+QW4mZ8LpLRAoGBANGO2owKLBUzKT3Om5rh8ibODkcAgtrixp4E2LkpDmU1IBkPHcQoe+I0la10rAVluLxhMz1P7XO85wJHgGZ0eeVKfL3yNSUf88pnba8arU74kiB+ZU1ReaxBgTcMn35AZ8O7PRnj4CWEJC0oFKyHGiVNJepCA3307AcwLQtYfewzAoGBAMoQb4j4fa3A3HO59bB1WGUpflhOuPqgBMsXgYv11y4+z5zjE64oHIf94FvBc1Ypw8F2hLZIHDhDA/HruOLySItKkWcgMxVvttL0IyziUQjXbepBcBSa7bYtXavWpjyd4ubRk4tLIMd8V900KndEdtKEKtXsJoqEpotX2qZ54gqJAoGBAJv+HB+cvt31HKEeuGReB1TtlGE0NzRbFYCxmOaUclvSZRdiUkUf4cAsZrMdI6RM6WyJaowcgGTkXI4szZ7223pcrWjz8K23EFI+Fb8AO7yKerRngl9A5DLe/CIPanaf2N4x9jTclZE3wXo/2MvpMgRMXon4JANSG5TqPs2wTORTAoGATPQrdg+k1YpwqsY0kopuKsC9tGXuvsw4ZxxBowryc1NKgCZe9WGjVAtU6bzn7vWi7sfzTPRzgt3K39R1KkE5bYrti0fRDKXqC7cculhKokJhQui/BannRA1M8keiMc2zf1JrjY4EItTxJXZbgeaaFhxtr+Dq4LAaOVExHVunwXkCgYEAp7ZU2ubyiKiAbWDkO+aCyE9DntIPtHqLI6lxp6yrU2Tz39V3yN9XjmIf+C7DNhO4Mhuyr/sFMJs9TjF7UCTYv3TfPF+Krd9ARirPfhSgT2gXZ9PN5j3gtmoZ/Kh0SnmRIV2B8QdqCWaAuIIx24+lo6ZTaAII8CM9FK/o6r13ZDA=',
        //  'alipay_rsa_public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlR3Shd5UqwPSb7+DFFAKQeh84ou/WiliPgBl0jxZl5xoOobtbH+g2jofGZ/xe/eWFi9Cs57+e6Gx7fgVxLVY4asUJD64zLeXw8eax6Ct7N6Y6TNNnukZayOvUdJs7gwsLAX3SzcHTlmh8G9qyHBdQqOt7hm2h6o5ZK4xLtIhdbLf4c9tHjeQwAsUREmn8CEy5cCAMnX7Fg2F3J5+I0Y98nS7Kpw5SvFqei6hYfwZHCC7LXC9o6tBKb8ATdI3E5XvCHVwCkP1sEp/9kC28VRn/sYqkqO7nSRy6my1CC/XHwxZ+iE7azCRK7gqmF40iDiSZhSaBmqqc6RjMv+u0OMB0QIDAQAB',

        //'rsa_private_key' =>'MIIEpQIBAAKCAQEA30IgQbp9r1Eo3Dka4yhmB5dcPATpDUiOzgGN/mT7vhvZ7OwCorwr0e7XMMdWGrnwUPi+PRaN+9UQWzaBhU6O67/K1f61mH300jmsknz5jIM7d/DV8JcfL450bBaRUovcthHJ9XliRMEDjY2/bmQ3jVqFW1CJc7YhNpn4ITPDnbGRtIyUE+1kRA3oPRoG1c1nZOVGIaTeZ6sI46iNdHHpMMCqmwAtokJvcTU93QfXUvN3/Fv3NWGwdUIWIN8TE7ouM/QgLMZ3vqvVfBoZwyxv/6mRlpHOlXFF6VF2BUCslFphub0KrPQ3/4TxS1LWLhqfq1xw6JPnrzYDIHm1XwWfAwIDAQABAoIBAEhO+HeRWSexF5TMAgNUkEchJMRjqr+conPiWQTt3AMkrh5oCip+24pw2s13olGhq03uYl8SsQXBajzAmI5+dRex1JYemt463VJBT71aMNDrqBUesYvsxjL25oojfQnJNsDZXhndKJCwGuB76mZsmvlzOgHrQblyYat3jr/iLhciUvnKsuaBcqvP6ynIA+4JoZExz7mjLaJlX9aC05zbxKdWyTU8+mhu/5nypdTKApr87L4rLwRgxkC0ewuFwEeZe3N2Wa38tg2dpgorBgWYGti95GEfsyongTOjT80UfY41DjhXSE20fdj2GJGdEiU4fFa55AHrvLdZR0BsaSLbzvECgYEA8cM1B7VL8XqSyZL/zbIQXdC3RAfM591URbHhvfy00zf6i5JaetIT3v95MC/1kGtO1E/RYoW9LQGelWEhqshFa76XCLL8/hJf94LrqvgrSDAEYMnl+WjIjQOMiT63SRyntaDPqRV+/Javju3MmEWPJxiGa82ubA+dj/9cXabH++cCgYEA7GfzXSx6Z+SibQOmnO3VHYRpbqEte2fuACaHLUFgZLDHRb+Oqrcs2egSK1ok4cB/9DnktUFWkdvDAij4xb+s+UyNfU/iLNGE6xLPP2MWkekoIcikWgM142K2MYpyZKpMFDZirPV/XlHYt/m9GMIKHQrioGaRdeTAYzBQrZ/qQIUCgYEAzqEF2QG117zoakouQdMXs+coS4fTNLTxSuP+yY+LyR8GD3ZVyLV2cFivbmT/H6DnbgSONAOULTw+GvQ6fynOU4lAsxmvq0NvLC+EVsrmyzrm8wZ8fQ92oTdQTRkCNAW+vTilY6FQuHfdsm5COD7zW/nVIZxYoFk0VJdvjUd3ky8CgYEAmQ+0wd7KYfnmMC7s8S4LjdDtcnNovz3LGs3XN7Xcc1UBsGcWN/HhWDSpjugOeRvpvHa/XZuwMT/YQs3ERESV+dNWyIuwJNSC+rBy/tdpBY+wLC98OctaWJ7Q18KLziF0oKGVweYxUSAs8PEBPcZkRemGUdQW2f4Bkjb2BXREdwECgYEAxjvksa3TnAek2dFlRwWri34zkTFXltQiR8mjVji/RKlLwE27Ou950Jpq5gQNy0OVFXoPPQE4a3MOWBc7249q7fd+sHnCwEAMd83vwv7UinPqrq6ZjCtY1Y2PctYLwo9eM0R7Qp0gBXsrpIwtLuGhOVWZcNgE9ElIhlfkx1PplIY=',
        //'alipay_rsa_public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA30IgQbp9r1Eo3Dka4yhmB5dcPATpDUiOzgGN/mT7vhvZ7OwCorwr0e7XMMdWGrnwUPi+PRaN+9UQWzaBhU6O67/K1f61mH300jmsknz5jIM7d/DV8JcfL450bBaRUovcthHJ9XliRMEDjY2/bmQ3jVqFW1CJc7YhNpn4ITPDnbGRtIyUE+1kRA3oPRoG1c1nZOVGIaTeZ6sI46iNdHHpMMCqmwAtokJvcTU93QfXUvN3/Fv3NWGwdUIWIN8TE7ouM/QgLMZ3vqvVfBoZwyxv/6mRlpHOlXFF6VF2BUCslFphub0KrPQ3/4TxS1LWLhqfq1xw6JPnrzYDIHm1XwWfAwIDAQAB',
        'merchant_private_key' => 'MIIEpQIBAAKCAQEA30IgQbp9r1Eo3Dka4yhmB5dcPATpDUiOzgGN/mT7vhvZ7OwCorwr0e7XMMdWGrnwUPi+PRaN+9UQWzaBhU6O67/K1f61mH300jmsknz5jIM7d/DV8JcfL450bBaRUovcthHJ9XliRMEDjY2/bmQ3jVqFW1CJc7YhNpn4ITPDnbGRtIyUE+1kRA3oPRoG1c1nZOVGIaTeZ6sI46iNdHHpMMCqmwAtokJvcTU93QfXUvN3/Fv3NWGwdUIWIN8TE7ouM/QgLMZ3vqvVfBoZwyxv/6mRlpHOlXFF6VF2BUCslFphub0KrPQ3/4TxS1LWLhqfq1xw6JPnrzYDIHm1XwWfAwIDAQABAoIBAEhO+HeRWSexF5TMAgNUkEchJMRjqr+conPiWQTt3AMkrh5oCip+24pw2s13olGhq03uYl8SsQXBajzAmI5+dRex1JYemt463VJBT71aMNDrqBUesYvsxjL25oojfQnJNsDZXhndKJCwGuB76mZsmvlzOgHrQblyYat3jr/iLhciUvnKsuaBcqvP6ynIA+4JoZExz7mjLaJlX9aC05zbxKdWyTU8+mhu/5nypdTKApr87L4rLwRgxkC0ewuFwEeZe3N2Wa38tg2dpgorBgWYGti95GEfsyongTOjT80UfY41DjhXSE20fdj2GJGdEiU4fFa55AHrvLdZR0BsaSLbzvECgYEA8cM1B7VL8XqSyZL/zbIQXdC3RAfM591URbHhvfy00zf6i5JaetIT3v95MC/1kGtO1E/RYoW9LQGelWEhqshFa76XCLL8/hJf94LrqvgrSDAEYMnl+WjIjQOMiT63SRyntaDPqRV+/Javju3MmEWPJxiGa82ubA+dj/9cXabH++cCgYEA7GfzXSx6Z+SibQOmnO3VHYRpbqEte2fuACaHLUFgZLDHRb+Oqrcs2egSK1ok4cB/9DnktUFWkdvDAij4xb+s+UyNfU/iLNGE6xLPP2MWkekoIcikWgM142K2MYpyZKpMFDZirPV/XlHYt/m9GMIKHQrioGaRdeTAYzBQrZ/qQIUCgYEAzqEF2QG117zoakouQdMXs+coS4fTNLTxSuP+yY+LyR8GD3ZVyLV2cFivbmT/H6DnbgSONAOULTw+GvQ6fynOU4lAsxmvq0NvLC+EVsrmyzrm8wZ8fQ92oTdQTRkCNAW+vTilY6FQuHfdsm5COD7zW/nVIZxYoFk0VJdvjUd3ky8CgYEAmQ+0wd7KYfnmMC7s8S4LjdDtcnNovz3LGs3XN7Xcc1UBsGcWN/HhWDSpjugOeRvpvHa/XZuwMT/YQs3ERESV+dNWyIuwJNSC+rBy/tdpBY+wLC98OctaWJ7Q18KLziF0oKGVweYxUSAs8PEBPcZkRemGUdQW2f4Bkjb2BXREdwECgYEAxjvksa3TnAek2dFlRwWri34zkTFXltQiR8mjVji/RKlLwE27Ou950Jpq5gQNy0OVFXoPPQE4a3MOWBc7249q7fd+sHnCwEAMd83vwv7UinPqrq6ZjCtY1Y2PctYLwo9eM0R7Qp0gBXsrpIwtLuGhOVWZcNgE9ElIhlfkx1PplIY=',
        'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA30IgQbp9r1Eo3Dka4yhmB5dcPATpDUiOzgGN/mT7vhvZ7OwCorwr0e7XMMdWGrnwUPi+PRaN+9UQWzaBhU6O67/K1f61mH300jmsknz5jIM7d/DV8JcfL450bBaRUovcthHJ9XliRMEDjY2/bmQ3jVqFW1CJc7YhNpn4ITPDnbGRtIyUE+1kRA3oPRoG1c1nZOVGIaTeZ6sI46iNdHHpMMCqmwAtokJvcTU93QfXUvN3/Fv3NWGwdUIWIN8TE7ouM/QgLMZ3vqvVfBoZwyxv/6mRlpHOlXFF6VF2BUCslFphub0KrPQ3/4TxS1LWLhqfq1xw6JPnrzYDIHm1XwWfAwIDAQAB',

        'notify_url' => 'http://operator.fs399.cn:777/admin/payment/pay_notify_url_operator',
        'return_url' => 'http://operator.fs399.cn:777/admin/payment/pay_return_url_operator',
//        'pay_back_url_operator_check_alipay' => 'http://118.31.124.117:777//admin/payment/pay_back_url_operator',
//        'alipay_callback_buy_win' => 'https://hou.fanzk.vip:5232/api/payment/alipay_callback_buy_win',
    ],
    'fsurl' => [
        'goods_import_url' => 'http://192.168.1.175/index/index/gdw_goods_copy',
        'seller_shopinfo_url' => ''
    ]

];
