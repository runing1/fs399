<?php

namespace app\admin\model;

use think\Db;
use think\Model;

class WinLocationLog extends Model
{

    //后台按月份统计-收入
    static public function getTotalAmount($user_id)
    {
        $results = Db::query('select sum(money) as "month_income",date_format(created_at,\'%Y-%m\') `year`,date_format(created_at,\'%m\') as `month` from shop_win_location_log where type=3 and user_id='.$user_id.' group by date_format(created_at,\'%Y-%m\') order by created_at desc');
        return $results;
    }

    static public function getRecord($user_id,$page,$limit)
    {
        if($page-1<0){
            $page = 1;
        }
        $offset = ($page-1)*$limit;
        $results = Db::query('select l.id,l.`type`,l.money,l.store_id,l.numbers,l.goods_id,l.created_at,
g.goods_name,g.goods_thumb,s.store_name,s.logo_image,date_format(l.created_at,\'%Y-%m\') `year`,date_format(l.created_at,\'%m\') as `month` 
from shop_win_location_log l 
left join shop_goods g on g.goods_id=l.goods_id 
left join shop_store s on s.id=l.store_id where l.user_id='.$user_id.' order by l.created_at desc limit '.$offset.','.$limit);
        return $results;
    }
}
