<?php

namespace app\admin\controller;

use think\Db;
use think\Request;

/**
 * 线下
 */
class UnderLine extends Base
{
    /**
     * 发布产品（美食）
     * @ApiMethod   (POST)
     * @ApiParams   (name="goods_img", type="file", required=true, description="商品图片")
     * @ApiParams   (name="store_id", type="integer", required=true, description="所属店铺")
     * @ApiParams   (name="user_id", type="integer", required=true, description="店铺所属用户")
     * @ApiParams   (name="cat_id", type="integer", required=true, description="分类")
     * @ApiParams   (name="goods_name", type="string", required=true, description="商品名称")
     * @ApiParams   (name="goods_brief", type="string", required=true, description="商品介绍")
     * @ApiParams   (name="goods_desc", type="string", required=true, description="商品描述")
     * @ApiParams   (name="shop_price", type="string", required=true, description="商品价格")
     * @ApiParams   (name="market_price", type="string", required=true, description="原价")
     * @ApiParams   (name="location", type="string", required=true, description="地址")
     */
    public function publishProduct()
    {
        if (request()->isPost()) {
//            if(isset($_FILES['goods_img']) && $_FILES['goods_img']['error'][0] == 0){
//                $goodsImg = oss_upload_file('goods_img', FALSE, TRUE);
//                $goodsImg = explode(',', $goodsImg);
//            }
            if (input('goods_img/a') != '')
                $goodsImg = input('goods_img/a', '');
            else
                return json_return('', 400, '商品图片不能为空！');
            $data = input('post.');
            if (!isset($data['store_id']))
                return json_return('', 400, '所属店铺不能为空！');
            if (!isset($data['user_id']))
                return json_return('', 400, '店铺所属用户不能为空！');
            if (!isset($data['cat_id']))
                return json_return('', 400, '分类不能为空！');
            if (!isset($data['goods_name']))
                return json_return('', 400, '商品名称不能为空！');
            if (!isset($data['goods_brief']))
                return json_return('', 400, '商品介绍不能为空！');
            if (!isset($data['goods_desc']))
                return json_return('', 400, '商品描述不能为空！');
            if (!isset($data['shop_price']))
                return json_return('', 400, '商品价格不能为空！');
//            if(!isset($data['market_price']))
//                return json_return('', 400, '原价不能为空！');
//            if(!isset($data['longitude']) || !isset($data['latitude']))
//                return json_return('', 400, '经纬度不能为空！');
            if (!isset($data['location']))
                return json_return('', 400, '地址不能为空！');
            $data['store_status'] = 'offlin';
            $data['add_time'] = time();
            $data['goods_thumb'] = $goodsImg[0];
            $data['goods_img'] = $goodsImg[0];
            $data['original_img'] = $goodsImg[0];
            Db::startTrans();
            try {
                $goodsId = db('Goods')->insertGetId($data);
                $ret = locationEdit($goodsId, 5);
                if (!$ret['state']) {
                    return json_return('', 400, $ret['msg']);
                }

                if ($goodsId !== FALSE) {
                    foreach ($goodsImg as $k => $v) {
                        db('goods_gallery')->insert([
                            'goods_id' => $goodsId,
                            'img_url' => $v,
                            'img_desc' => $k + 1,
                            'thumb_url' => $v,
                            'img_original' => $v
                        ]);
                    }
                    Db::commit();
                    return json_return('', 200, '发布成功！');
                }
            } catch (\Exception $ex) {
                Db::rollback();
                return json_return('', 400, '网络异常！');
            }
        }
        return json_return('', 400, '提交方式错误！');
    }

    /**
     * 编辑产品（美食）
     * @ApiMethod   (POST)
     * @ApiParams   (name="goods_img", type="file", required=true, description="商品图片")
     * @ApiParams   (name="store_id", type="integer", required=true, description="所属店铺")
     * @ApiParams   (name="user_id", type="integer", required=true, description="店铺所属用户")
     * @ApiParams   (name="cat_id", type="integer", required=true, description="分类")
     * @ApiParams   (name="goods_name", type="string", required=true, description="商品名称")
     * @ApiParams   (name="goods_brief", type="string", required=true, description="商品介绍")
     * @ApiParams   (name="goods_desc", type="string", required=true, description="商品描述")
     * @ApiParams   (name="shop_price", type="string", required=true, description="商品价格")
     * @ApiParams   (name="market_price", type="string", required=true, description="原价")
     * @ApiParams   (name="location", type="string", required=true, description="地址")
     * @return bool
     */
    public function editProduct()
    {
        //$uid = $this->uid;
        $uid = input('user_id', 63);
        if (request()->isPost()) {
            if (input('post.goods_img/a')!='') {
                $goodsImg = input('post.goods_img/a');
            }
            if (!$goodsImg)
                return json_return('', 400, '商品图片不能为空！');
            $data = input('post.');
            if (!isset($data['goods_id']))
                return json_return('', 400, '商品id不能为空！');
            if (!isset($data['cat_id']))
                return json_return('', 400, '分类不能为空！');
            if (!isset($data['goods_name']))
                return json_return('', 400, '商品名称不能为空！');
            if (!isset($data['goods_brief']))
                return json_return('', 400, '商品介绍不能为空！');
            if (!isset($data['goods_desc']))
                return json_return('', 400, '商品描述不能为空！');
            if (!isset($data['shop_price']))
                return json_return('', 400, '商品价格不能为空！');
            if (!isset($data['market_price']))
                return json_return('', 400, '原价不能为空！');
//            if(!isset($data['longitude']) || !isset($data['latitude']))
//                return json_return('', 400, '经纬度不能为空！');
            if (!isset($data['location']))
                return json_return('', 400, '地址不能为空！');
            $data['last_update'] = time();
            $data['goods_thumb'] = $goodsImg[0];
            $data['goods_img'] = $goodsImg[0];
            $data['original_img'] = $goodsImg[0];
            $data['checktime'] = 0;
            Db::startTrans();
            try {
                $goods = db('Goods')->where('goods_id', $data['goods_id'])->find();

                if ($goods['goods_status'] != 'onlin') {
                    locationEdit($data['goods_id'], 5);
                }
                if ($goods['is_recommend'] == 1) {
                    locationEdit($data['goods_id'], 2);
                }


                $data['goods_status'] = 'uncheck';
                $result = db('Goods')->where('goods_id', $data['goods_id'])->update($data);

                if ($result !== FALSE) {
                    db('goods_gallery')->where('goods_id', $data['goods_id'])->delete();
                    foreach ($goodsImg as $k => $v) {
                        db('goods_gallery')->insert([
                            'goods_id' => $data['goods_id'],
                            'img_url' => $v,
                            'img_desc' => $k + 1,
                            'thumb_url' => $v,
                            'img_original' => $v
                        ]);
                    }
                    Db::commit();
                    return json_return('', 200, '编辑成功！');
                }
            } catch (\Exception $ex) {
                Db::rollback();
                return json_return('', 400, '网络异常！');
            }
        }
        $goodsId = input('get.goods_id');
        if (!$goodsId)
            return json_return('', 400, '缺少参数');
        $data = db('Goods')->alias('a')
            ->field('a.goods_id,a.cat_id,a.goods_name,a.goods_img,a.goods_brief,a.goods_desc,a.shop_price,a.market_price,a.promotion_method,a.promotion_price,a.promotion_designation_price,a.promotion_universal_price,a.longitude,a.latitude,a.location,b.cat_name')
            ->join('__GOODS_CATEGORY__ b', 'a.cat_id=b.cat_id', 'left')
            ->where('a.goods_id', $goodsId)
            ->find();
        $category = db('GoodsCategory')->field('cat_name,parent_id')
            ->where('cat_id', $data['cat_id'])
            ->find();
        if ($category['parent_id'] !== 0) {
            $catFirst = db('GoodsCategory')->where('cat_id', $category['parent_id'])->value('cat_name');
            $data['cat_name'] = $catFirst . '--' . $category['cat_name'];
        }
        $data['path'] = config('fzk_img_url');
        $data['imgs'] = db('GoodsGallery')->field('img_id,img_original')->where('goods_id', $goodsId)->select();
        return json_return($data);
    }

    /**
     * 酒店发布
     * @ApiMethod   (POST)
     * @ApiParams   (name="goods_img", type="file", required=true, description="商品图片")
     * @ApiParams   (name="store_id", type="integer", required=true, description="所属店铺")
     * @ApiParams   (name="user_id", type="integer", required=true, description="店铺所属用户")
     * @ApiParams   (name="cat_id", type="integer", required=true, description="分类")
     * @ApiParams   (name="goods_name", type="string", required=true, description="商品名称")
     * @ApiParams   (name="goods_brief", type="string", required=true, description="商品介绍")
     * @ApiParams   (name="goods_desc", type="string", required=true, description="商品描述")
     * @ApiParams   (name="shop_price", type="string", required=true, description="商品价格")
     * @ApiParams   (name="market_price", type="string", required=true, description="原价")
     * @ApiParams   (name="location", type="string", required=true, description="地址")
     * @ApiParams   (name="one_day", type="string", required=true, description="入住时间 example:date1,date2,date3")
     * @ApiParams   (name="day_price", type="string", required=true, description="入住价格 example:price1,price2,price3")
     * @return bool
     */
    public function publishHotel()
    {
        if (request()->isPost()) {
//            if (isset($_FILES['goods_img']) && $_FILES['goods_img']['error'][0] == 0) {
//                $goodsImg = oss_upload_file('goods_img', FALSE, TRUE);
//                $goodsImg = explode(',', $goodsImg);
            if(input('goods_img/a')!=''){
                $goodsImg=input('goods_img/a','');
            } else {
                return json_return('', 400, '商品图片不能为空！');
            }
            $data = input('post.');
            if (!isset($data['store_id']))
                return json_return('', 400, '所属店铺不能为空！');
            if (!isset($data['user_id']))
                return json_return('', 400, '店铺所属用户不能为空！');
            if (!isset($data['cat_id']))
                return json_return('', 400, '分类不能为空！');
            if (!isset($data['goods_name']))
                return json_return('', 400, '商品名称不能为空！');
            if (!isset($data['goods_brief']))
                return json_return('', 400, '商品介绍不能为空！');
            if (!isset($data['goods_desc']))
                return json_return('', 400, '商品描述不能为空！');
            if (!isset($data['start_time']) || !isset($data['end_time']))
                return json_return('', 400, '房源起止日期不能为空！');
            if (!isset($data['shop_price']))
                return json_return('', 400, '商品价格不能为空！');
            if (!isset($data['market_price']))
                return json_return('', 400, '原价不能为空！');
//            if(!isset($data['longitude']) || !isset($data['latitude']))
//                return json_return('', 400, '经纬度不能为空！');
            if (!isset($data['location']))
                return json_return('', 400, '地址不能为空！');
            $date = explode(',', $data['one_day']);
            $dayPrice = explode(',', $data['day_price']);
            unset($data['one_day']);
            unset($data['day_price']);
            if ((!$date || !$dayPrice) || (count($date) !== count($dayPrice)))
                return json_return('', 400, '参数不完整！');
            $data['store_status'] = 'offlin';
            $data['add_time'] = time();
            $data['goods_thumb'] = $goodsImg[0];
            $data['goods_img'] = $goodsImg[0];
            $data['original_img'] = $goodsImg[0];
            Db::startTrans();
            try {

                $goodsId = db('Goods')->insertGetId($data);

                locationEdit($goodsId, 5);

                if ($goodsId !== FALSE) {
                    foreach ($goodsImg as $k => $v) {
                        db('goods_gallery')->insert([
                            'goods_id' => $goodsId,
                            'img_url' => $v,
                            'img_desc' => $k + 1,
                            'thumb_url' => $v,
                            'img_original' => $v
                        ]);
                    }
                    foreach ($date as $k => $v) {
                        db('hotel_price')->insert([
                            'goods_id' => $goodsId,
                            'on_day' => $v,
                            'day_price' => $dayPrice[$k],
                            'addtime' => date('Y-m-d H:i:s')
                        ]);
                    }
                    Db::commit();
                    return json_return('', 200, '发布成功！');
                }
            } catch (Exception $ex) {
                Db::rollback();
                return json_return('', 400, $ex);
            }
        }
    }

/**
 * 编辑酒店
 * @ApiMethod   (POST)
 * @ApiParams   (name="goods_img", type="file", required=true, description="商品图片")
 * @ApiParams   (name="store_id", type="integer", required=true, description="所属店铺")
 * @ApiParams   (name="user_id", type="integer", required=true, description="店铺所属用户")
 * @ApiParams   (name="cat_id", type="integer", required=true, description="分类")
 * @ApiParams   (name="goods_name", type="string", required=true, description="商品名称")
 * @ApiParams   (name="goods_brief", type="string", required=true, description="商品介绍")
 * @ApiParams   (name="goods_desc", type="string", required=true, description="商品描述")
 * @ApiParams   (name="shop_price", type="string", required=true, description="商品价格")
 * @ApiParams   (name="market_price", type="string", required=true, description="原价")
 * @ApiParams   (name="location", type="string", required=true, description="地址")
 * @ApiParams   (name="one_day", type="string", required=true, description="入住时间 example:date1,date2,date3")
 * @ApiParams   (name="day_price", type="string", required=true, description="入住价格 example:price1,price2,price3")
 */
public
function editHotel()
{
    if (Request::instance()->isPost()) {

        $goodsImg = input('post.goods_img/a');

        if (!$goodsImg)
            return json_return('', 400, '商品图片不能为空！');
        $data = input('post.');
        if (!isset($data['goods_id']) || $data['goods_id'] == 0 || !is_numeric($data['goods_id']))
            return json_return('', 400, '商品id不能为空！');
        if (!isset($data['cat_id']))
            return json_return('', 400, '分类不能为空！');
        if (!isset($data['goods_name']))
            return json_return('', 400, '商品名称不能为空！');
        if (!isset($data['goods_brief']))
            return json_return('', 400, '商品介绍不能为空！');
        if (!isset($data['goods_desc']))
            return json_return('', 400, '商品描述不能为空！');
        if (!isset($data['start_time']) || !isset($data['end_time']))
            return json_return('', 400, '房源起止日期不能为空！');
        if (!isset($data['shop_price']))
            return json_return('', 400, '商品价格不能为空！');
        if (!isset($data['market_price']))
            return json_return('', 400, '原价不能为空！');
//            if(!isset($data['longitude']) || !isset($data['latitude']))
//                return json_return('', 400, '经纬度不能为空！');
        if (!isset($data['location']))
            return json_return('', 400, '地址不能为空！');
        $date = explode(',', $data['one_day']);
        $dayPrice = explode(',', $data['day_price']);
        unset($data['one_day']);
        unset($data['day_price']);
        if ((!$date || !$dayPrice) || (count($date) !== count($dayPrice)))
            return json_return('', 400, '参数不完整！');
        $data['last_update'] = time();
        $data['goods_thumb'] = $goodsImg[0];
        $data['goods_img'] = $goodsImg[0];
        $data['original_img'] = $goodsImg[0];
        $data['checktime'] = 0;
        Db::startTrans();
        try {
            $goods = db('Goods')->where('goods_id', $data['goods_id'])->find();

            if ($goods['goods_status'] != 'onlin') {
                locationEdit($data['goods_id'], 5);
            }
            if ($goods['is_recommend'] == 1) {
                locationEdit($data['goods_id'], 2);
            }

            $data['goods_status'] = 'uncheck';
            $result = db('Goods')->where('goods_id', $data['goods_id'])->update($data);

            if ($result !== FALSE) {
                db('GoodsGallery')->where('goods_id', $data['goods_id'])->delete();
                db('HotelPrice')->where('goods_id', $data['goods_id'])->delete();
                foreach ($goodsImg as $k => $v) {
                    db('GoodsGallery')->insert([
                        'goods_id' => $data['goods_id'],
                        'img_url' => $v,
                        'img_desc' => $k + 1,
                        'thumb_url' => $v,
                        'img_original' => $v
                    ]);
                }
                foreach ($date as $k => $v) {
                    db('HotelPrice')->insert([
                        'goods_id' => $data['goods_id'],
                        'on_day' => $date[$k],
                        'day_price' => $dayPrice[$k],
                        'addtime' => date('Y-m-d H:i:s')
                    ]);
                }
                Db::commit();
                return json_return('', 200, '编辑成功！');
            }
        } catch (Exception $ex) {
            Db::rollback();
            return json_return('', 400, '网络异常！');
        }
    }
    $goodsId = input('get.goods_id');
    //dump($goodsId);die;
    if (!$goodsId || !is_numeric($goodsId))
        return json_return('', 400, '参数错误');
    $data = db('Goods')->alias('a')
        ->field('a.goods_id,a.goods_status,a.cat_id,a.goods_name,a.goods_img,a.goods_brief,a.goods_desc,a.start_time,a.end_time,a.shop_price,a.market_price,a.promotion_method,a.promotion_price,a.promotion_designation_price,a.promotion_universal_price,a.longitude,a.latitude,a.location,b.cat_name')
        ->join('__GOODS_CATEGORY__ b', 'a.cat_id=b.cat_id', 'left')
        ->where('a.goods_id', $goodsId)
        ->find();
    if ($data['goods_status'] == 'uncheck')
        return json_return('', 400, '该商品未通过审核！');
    $category = db('GoodsCategory')->field('cat_name,parent_id')
        ->where('cat_id', $data['cat_id'])
        ->find();
    if ($category['parent_id'] !== 0) {
        $catFirst = db('GoodsCategory')->where('cat_id', $category['parent_id'])->value('cat_name');
        $data['cat_name'] = $catFirst . '--' . $category['cat_name'];
    }
    $data['path'] = config('fzk_img_url');
    $data['imgs'] = db('GoodsGallery')->field('img_id,img_original')->where('goods_id', $goodsId)->select();
    $data['time_and_price'] = db('HotelPrice')->field('id,on_day,day_price')->where('goods_id', $goodsId)->select();
    return json_return($data);
}


}