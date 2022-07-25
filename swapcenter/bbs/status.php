<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$wr_id = $_POST['wr_id'];
$wr_4 = $_POST['wr_4'];

$sql = " 
    update g5_write_send set
            wr_4 = '{$wr_4}'
        where
            wr_id = '{$wr_id}'
";
sql_query($sql);
?>
