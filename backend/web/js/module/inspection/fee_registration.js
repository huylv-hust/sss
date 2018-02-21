var fee_registration = function () {
    var click_remove_fee_registration = function () {
        $('.btn_remove').on('click', function () {
            var id = $(this).attr('attr-id');
            $('.modal').modal();
            $(this).closest('.fee_registration').find('#remove_fee_registration').attr('value', id);
        });
    };

    var validate = function () {
        $('#fee_registration_form').validate({
            rules: {
                'NAME': {
                    required: true
                },
                'WEIGHT_TAX' : {
                    required : true,
                    digits: true
                },
                'MANDATORY_INSURANCE' : {
                    required : true,
                    digits: true
                },
                'STAMP_FEE' : {
                    required : true,
                    digits: true
                }
            },
            messages: {
                'NAME': {
                    required: '分類名を入力してください'
                },
                'WEIGHT_TAX': {
                    required : '重量税を入力してください',
                    digits: '数字で入力してください'
                },
                'MANDATORY_INSURANCE': {
                    required : '自賠責を入力してください',
                    digits: '数字で入力してください'
                },
                'STAMP_FEE': {
                    required : '印紙代を入力してください',
                    digits: '数字で入力してください'
                }
            }
        });
    };

    return {
        init: function () {
            click_remove_fee_registration();
            validate();
        }
    }
}();

$(function () {
    fee_registration.init();
});