<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$coin = $_POST['coin'];
$qty = $_POST['qty'];

$before = $coin;
switch ($coin) {
    case 'FIL' :
        $after = 'CENT';
        break;
    case 'CENT' :
        $after = 'FIL';
        break;
}

$wallet = get_wallet();

$before_price = 0;
$after_price = 0;

$before_qty = 0;
$after_qty = 0;

foreach ($wallet as $li) {
    if ($before == $li['en']) {
        $before_price = $li['price'];
        $before_qty = $li['qty'];
    } else if ($after == $li['en']) {
        $after_price = $li['price'];
        $after_qty = $li['qty'];
    } else {
        continue;
    }
}

switch ($after) {
    case 'FIL' : $cnt = 8; break;
    case 'CENT' : $cnt = 0; break;
}

// $total = number_format(($qty * $before_price) / $after_price, $cnt);
// $total = ($qty * $before_price) / $after_price;
$total = ($qty * $before_price) / $after_price;

$arr = array($qty, $before_price, $after_price, $cnt);

echo json_encode($arr);
?>
