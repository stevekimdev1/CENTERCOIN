<?php
include_once('./_common.php');

$coin = $_POST['coin'];
$addr = "";
if (!empty($member['mb_2']) && !empty($member['mb_2']) && $member['mb_level'] == 2) {
    api_login_check($member); // api connect
    $wallet = get_wallet();

    foreach ($wallet as $i) {
        if ($i['en'] == $coin) {
            $addr = $i['addr'];
        }
    }
}

echo $addr;
?>
