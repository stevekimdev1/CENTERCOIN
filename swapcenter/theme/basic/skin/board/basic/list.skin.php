<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$scale = 10;
$block = 10;
$page = !empty($_GET['page']) ? $_GET['page'] : 1;

$stx_coin = $_GET['stx_coin'];

$where = " 1 = 1 ";

if ($member['mb_level'] == 2) {
    $where .= " and mb_id = '{$member['mb_id']}' ";
}

if (!empty($stx_coin)) {
    $where .= " and wr_1 = '{$stx_coin}' ";
}

$total = sql_fetch(" select count(wr_id) as cnt from g5_write_send where {$where} ");
$total = $total['cnt'];

$limit = 10;
$start = ($page - 1) * $limit;

$sql = " select * from g5_write_send where {$where} order by wr_id desc limit {$start}, {$limit} ";
$result = sql_query($sql);
$cnt = sql_num_rows($result);

$url = "/bbs/board.php?bo_table={$bo_table}&stx_coin={$stx_coin}";
?>
<link rel="stylesheet" href="/extend/plugins/select2/css/select2.css">
<link rel="stylesheet" href="/extend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="/extend/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<script src="/extend/plugins/select2/js/select2.full.min.js"></script>

<script src="/js/list.js?v=<?=time()?>"></script>

<script>
$(function () {
    // $('.select2').select2();
});
</script>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <?php if ($member['mb_level'] != 2) { ?>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>분류</label>
                                <select class="form-control Search" name="stx_coin">
                                    <option value="">전체</option>
                                    <option value="FIL" <?=$stx_coin == 'FIL' ? 'selected' : ''?>>파일코인</option>
                                    <option value="CENT" <?=$stx_coin == 'CENT' ? 'selected' : ''?>>센터코인</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" id="Delete" class="btn btn-default">선택삭제</button>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-right">
                                    <?=page_nav($total, $scale, $block, $page, $url)?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div style=" min-width:400px;">
                                    <form method="post" id="ListForm" action="/bbs/send_delete.php">
                                    <table id="BoardList" class="table table-striped table-bordered" style="min-width:400px;">
                                    <thead>
                                    <tr>
                                    <?php if ($member['mb_level'] != 2) { ?>
                                        <th class="text-center">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="CheckAll">
                                                <label for="CheckAll"></label>
                                            </div>
                                        </th>
                                    <?php } ?>
                                        <th class="text-center">번호</th>
                                        <th class="text-center">분류</th>
                                        <th class="text-center">주소</th>
                                        <th class="text-center">신청수량</th>
                                    <?php if ($member['mb_level'] != 2) { ?>
                                        <th class="text-center">상태</th>
                                    <?php } ?>
                                        <th class="text-center">신청일</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    if ($cnt > 0) {
                                        $num = $total - ($limit * ($page - 1));
                                        $orderby = 1;
                                        while ($row = sql_fetch_array($result)) {
                                    ?>
                                            <tr>
                                            <?php if ($member['mb_level'] != 2) { ?>
                                                <td class="text-center">
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" id="RowCheck<?=$num?>" class="ListCheck" name="check[<?=$row['wr_id']?>]" value="<?=$row['wr_id']?>" />
                                                        <label for="RowCheck<?=$num?>"></label>
                                                    </div>
                                                </td>
                                            <?php } ?>
                                                <td class="text-center"><?=$num?></td>
                                                <td>
                                                    <?=$row['wr_1']?>
                                                    &gt;
                                                    <?=$row['wr_5']?>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" id="UserAddr<?=$orderby?>" class="form-control" value="<?=$row['wr_2']?>" readonly />
                                                        <?php if ($member['mb_level'] != 2) { ?>
                                                            <span class="input-group-append">
                                                                <button type="button" data-order="<?=$orderby?>" class="AddrCopy btn btn-info btn-sm btn-flat">복사</button>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?=$row['wr_6']?> &gt; <?=$row['wr_7']?></td>
                                            <?php if ($member['mb_level'] != 2) { ?>
                                                <td class="text-center">
                                                    <select class="StatusChange form-control" data-idx="<?=$row['wr_id']?>">
                                                        <option value="1" <?=$row['wr_4'] == 1 ? 'selected' : ''?>>대기</option>
                                                        <option value="2" <?=$row['wr_4'] == 2 ? 'selected' : ''?>>완료</option>
                                                    </select>
                                                </td>
                                            <?php } ?>
                                                <td class="text-center"><?=$row['wr_datetime']?></td>
                                            </tr>
                                    <?php
                                            $num--;
                                            $orderby++;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                    </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <?=page_nav($total, $scale, $block, $page, $url)?>
                            </ul>
                        </div> <!-- .card-footer end -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- 내용 종료 -->

