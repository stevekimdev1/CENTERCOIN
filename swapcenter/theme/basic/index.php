<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.php');

$wallet = !empty($member['mb_2']) && !empty($member['mb_2']) ? get_wallet() : array();
?>
    <script src="/extend/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="/extend/plugins/jquery-validation/additional-methods.min.js"></script>

    <script src="/js/coin.js?v=<?=time()?>"></script>

    <section class="content">
        <div class="container-fluid">
            <script>
            var get_price_now = function () {
                var r = get_ajax('/bbs/price.php', { }, 'json');

                for (var i = 0; i < r.length; i++) {
                    $('#'+r[i]['name']).text(r[i]['price']);
                }

                setTimeout("get_price_now()", 1500);
            };

            $(function () {
                get_price_now();
            });
            </script>

            <div class="row">

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-default elevation-1"><img src="/img/FIL.png" alt="image" /></span>
                        <div class="info-box-content">
                            <span class="info-box-text">FIL</span>
                            <span class="info-box-number">
                                <small>&#x20a9;</small>
                                <span id="FIL_KRW">0</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-default elevation-1"><img src="/img/CENT.png" alt="image" /></span>
                        <div class="info-box-content">
                            <span class="info-box-text">CENT</span>
                            <span class="info-box-number">
                                <small>&#x20a9;</small>
                                <span id="CENT_KRW">0</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
            <?php if (count($wallet) > 0) { ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">My Wallet</h3>
                        </div>
                        <div class="card-body">
                        <?php
                        for ($i = 0; $i < count($wallet); $i++) {
                        ?>
                            <div class="form-group">
                                <label><?=$wallet[$i]['ko']?> (<?=$wallet[$i]['en']?>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-wallet"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control Amount" value="<?=$wallet[$i]['amount']?>" readonly />
                                    <span class="input-group-append">
                                        <button type="button" class="SwapCheck btn btn-info btn-flat" data-coin="<?=$wallet[$i]['en']?>">선택</button>
                                    </span>
                                </div>
                                <div class="text-right">
                                    <?=round($wallet[$i]['price'] * $wallet[$i]['amount'], 1)?> won
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

                <style>
                #SwapSelect table { width:100%; }
                #SwapSelect {}
                #SwapSelect .SwapSubTitle { padding-bottom:10px; font-size:24px; }
                #SwapSelect input[type=number] { width:100%; height:60px; border:1px solid #ced4da; padding-left:5px; padding-right:5px; font-size:18px; }
                </style>
                <script>
                $(function () {
                    $('#Change').on('click', function () {
                        var before = $('#Before').html();
                        var before_img = $('#BeforeImg').html();
                        var after = $('#After').html();
                        var after_img = $('#AfterImg').html();
                        var before_placeholder = $('#Before input').attr('placeholder');
                        var after_placeholder = $('#after input').attr('placeholder');

                        $('#Before').html(after);
                        $('#Before input').attr('name', 'before').attr('placeholder', before_placeholder).attr('readonly', false);

                        $('#After').html(before);
                        $('#After input').attr('name', 'after').attr('placeholder', after_placeholder).attr('readonly', true);
                        
                        $('#BeforeImg').html(after_img);
                        $('#AfterImg').html(before_img);

                        var o = $('input[name=orderby]').val();
                        $('input[name=orderby]').val(o == 'FIL' ? 'CENT' : 'FIL');

                        switch (o) {
                              case 'CENT' : 
                                $('#SendAddr').val('f1wrkfhzukg4efdxymio37a4ta25jyajpvlpu75ma');
                                break;
                              case 'FIL' : 
                                $('#SendAddr').val('0x1Ecbc33BA52978345A69d46a3c998848c262B93E');
                                break;
                        }

                        return false;
                    });

                    $(document).on('keyup', 'input[name=before]', function () {
                        var val = parseFloat($(this).val());
                        if (!val || val < 0) { return false; }

                        var fil = $('#FIL_KRW').text();
                        var cent = $('#CENT_KRW').text();

                        var coin = $(this).data('coin');

                        var calc = 0;
                        switch (coin) {
                              case 'CENT' : 
                                calc = (cent * val) / fil; 
                                $('#SendAddr').val('f1wrkfhzukg4efdxymio37a4ta25jyajpvlpu75ma');
                                break;
                              case 'FIL' : 
                                calc = (fil * val) / cent; 
                                $('#SendAddr').val('0x1Ecbc33BA52978345A69d46a3c998848c262B93E');
                                break;
                        }

                        calc = Number(calc).toFixed(8);

                        $('input[name=after]').val(calc);
                        $('input[name=names]').val(coin);
                    });
                });
                </script>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Swap</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" id="SwapForm" action="/bbs/change.php">
                            <input type="hidden" name="names" value="" />
                            <input type="hidden" name="orderby" value="FIL" />
                            <div class="form-group">
                                <div id="SwapSelect" class="form-group">
                                    <table>
                                    <tbody>
                                    <tr>
                                        <td width="244px">
                                            <div id="BeforeImg" class="SwapSubTitle"><img src="/img/FIL.png" alt="img" style="width:30px" /> FIL</div>
                                            <div id="Before">
                                                <input type="number" name="before" value="" placeholder="Amount (Base)" data-coin="FIL" />
                                            </div>
                                        </td>
                                        <td width="80px" align="center">
                                            <a href="" id="Change"><i class="fas fa-arrows-alt-h"></i></a>
                                        </td>
                                        <td width="244px">
                                            <div id="AfterImg" class="SwapSubTitle"><img src="/img/CENT.png" alt="img" style="width:30px" /> CENT</div>
                                            <div id="After">
                                                <input type="number" name="after" value="" placeholder="Converts to (Quote)" readonly  data-coin="CENT" />
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deposit Wallet Address</label>
                                <div class="input-group">
                                    <input type="text" id="SendAddr" class="form-control" value="f1wrkfhzukg4efdxymio37a4ta25jyajpvlpu75ma" readonly />
                                    <span class="input-group-append">
                                        <button type="button" class="CopyCheck btn btn-info btn-flat">Copy</button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>My Wallet Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="my_addr" value="" placeholder="캐셔레스트 지갑 주소를 사용해서 이용해 주세요" />
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
<?php
$check = sql_fetch(" select wr_id from g5_write_send where mb_id = '{$member['mb_id']}' and date_format(wr_datetime, '%Y-%m-%d') = '".date('Y-m-d')."' ");

?>
                            <button type="submit" id="SubmitCheck" class="btn btn-danger" <?=!empty($check['wr_id']) ? 'disabled' : ''?>>신청하기</button>
                        </div>
                    </div>
                    </form>
                </div>

                <!--
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Swap</h3>
                        </div>
                        <form method="post" id="SwapForm" action="/bbs/change.php">
                        <input type="hidden" name="names" value="" />
                        <div class="card-body">
                            <div class="form-group">
                                <label>코인</label>
                                <select class="form-control" name="coin">
                                    <option value="">선택</option>
                                    <option value="CENT">센터코인(CENT)</option>
                                    <option value="FIL">파일코인(FIL)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>수량</label>
                                <input type="number" class="form-control" name="qty" value="" />
                            </div>
                            <?php /*
                            <div class="form-group">
                                <label>예상 변경 수량</label>
                                <input type="text" class="form-control" name="change" value="" readonly />
                            </div>
                            */?>
                            <div class="form-group">
                                <label>입금 주소</label>
                                <div class="input-group">
                                    <input type="text" id="SendAddr" class="form-control" value="" readonly />
                                    <span class="input-group-append">
                                        <button type="button" class="CopyCheck btn btn-info btn-flat">Copy</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>스왑코인</label>
                                <select class="form-control" name="change_coin">
                                    <option value="">선택</option>
                                    <option value="CENT">센터코인(CENT)</option>
                                    <option value="FIL">파일코인(FIL)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>내 지갑 주소</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="my_addr" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="SubmitCheck" class="btn btn-danger">신청하기</button>
                        </div>
                        </form>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </section>
    <!-- 내용 종료 -->

<?php
include_once(G5_THEME_PATH.'/tail.php');
