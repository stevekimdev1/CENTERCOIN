<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

if (!($w == '' || $w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

$mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_password    = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$mb_name        = $mb_id; 
$mb_nick        = $mb_id;
$mb_email       = $mb_id;
$mb_1           = isset($_POST['mb_1'])             ? trim($_POST['mb_1'])           : "";
$mb_2           = isset($_POST['mb_2'])             ? trim($_POST['mb_2'])           : "";
$mb_3           = isset($_POST['mb_3'])             ? trim($_POST['mb_3'])           : "";
$mb_4           = isset($_POST['mb_4'])             ? trim($_POST['mb_4'])           : "";
$mb_5           = isset($_POST['mb_5'])             ? trim($_POST['mb_5'])           : "";
$mb_6           = isset($_POST['mb_6'])             ? trim($_POST['mb_6'])           : "";
$mb_7           = isset($_POST['mb_7'])             ? trim($_POST['mb_7'])           : "";
$mb_8           = isset($_POST['mb_8'])             ? trim($_POST['mb_8'])           : "";
$mb_9           = isset($_POST['mb_9'])             ? trim($_POST['mb_9'])           : "";
$mb_10          = isset($_POST['mb_10'])            ? trim($_POST['mb_10'])          : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_homepage    = clean_xss_tags($mb_homepage);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

// 인증키가 유요한 회원만 회원 가입이 된다.
$data = array(
        'grant_type' => 'password'
    ,   'username' => $mb_1
    ,   'servicekey' => $mb_2
    ,   'secretkey' => $mb_3
);
$result = curl('https://api.cashierest.com/V2/UserV12/Token', $data);
if (!$result->ErrCode != 0) { alert('api 정보가 다르거나 오류가 발생했습니다 (code : '.$result->ErrCode.')'); }

if ($w == '') {
    $sql = " insert into {$g5['member_table']}
                set mb_id = '{$mb_id}',
                     mb_password = '".get_encrypt_string($mb_password)."',
                     mb_name = '{$mb_name}',
                     mb_nick = '{$mb_nick}',
                     mb_nick_date = '".G5_TIME_YMD."',
                     mb_email = '{$mb_email}',
                     mb_today_login = '".G5_TIME_YMDHIS."',
                     mb_datetime = '".G5_TIME_YMDHIS."',
                     mb_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_level = '{$config['cf_register_level']}',
                     mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_open_date = '".G5_TIME_YMD."',
                     mb_1 = '{$mb_1}',
                     mb_2 = '{$mb_2}',
                     mb_3 = '{$mb_3}',
                     mb_4 = '{$mb_4}',
                     mb_5 = '{$mb_5}',
                     mb_6 = '{$mb_6}',
                     mb_7 = '{$mb_7}',
                     mb_8 = '{$mb_8}',
                     mb_9 = '{$mb_9}',
                     mb_10 = '{$mb_10}'
    ";
    sql_query($sql);

    set_session('ss_mb_reg', $mb_id);
}

if(isset($_SESSION['ss_cert_type'])) unset($_SESSION['ss_cert_type']);
if(isset($_SESSION['ss_cert_no'])) unset($_SESSION['ss_cert_no']);
if(isset($_SESSION['ss_cert_hash'])) unset($_SESSION['ss_cert_hash']);
if(isset($_SESSION['ss_cert_birth'])) unset($_SESSION['ss_cert_birth']);
if(isset($_SESSION['ss_cert_adult'])) unset($_SESSION['ss_cert_adult']);


goto_url(G5_HTTP_BBS_URL.'/login.php');
