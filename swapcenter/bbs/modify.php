<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$password = get_encrypt_string($_POST['mb_password']);

$mb_id = $_POST['mb_id'];
$mb_1 = $_POST['mb_1'];
$mb_2 = $_POST['mb_2'];
$mb_3 = $_POST['mb_3'];

$sql = "
    update g5_member set
            mb_password = '{$password}'
        ,   mb_1 = '{$mb_1}'
        ,   mb_2 = '{$mb_2}'
        ,   mb_3 = '{$mb_3}'
        where mb_id = '{$mb_id}'
";

sql_query($sql);

goto_url('/');
?>
