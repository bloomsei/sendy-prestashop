$(document).ready(function() {
    $('form#sendynewsletter_form').submit(function() {
        var name = $('#sendynewsletter_name').attr('value');
        if (name != undefined) {
            if ($('#sendynewsletter_name').attr('data-req') == 'true') {
                $('.sn_warning').css('display', 'none');
                $('.sn_success').css('display', 'none');
                $('#sn_name').css('display', 'block');
                return false;
            }
        }
        $.ajax({
            url: '../modules/sendynewsletter/subscribe.php',
            type: 'POST',
            data: {
                email: $('#sendynewsletter_email').attr('value'),
                name: name,
                ip:  $('#sendynewsletter_ip').attr('value'),
            },
            success: function(data){
                $('.sn_warning').css('display', 'none');
                $('.sn_success').css('display', 'none');
                if (data == 'Invalid email address.') {
                    $('#sn_email').css('display', 'block');
                }
                else if (data == 'Already subscribed.') {
                    $('#sn_subscribed').css('display', 'block');
                }
                else if (data == 1) {
                    $('.sn_success').css('display', 'block');
                }
                else {
                    $('#sn_error').css('display', 'block');
                }
            },
            error: function() {
                $('.sn_warning').css('display', 'none');
                $('.sn_success').css('display', 'none');
                $('#sn_error').css('display', 'block');
            }
        });
        return false;
    });
});