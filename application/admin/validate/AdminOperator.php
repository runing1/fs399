<?php
namespace app\admin\validate;
use think\validate;
//验证器
class AdminOperator extends validate
{

    protected $rule=[
        'user_name'    =>'require|unique:admin_operator',
        'id_card'   =>'require',
        'mobile'=>['require', 'regex' => '/^1[3|4|5|8][0-9]\d{4,8}$/'],
        'payment_amount'=> 'require|regex:\d{1,10}(\.\d{1,2})?$'
    ];
    protected $message = [
        'user_name.require'    => '用户名必填',
        'user_name.unique'     => '已存在相同用户名',
        'id_card.require'        => '身份证必填',
        'mobile.require'     => '手机号必填',
        'mobile.regex' => '手机号不正确',
        'payment_amount.regex'=>'金额错误'
//'mobile'=>['require', 'regex' => '/^1[3|4|5|8][0-9]\d{4,8}$/'],
    ];
    protected $scene = [
        'add' =>['user_name','id_card','mobile','payment_amount'],
        'edit'=>['user_name','id_card','mobile'],
      //  'del' =>['admin_id'],
    ];

}