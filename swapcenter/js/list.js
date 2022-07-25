var ajax = function (url, data, type) {
    return $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType : type,
        async: !1,
        error: function(request,status,error) {
            alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
};

var get_ajax = function (url, data, type) {
    var result = ajax(url, data, type);

    return type == 'json' ? result['responseJSON'] : result['responseText'];
}

var log = function (txt) {
    console.log('======== start ======');
    console.log(txt);
    console.log('======== end ======');
};

$(function () {
    $('.AddrCopy').on('click', function () {
        var order = $(this).data('order');

        var addr = $('#UserAddr'+order);
        addr.select();
        document.execCommand("Copy");

    });

    $('#CheckAll').on('click', function () {
        $('.ListCheck').prop('checked', $(this).is(':checked'));
    });

    $('.ListCheck').on('click', function () {
        var cnt = $('.ListCheck').length;

        var num = 0;
        for (var i = 0; i < cnt; i++) {
            if ($('.ListCheck').eq(i).is(':checked')) { num++; }
        }

        $('#CheckAll').prop('checked', (cnt == num ? true : false) );
    });

    $('#Delete').on('click', function () {
        var cnt = $('.ListCheck').length;

        var num = 0;
        for (var i = 0; i < cnt; i++) {
            if ($('.ListCheck').eq(i).is(':checked')) { num++; }
        }

        if (num == 0) {
            alert('한개 이상 선택 해주세요.');
            return false;
        }

        $('#ListForm').submit();
    });

    $('.Search').change(function() {
        var stx_coin = $('select[name=stx_coin]').val();

        location.href = "/bbs/board.php?bo_table=send&stx_coin="+stx_coin;
    });

    $('.StatusChange').change(function(){
        var val = $(this).val();
        var idx = $(this).data('idx');
        get_ajax("/bbs/status.php", {wr_4 : val, wr_id : idx}, "html");
    });
});
