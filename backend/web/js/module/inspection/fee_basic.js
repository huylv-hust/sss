var fee_basic = function () {
    var click_remove_fee_basic = function () {
        $('.btn_remove').on('click', function () {
            var id = $(this).attr('attr-id');
            $('.modal').modal();
            $(this).closest('.fee_basic').find('#remove_fee_basic').attr('value', id);
        });
    };

    var validate = function () {
        $('#fee_basic_form').validate({
            rules: {
                'NAME': {
                    required: true
                },
                'VALUE' : {
                    required : true,
                    digits: true
                }
            },
            messages: {
                'NAME': {
                    required: '分類名を入力してください'
                },
                'VALUE': {
                    required : '基本料金を入力してください',
                    digits: '数字で入力してください'
                }
            }
        });
    };

    return {
        init: function () {
            click_remove_fee_basic();
            validate();
        }
    }
}();

$(function () {
    fee_basic.init();
});