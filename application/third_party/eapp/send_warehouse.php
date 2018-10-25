<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 18:23
 * Des: 打印发货单时会通知
 */
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

    Log::e('发送access_tocken返回d信息，' . json_encode($ret));
    return $ret->access_token;
}
function send ($Msg, $User) {
    $access_token = getAccessToken(APP_KEY_STORAGE, APP_SECRET_STORAGE);
    $res = Http::post("/topapi/message/corpconversation/asyncsend_v2",
        array("access_token" => $access_token),
        array(
            "agent_id" => APP_AGENT_STORAGE,
            "userid_list" => implode(',', $User),
            "msg" => $Msg
        ));
    Log::e('发送消息，' . json_encode($Msg));
    Log::e('发送消息返回d信息，' . json_encode($res));
    if ($res->errcode === 0) {
        Log::e('task_id，' . $res->task_id);
        $resd = Http::post("/topapi/message/corpconversation/getsendresult",
            array("access_token" => $access_token),
            array(
                "agent_id" => APP_AGENT_STORAGE,
                "task_id" => $res->task_id
            ));
        Log::e('发送消息返回信息，' . json_encode($resd));
        return true;
    } else {
        return $res->errmsg;
    }
}
