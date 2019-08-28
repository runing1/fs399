<?php
return array(
    'system' => array('name' => '系统', 'child' => array(
        array('name' => '设置', 'child' => array(
            array('name' => '系統设置', 'act' => 'index', 'op' => 'System'),
        )),
        array('name' => '我的团队', 'child' => array(
            array('name' => '团队信息', 'act' => 'team', 'op' => 'User'),
            array('name' => '团队成员', 'act' => 'team_member', 'op' => 'User'),
            array('name' => '我的消息', 'act' => 'message', 'op' => 'User'),
//            array('name'=>'我的提现','act'=>'withdrawal','op'=>'User'),
//            array('name'=>'提现设置','act'=>'withdrawal_pwd','op'=>'User'),

        )),
        array('name' => '提现', 'child' => array(
            array('name' => '账户提现', 'act' => 'withdrawal', 'op' => 'Withdraw'),
            array('name' => '店铺提现', 'act' => 'storeWithdrawal', 'op' => 'Withdraw'),
            array('name' => '支付设置', 'act' => 'withdrawal_pwd', 'op' => 'Withdraw'),
            array('name' => '提现记录', 'act' => 'withdrawal_list', 'op' => 'Withdraw'),
        )),
        array('name' => '推广扫码', 'child' => array(
            array('name' => '推广', 'act' => 'tuiguang', 'op' => 'User'),

        )),
        array('name' => '我的店铺', 'child' =>array(
//                array('name'=>'发布商品', 'act' => 'publishGoods', 'op'=>'Store'),
                array('name'=>'店铺管理', 'act'=>'storeManage','op'=>'Store'),
                array('name'=>'店铺商品', 'act'=>'shop','op'=>'Store'),
                array('name'=>'店铺资金记录', 'act'=>'storeMoneyLog','op'=>'Store')
        )),

        array('name' => '运营商', 'child' => array(
            array('name' => '运营商列表', 'act' => 'index', 'op' => 'Operator'),

        )),


        array('name' => '权限', 'child' => array(
            //	array('name' => '管理员列表', 'act'=>'index', 'op'=>'Admin'),
            array('name' => '角色管理', 'act' => 'role', 'op' => 'Admin'),
            array('name' => '权限资源列表', 'act' => 'right_list', 'op' => 'System'),
            //	array('name' => '管理员日志', 'act'=>'log', 'op'=>'Admin'),
            //array('name' => '供应商列表', 'act'=>'supplier', 'op'=>'Admin'),
        )),

    )),

);