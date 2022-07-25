<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;

function dump ($txt, $end = 0) {
    echo '<pre>';
    var_dump($txt);
    echo '</pre>';
    if ($end == 1) { exit; }
}

function g_curl ($url, $fields) {
    $ch = curl_init();                                                            // curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);                                 // url 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);              // 요청결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);               // connection timeout : 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                 // 원격 서버의 인증서가 유효한지 검사 여부

    $post_field_string = http_build_query($fields, '', '&');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);      // POST DATA

    curl_setopt($ch, CURLOPT_POST, false);                               // POST 전송 여부
    $response = json_decode(curl_exec($ch));

    curl_close ($ch);
    return $response;
}

function curl ($url, $fields = array(), $token = '') {
    $ch = curl_init();                                                            // curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);                                 // url 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);              // 요청결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);               // connection timeout : 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                 // 원격 서버의 인증서가 유효한지 검사 여부

    if (!empty($token)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token)); //header 지정하기
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);
    }

    $post_field_string = http_build_query($fields, '', '&');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);      // POST DATA

    curl_setopt($ch, CURLOPT_POST, true);                               // POST 전송 여부
    $response = json_decode(curl_exec($ch));

    curl_close ($ch);
    return $response;
}

function api_login_check ($mb) {
    $data = array(
            'grant_type' => 'password'
        ,   'username' => $mb['mb_1'] 
        ,   'servicekey' => $mb['mb_2'] 
        ,   'secretkey' => $mb['mb_3']
    );
    $result = curl('https://api.cashierest.com/V2/UserV12/Token', $data);
    if ($result->ErrCode == 0) {
        set_session('api', $result->ReturnData);
    }

    
}

function get_wallet_direct ($name) {
    $api = get_session('api');
    $info = curl('https://api.cashierest.com/V2/PInfoV12/WalletCheck', array('CoinCode' => $name), $api->access_token);

    $addr = "";

    foreach ($info->ReturnData as $li) {
        $addr = $li->Address;
    }

    return $addr;
}

function get_wallet () {
    $api = get_session('api');
    $info = curl('https://api.cashierest.com/V2/PInfoV12/Account', array(), $api->access_token);

    $result = array();
    if ($info->ErrCode == 0) {
        foreach ($info->ReturnData as $li) {
            if (in_array($li->CoinCode, array('CENT', 'FIL')) == false) { continue; }
            $result[] = array(
                    'ko' => $li->CoinName
                ,   'en' => $li->CoinCode
                ,   'price' => $li->CoinPriceNow
                ,   'amount' => $li->AccountAmount
                ,   'addr' => $li->AccountAddress
            );
        }
    }

    return $result;
}

function page_nav($total, $scale, $p_num, $page, $link, $target = "") {
    $total_page = ceil($total/$scale);
    $page_list = ceil($page/$p_num)-1;

    $add = "";
    if (!empty($target)) {
        $add = "#{$target}";
    }

    // 페이지 리스트의 첫번째가 아닌 경우엔 [1]...[prev] 버튼을 생성한다.
    if ($page_list > 0) {
        $prev_page = ($page_list-1)*$p_num+1;

        // $navigation = "<li class='page-item'><a class='page-link' href='".$link."&page=1'>&lt;</a></li>";
        $navigation = "<li class='page-item'><a class='page-link' href='".$link."&page=".$prev_page."{$add}'>&laquo;</a></li>";
    }

    // 페이지 목록 가운데 부분 출력
    $page_end = ($page_list + 1) * $p_num;
    if ($page_end > $total_page) $page_end = $total_page;

    for ($i = $page_list * $p_num + 1; $i <= $page_end; $i++) {
        if ($i == $page) {
            $navigation .= "<li class='page-item active'><a class='page-link' href='".$link."&page=".$i."{$add}'>".$i."</a></li>";
        } else {
            $navigation .= "<li class='page-item'><a class='page-link' href='".$link."&page=".$i."{$add}'>".$i."</a></li>";
        }
    }

    // 페이지 목록 맨 끝이 $total_page 보다 작을 경우에만, [next]...[$total_page] 버튼을 생성한다.
    if ($page_end<$total_page) {
        $next_page = ($page_list + 1) * $p_num + 1;

        $navigation .= "<li class='page-item'><a class='page-link' href='".$link."&page=".$next_page."{$add}'>&raquo;</a></li>";
        // $navigation .= "<a class='last' href='".$link."&page=".$total_page."'><span>끝</span></a>";
    }

    return $navigation;
}

function send_coin ($data) {
    // $api = get_session('api');
    // $info = curl('https://api.cashierest.com/V2/POrderV12/CoinWithdraw', $data, $api->access_token);
}
?>
