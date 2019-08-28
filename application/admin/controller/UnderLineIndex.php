<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;

/**
 * @王坏坏
 */
class UnderLineIndex extends Base
{
    /**
     * 发布分类（一级）
     */
    public function category()
    {
        $data = Redis()->get('app_category');
        if($data){
//            $oldData = db('GoodsCategory')
//                ->where([
//                    'parent_id' => ['eq', 0],
//                    'is_show' => ['eq', 1],
//                    'is_offline' => ['eq', 1]
//                ])->count();
            //if(count($oldData) === count(json_decode($data, TRUE)))
            return json_return(json_decode($data, TRUE));
        }
        $data = db('GoodsCategory')->field('cat_id,cat_name,parent_id,cat_image')
            ->where([
                'parent_id' => ['eq', 0],
                'is_show' => ['eq', 1],
                'is_offline' => ['eq', 1]
            ])->select();
        foreach($data as $k=>$v)
        {
            $data[$k]['cat_image'] = add_image_pre($v['cat_image']);
            $isChild = db('GoodsCategory')->where('parent_id', $v['cat_id'])->value('cat_id');
            $data[$k]['is_child'] = $isChild ? 1 : 0;
        }
        Redis()->set('app_category', json_encode($data), 86400);
        return json_return($data);
    }

    /**
     * 获取子级分类
     * @param integer $cat_id 分类ID
     * @return bool
     */
    public function getChildCat()
    {
        $catId = input('get.cat_id');
        if(!$catId)
            return json_return('', 400, '参数错误！');
        $data = Redis()->get('app_category_child_'.$catId);
        $catInfo = db('GoodsCategory')->field('cat_id,parent_id')->where('cat_id', $catId)->find();
        if($data){
            $oldCount = db('GoodsCategory')->where([
                'is_show' => 1,
                'is_offline' => 1,
                'parent_id' => $catInfo['parent_id'] == 0 ? $catId : $catInfo['parent_id']
            ])->count();
            $oldData = json_decode($data, TRUE);
            if($oldCount === count($oldData))
                return json_return(json_decode($data));
        }
        if($catInfo['parent_id'] == 0)
        {
            $data = db('GoodsCategory')->field('cat_id,cat_name,parent_id,cat_image')
                ->where([
                    'is_show' => 1,
                    'is_offline' => 1,
                    'parent_id' => $catId
                ])->select();
        }else{
            $data = db('GoodsCategory')->field('cat_id,cat_name,parent_id,cat_image')
                ->where([
                    'is_show' => 1,
                    'is_offline' => 1,
                    'parent_id' => $catInfo['parent_id']
                ])->select();
        }
        foreach($data as $k=>$v)
        {
            $data[$k]['cat_image'] = add_image_pre($v['cat_image']);
        }
        Redis()->set('app_category_child_'.$catId, json_encode($data));
        return json_return($data);
    }
    /**
     * 套餐详情
     * @param integer $goods_id 商品ID
     * @param string $longitude 定位(经度)
     * @param string $latitude 定位(维度)
     */
    public function goodsDetail()
    {
        $goodsId = input('get.goods_id');
        $longitude = input('get.longitude');
        $latitude = input('get.latitude');
        if(!$goodsId)
            return json_return('', 400, '参数错误！');
        $data = db('Goods')->alias('a')
            ->field('a.goods_id,a.store_id,a.goods_name,goods_number,a.market_price,a.shop_price,a.goods_brief,a.goods_desc,a.goods_img,a.promotion_method,a.start_time,a.end_time,b.store_name,b.province,b.city,b.district,b.street,house_number,b.longitude,b.latitude,b.mobile,c.cat_name,a.cat_id')
            ->join('__STORE__ b','a.store_id=b.id')
            ->join('__GOODS_CATEGORY__ c','a.cat_id=c.cat_id','left')
            ->where('a.goods_id', $goodsId)
            ->find();
        if(!$data)
            return json_return('', 400, '暂无该商品信息！');
        //$data = Common::checkPromotionMethod($data, 'store_id', FALSE);
        $data['is_hotel'] = in_array($data['cat_id'], getNextCatId(1223)) ? 1 : 0;      //判断是否是酒店分类,0:其他分类，1：酒店
        //$data['is_hotel'] = strpos($data['cat_name'], '酒店') !== FALSE || strpos($data['cat_name'], '住宿') !== FALSE || strpos($data['cat_name'], '民宿') !== FALSE ? 1 : 0;
        $data['hotel_dateprice'] = db('hotelPrice')->where('goods_id',$goodsId)->where('on_day', '>=' , date('Y-m-d'))->select();
        $data['goods_img'] = add_image_pre($data['goods_img']);
        //$data['promotion_method'] = $data['promotion_method'] == 0 ? 0 : 1;
        $data['gallery'] = db('GoodsGallery')->field('img_url')->where('goods_id', $goodsId)->select();
        foreach($data['gallery'] as $k=>$v)
        {
            $data['gallery'][$k]['img_url'] = add_image_pre($v['img_url']);
        }
        $data['recommend'] = Common::recommend(2, '', 4, $longitude, $latitude);
        return json_return($data, 200, '返回成功！');
    }
}