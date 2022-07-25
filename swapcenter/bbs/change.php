<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// $coin = $_POST['coin'];
$coin = $_POST['names'];
$before = $_POST['before'];
// $qty = $_POST['qty'];
$qty = $before;
// $change_coin = $_POST['change_coin'];
$change_coin = $coin == 'CENT' ? 'FIL' : 'CENT';
$my_addr = $_POST['my_addr'];

$after = $_POST['after'];
$wr_6 = $before;
$wr_7 = $after;

/*
$target = $_POST['names'];
$qty = $_POST['qty'];


api_login_check($member); // api connect
$wallet = get_wallet();

$other = "";
foreach ($wallet as $li) {
    if ($li['en'] == $target) {
        $addr = $li['addr'];
    }
}

switch ($target) {
    case 'FIL' : $other = 'CENT'; break;
    case 'CENT' : $other = 'FIL'; break;
}

$receive = get_wallet_direct($other);

/*
코인 전송 가능
$data = array(
        'CoinCode' => $target
    ,   'Units' => $qty
    ,   'Address' => $post
);

// 훗날 사용할수도 있으니........
// send_coin($data);
*/


// 단순 신청으로 변경 수정은 없고 입력/삭제만 가능하도록

$wr_num = get_next_num('g5_write_send');
$wr_reply = '';

$wr_1 = $coin; // 코인명 
$wr_2 = $my_addr; // 회원 주소
$wr_3 = $qty; // 신청 수량
$wr_4 = 1; // 처리여부 (1:대기, 2:처리)
$wr_5 = $change_coin;

$sql = " 
    insert into g5_write_send set 
        wr_num = '$wr_num',
         wr_reply = '$wr_reply',
         mb_id = '{$member['mb_id']}',
         wr_datetime = '".G5_TIME_YMDHIS."',
         wr_last = '".G5_TIME_YMDHIS."',
         wr_ip = '{$_SERVER['REMOTE_ADDR']}',
         wr_1 = '$wr_1',
         wr_2 = '$wr_2',
         wr_3 = '$wr_3',
         wr_4 = '$wr_4',
         wr_5 = '$wr_5',
         wr_6 = '$wr_6',
         wr_7 = '$wr_7',
         wr_8 = '$wr_8',
         wr_9 = '$wr_9',
         wr_10 = '$wr_10'
";

$r = sql_query($sql);

/** 텔레그램 알림(push) php 소스 **/
 
// define('BOT_TOKEN', '발급받은token');
define('BOT_TOKEN', '5114329878:AAGb1aCdgSaJJA5-HUDvHqIIaqbFCnEfmN0');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
 
// $_TELEGRAM_CHAT_ID = array('message_id값');
$_TELEGRAM_CHAT_ID = array('2073404363', '552938097');
// $_TELEGRAM_CHAT_ID = array('5120616378');
 
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

/*
$wr_1 = $coin; // 코인명 
$wr_2 = $my_addr; // 회원 주소
$wr_3 = $qty; // 신청 수량
$wr_4 = 1; // 처리여부 (1:대기, 2:처리)
$wr_5 = $change_coin;
$wr_6 = 이전
$wr_7 = 이후
*/
 
// 메시지 발송 부분
foreach($_TELEGRAM_CHAT_ID AS $_TELEGRAM_CHAT_ID_STR) {
 
    $_TELEGRAM_QUERY_STR    = array(
        'chat_id' => $_TELEGRAM_CHAT_ID_STR,
        'text'    => "{$member['mb_id']} 님 신청 하셨습니다.\nSwap : {$wr_1} > {$wr_5}\nWallet : {$wr_2}\n수량 : {$wr_6} > {$wr_7}
        "
    );
 
    telegramApiRequest("sendMessage", $_TELEGRAM_QUERY_STR);
    // dump($_TELEGRAM_QUERY_STR, 1);
}

goto_url('/');
?>
