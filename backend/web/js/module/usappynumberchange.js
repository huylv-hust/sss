var usappynumberchange = function(){
    var ready = function(){

    };

    var validate = function(){
        $.validator.addMethod("mynumber", function (value, element) {
            if (value.match(/^(\d)*$/) || value == '') {
                return true;
            } else {
                return false;
            }
        });
        $.validator.addMethod("newequalold", function (value, element) {
            if (value != $('#form_oldCardNumber').val()) {
                return true;
            } else {
                return false;
            }
        });
        $('#btnCardNumberVerify').click(function(){
            $('#usappynumberchange').validate({
                rules: {
                    oldCardNumber:{
                        required: true,
                        mynumber: true,
                        rangelength: [16,16]
                    },
                    newCardNumber: {
                        required: true,
                        mynumber: true,
                        rangelength: [16,16],
                        newequalold: true
                    }
                },
                messages: {
                    oldCardNumber:{
                        required: '旧Usappyカード番号を入力してください',
                        mynumber: '旧Usappyカード番号は数字で入力してください',
                        rangelength: '旧Usappyカード番号は16文字で入力してください'
                    },
                    newCardNumber: {
                        required: '新Usappyカード番号を入力してください',
                        mynumber: '新Usappyカード番号は数字で入力してください',
                        rangelength: '新Usappyカード番号は16文字で入力してください',
                        newequalold: '新Usappyカード番号と旧Usappyカード番号は異なる内容を入力してください'
                    }
                }
            }).form();
        });
    };

    var back = function() {
        $('#btnBackCardNumberConfirm').click(function(){
            $('#usappy_number_change_confirm').attr('action','usappy-number-change');
            $('#usappy_number_change_confirm').submit();
            });
    };
    var submit = function(){
        $('#btnCardNumberVerify').click(function(){
            var form = $('#usappynumberchange'),
                valid;
            valid = form.valid();
            if (valid == false)
                return false;

            form.submit();
        });
        $('#btnCardNumberConfirm').click(function(){
            $('#usappy_number_change_confirm').submit();
        });
    };
    var zen2han = function() {
        $('#form_oldCardNumber').on('change', function () {
            utility.zen2han(this);
        });
        $('#form_newCardNumber').on('change', function () {
            utility.zen2han(this);
        });
    };

    return{
        init: function(){
            ready();
            validate();
            submit();
            zen2han();
            back();
        }
    };
}();

$(function(){
    usappynumberchange.init();
});