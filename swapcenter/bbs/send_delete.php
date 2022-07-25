<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$check = $_POST['check'];

if (count($check) > 0) {
    foreach ($check as $c) {
        $sql = " delete from g5_write_send where wr_id = '{$c}' ";
        sql_query($sql);
    }
}

goto_url("/bbs/board.php?bo_table=send");
?>
