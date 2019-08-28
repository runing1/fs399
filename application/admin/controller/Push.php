<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;


class Push extends Controller {

    private $app_key;
    private $master_secret;

    public function __construct() {
        $this->app_key = "e1efab43e13d5c4d3a32078c";
        $this->master_secret = "7637df265939cf5368b73069";
    }

    //外部推送接口（测试）
    public function send(Request $request) {
        $receiver = input("post.receiver");
        $message = input("post.message");
        $content = input("post.content");
        $title = input("post.title");
        return $this->pushMSG($receiver, $message, $content, $title);
    }

    public function pushMSG($receiver, $message = "", $content = "", $title = "凡商优店商城", $platform = array('android', 'ios')) {//推送函数
        if (empty($receiver))
            return false;
        if ($receiver != "all") {
            $arr_rec = explode(",", $receiver);
            $receiver = [
                "alias" => $arr_rec
            ];
        }
        $arr = array(
            "platform" => $platform, //平台类型
            "audience" => $receiver, //推送目标,这个地方是接受的
            "notification" => array(
                "android" => array(
                    "alert" => $message, //内容
                    "title" => $title, //标题
                    "builder_id" => 1, //Android 通知栏样式
                    "extras" => array(
                        "data" => $content //这里自定义 JSON 格式的 Key/Value 信息
                    )
                ),
                "ios" => array(
                    "alert" => $message, //内容
                    "sound" => "default", //通知提示声音
                    "badge" => "+1", //IOS 应用角标
                    "extras" => array(
                        "title" => $title,
                        "data" => $content //这里自定义 JSON 格式的 Key/Value 信息
                    )
                )
            ),
            "options" => array(
                "apns_production" => true //APNs是否生产环境，true 表示推送生产环境，false 表示要推送开发环境
            )
        );
        //推送消息
        $result = $this->sendPost($arr);
        return $result;
    }

    private function sendPost($data) {

        //用户名和密码
        $username = $this->app_key;
        $password = $this->master_secret;
        //请url
        $URL = 'https://api.jpush.cn/v3/push';
        //数据转成json格式
        $data = json_encode($data);
        /* echo($data);
          exit; */
        //header头设置
        $headers = array('Accept: application/json', 'Content-Type: application/json');
        //请求极光接口
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        //返回结果

        return json_encode($result);
    }

}
