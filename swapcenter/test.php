<?php
/** 텔레그램 알림(push) php 소스 **/
 
// define('BOT_TOKEN', '발급받은token');
define('BOT_TOKEN', '5114329878:AAGb1aCdgSaJJA5-HUDvHqIIaqbFCnEfmN0');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
 
// $_TELEGRAM_CHAT_ID = array('message_id값');
$_TELEGRAM_CHAT_ID = array('5120616378');
 
function telegramExecCurlRequest($handle) {
 
    $response = curl_exec($handle);
 
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    }
 
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
 
    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
        sleep(10);
        return false;
    } 
    else if ($http_code != 200) {
 
        $response = json_decode($response, true);
 
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
 
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        }
 
        return false;
    } 
    else {
 
        $response = json_decode($response, true);
 
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        }
 
        $response = $response['result'];
    }
 
    return $response;
}
 
function telegramApiRequest($method, $parameters) {
 
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }
 
    if (!$parameters) {
        $parameters = array();
    } 
    else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }
 
    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }
 
    $url = API_URL.$method.'?'.http_build_query($parameters);
 
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
 
    return telegramExecCurlRequest($handle);
}
 
// 메시지 발송 부분
foreach($_TELEGRAM_CHAT_ID AS $_TELEGRAM_CHAT_ID_STR) {
 
    $_TELEGRAM_QUERY_STR    = array(
        'chat_id' => $_TELEGRAM_CHAT_ID_STR,
        //'text'    => "새로운 문의가 등록되었습니다 - 연락처 : {$_POST['wr_contact']}"
        'text'    => "새로운 문의가 등록되었습니다"
    );
 
    telegramApiRequest("sendMessage", $_TELEGRAM_QUERY_STR);
}
?>
