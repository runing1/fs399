<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

/**
 * 公共接口
 */
class Common extends Base
{
    /**
     * 您可能感兴趣 (后续待完善)
     * @ApiInternal
     * @param int $type
     * @param string $userId
     * @param int $limit
     * @param int $longitude
     * @param int $latitude
     * @param bool $isHotel
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function recommend($type = 1, $userId = '', $limit = 4, $longitude = 0, $latitude = 0, $isHotel = false)    //1:推荐商铺，2：推荐产品
    {
        if($type == 1){
            $where['type'] = array('EQ', 'offlin');
            $where['state'] = array('EQ', 'finish');
            $where['b.is_delete'] = 0;
            $data = db('Store')->alias('a')
                ->field('a.id,a.cat_id,a.store_name,a.logo_image,a.store_desc,a.province,a.city,a.district,a.street,a.house_number,a.longitude,a.latitude,a.mobile,b.promotion_method,b.promotion_price')
                ->join('__GOODS__ b','a.id=b.store_id','inner')
                ->where($where)
                ->limit($limit)
                ->distinct(TRUE)
                ->select();
            $data = self::checkPromotionMethod($data);
            foreach($data as $k=>$v)
            {
                $data[$k]['logo_image'] = add_image_pre($v['logo_image']);
            }
        }elseif($type ==2){
            if($longitude && $latitude)
            {
                $arr = squre_point($latitude, $longitude, 100);
                $where['c.latitude'] = array('BETWEEN', array($arr['latmin'],$arr['latmax']));
                $where['c.longitude'] = array('BETWEEN', array($arr['lngmin'], $arr['lngmax']));
            }
            $where['a.store_status'] = array('EQ', 'offlin');
            $where['a.goods_status'] = array('EQ', 'onlin');
            $where['a.is_delete'] = 0;
            if($isHotel){
                $catIds = getNextCatId(1223);
                $where['a.cat_id'] = array('IN', $catIds);
                $where['a.end_time'] = array('EGT', date('Y-m-d'));
                $where['c.state'] = array('EQ', 'finish');
            }
            $data = db('Goods')->alias('a')
                ->field('a.goods_id,a.store_id,a.goods_name,a.goods_brief,a.goods_desc,a.market_price,a.shop_price,a.promotion_method,c.longitude,c.latitude,a.promotion_method,a.promotion_price,b.cat_name,c.store_name,c.logo_image')
                ->join('__GOODS_CATEGORY__ b','a.cat_id=b.cat_id','left')
                ->join('__STORE__ c','a.store_id=c.id')
                ->where($where)
                ->limit($limit)
                ->select();
            foreach($data as $k=>$v){
                $data[$k]['imgs'] = db('goods_gallery')->field('img_id,img_url')->where('goods_id', $v['goods_id'])->select();
                $data[$k]['logo_image'] = add_image_pre($v['logo_image']);
                foreach($data[$k]['imgs'] as $kk=>$vv)
                {
                    $data[$k]['imgs'][$kk]['img_url'] = add_image_pre($vv['img_url']);
                }
            }
        }
        return $data;
    }



    /**
     * 检索该店铺下优惠方式
     * @ApiInternal
     */
    public static function checkPromotionMethod($data, $field = 'id', $multi = true)
    {
        if($multi)
        {
            foreach($data as $k=>$v)
            {
                $promotion = db('Goods')->field('GROUP_CONCAT(promotion_method SEPARATOR ",") promotion_method')->where('store_id', $v[$field])->select();
                $promotion = explode(',', $promotion[0]['promotion_method']);
                $promotion = array_unique($promotion);
                if(in_array(1, $promotion) !== FALSE && in_array(2, $promotion) !== FALSE)
                    $multi ? $data[$k]['promotion_method'] = 3 : $data['promotion_method'] = 3;
                if(in_array(1, $promotion) !== FALSE && in_array(2, $promotion) == FALSE)
                    $multi ? $data[$k]['promotion_method'] = 1 : $data['promotion_method'] = 1;
                if(in_array(2, $promotion) !== FALSE && in_array(1, $promotion) == FALSE)
                    $multi ? $data[$k]['promotion_method'] = 2 : $data['promotion_method'] = 2;
                if(in_array(0, $promotion) !== FALSE && in_array(1, $promotion) == FALSE && in_array(2, $promotion) == FALSE)
                    $multi ? $data[$k]['promotion_method'] = 0 : $data['promotion_method'] = 0;
                if(in_array(3, $promotion) !== FALSE)
                    $multi ? $data[$k]['promotion_method'] = 3 : $data['promotion_method'] = 3;
            }
        }else{
            $promotion = db('Goods')->field('GROUP_CONCAT(promotion_method SEPARATOR ",") promotion_method')->where('store_id', $data[$field])->select();
            $promotion = explode(',', $promotion[0]['promotion_method']);
            $promotion = array_unique($promotion);
            if(in_array(1, $promotion) !== FALSE && in_array(2, $promotion) !== FALSE)
                $data['promotion_method'] = 3;
            if(in_array(1, $promotion) !== FALSE && in_array(2, $promotion) == FALSE)
                $data['promotion_method'] = 1;
            if(in_array(2, $promotion) !== FALSE && in_array(1, $promotion) == FALSE)
                $data['promotion_method'] = 2;
            if(in_array(0, $promotion) !== FALSE && in_array(1, $promotion) == FALSE && in_array(2, $promotion) == FALSE)
                $data['promotion_method'] = 0;
            if(in_array(3, $promotion) !== FALSE)
                $data['promotion_method'] = 3;
        }
        return $data;
    }
}