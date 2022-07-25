<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가kj

$result = g_curl('https://api.cashierest.com/V2/PbV12/TickerAll', array());

$check = array('FIL_KRW', 'CENT_KRW');

$return = array();

if ($result->ErrCode == 0) {
    foreach ($result->Cashierest as $k => $v) {
        if (in_array($k, $check)) {
            $return[] = array('name' => $k, 'price' => $v->last);
            continue;
        }
    }
}

echo json_encode($return);
?>
