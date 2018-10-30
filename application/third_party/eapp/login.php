<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/util/Http.php");
require_once(__DIR__ . "/util/Log.php");


function getAccessToken($appkey, $appsecret) {
    $ret = Http::get("/gettoken",
    array(
        "appkey" => $appkey,
        "appsecret" => $appsecret,
    ));

    if ($ret->errcode != 0) {
        return false;
    } 

    return $ret->access_token;
}

function getUserId () {
    $app = $_POST['app'];
    if ($app == 'finance') {
        $access_token = getAccessToken(APP_KEY_FINANCE, APP_SECRET_FINANCE);
    } elseif ($app == 'warehouse') {
        $access_token = getAccessToken(APP_KEY_STORAGE, APP_SECRET_STORAGE);
    } else {
        $access_token = false;
    }

    if ($access_token) {
        $code = $_POST['authCode'];
        // 通过authcode和accesstoken获取哦用户信息
        $res = Http::get("/user/getuserinfo",
            array(
                "access_token" => $access_token,
                "code" => $code,
            ));
        if ($res->errcode == 0) {
            $user = Http::get("/user/get",
                array(
                    "access_token" => $access_token,
                    "userid" => $res->userid,
                ));
            if ($res->errcode == 0) {
                return array(
                    'user_id' => $res->userid,
                    'user_name' => $user->name
                );
            } else {
                // Log::e('获取用户信息错误，'.$user->errmsg);
            }
        } else {
            // Log::e('获取用户ID错误，'.$res->errmsg);
        }
    }
    return false;
}
