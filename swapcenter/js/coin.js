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
    $('.SwapCheck').on('click', function () {
        var coin = $(this).data('coin');
        var receive = $(this).data('receive');

        $('select[name=coin').val(coin);
        $('input[name=names]').val(coin); // 없어도 될듯

        var addr = '';
        var other = '';
        switch (coin) {
            case 'FIL' : 
                addr = 'f1wrkfhzukg4efdxymio37a4ta25jyajpvlpu75ma'; 
                $('select[name=change_coin]').val('CENT');
                other = 'CENT';
                break;
            case 'CENT' : 
                addr = '0x1Ecbc33BA52978345A69d46a3c998848c262B93E'; 
                $('select[name=change_coin]').val('FIL');
                other = 'FIL';
                break;
        }

        $('#SendAddr').val(addr);

        var my = get_ajax('/bbs/my_wallet.php', { coin : other}, 'html');
        if (my) { $('input[name=my_addr]').val(my); }

        var orderby = $('input[name=orderby]').val();
        if (coin != orderby) {
            $('input[name=orderby]').val(orderby == 'FIL' ? 'CENT' : 'FIL');
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
        }
    });

    $('select[name=coin]').change(function () {
        var coin = $(this).val();

        var addr = '';
        var other = '';
        switch (coin) {
            case 'FIL' : 
                addr = 'f1wrkfhzukg4efdxymio37a4ta25jyajpvlpu75ma'; 
                $('select[name=change_coin]').val('CENT');
                other = 'CENT';
                break;
            case 'CENT' : 
                $('select[name=change_coin]').val('FIL');
                addr = '0x1Ecbc33BA52978345A69d46a3c998848c262B93E'; 
                other = 'FIL';
                break;
        }

        var addr = get_ajax('/bbs/my_wallet.php', { coin : other}, 'html');
        if (addr) { $('input[name=my_addr]').val(addr); }

        $('#SendAddr').val(addr);
    });

    $('select[name=change_coin]').change(function () {
        var coin = $(this).val();

        var addr = get_ajax('/bbs/my_wallet.php', { coin : coin}, 'html');
        if (addr) {
            $('input[name=my_addr]').val(addr);
        }
    });

    $('input[name=qty]').on('keyup', function () {
    /* 비율 어떻게 할지 모르니 숨겨두자
        var val = $(this).val();
        if (!val || val <= 0) { return; }

        var coin = $('input[name=names]').val();
        var qty = $('input[name=qty]').val();
        var info = get_ajax('/bbs/swap.php', { coin : coin, qty : qty}, 'json');
        // $('input[name=change]').val(info);

        var total = (info[0] * info[1]) / info[2];
        $('input[name=change]').val(Number(total).toFixed(info[3]));
    */
    });

    $('#SwapForm').submit(function () {
            /*
        var coin = $('input[name=names]').val();

        var limit = 0;
        switch (coin) {
            case 'FIL' : limit = 0.1; break;
            case 'CENT' : limit = 1000; break;
        }

        var qty = $('input[name=qty]').val();
        if (limit > qty) {
            alert('최소 수량은 ['+limit+'] 입니다');
            return false;
        }
        */
    });

    $.validator.setDefaults({
        submitHandler: function () {
            if (confirm('Would you like to apply?')) {
                return true;
            } else {
                return false;
            }
        }
    });

    $('#SwapForm').validate({
        rules: {
            my_addr : {
                required: true,
                email: false
            },
            before : {
                required: true,
                email: false
            }
        },
        messages: {
            my_addr : {
                required: "Please enter your wallet address.",
                email: ""
            },
            before : {
                required: "Please enter the quantity you want to change.",
                email: ""
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    $('.CopyCheck').on('click', function () {
        var addr = $('#SendAddr');
        addr.select();
        document.execCommand("Copy");

        alert('주소 복사 완료');
    });
});
