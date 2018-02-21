/**
 * Created by HP400 on 1/11/2016.
 */
var login = function(){

    var validate = function(){
        jQuery.validator.addMethod("check_all_space", function(value,element) {
            if(value.trim() != '') return true;
            return false;
        },'SSIDを入力してください');
        $('#frmLogin').validate({
            rules: {
                ssid:{
                    required: true,
                    check_all_space: true
                },
                password:{
                    required: true
                },
                id:{
                    required: true,
                    check_all_space: true
                }
            },
            messages: {
                ssid:{
                    required: 'SSIDを入力してください'
                },
                password:{
                    required: 'パスワードを入力してください'
                },
                id:{
                    required: 'IDを入力してください'
                }
            },
            highlight: function(element, errorClass) {
                $(element).addClass('invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('invalid');
            }
        });
    };
    submit = function() {
        $('.btnLogin').click(function(){
            $('.alert').hide();
            var form = $('#frmLogin'),
                valid;
            valid = form.valid();
            if (valid == false)
                return false;
            form.submit();
        });
    };
    var zen2han = function() {
        $('#form-ssid').on('change', function () {
            utility.zen2han(this);
        });
        $('#form-password').on('change', function () {
            utility.zen2han(this);
        });
    };
    return{
        init: function(){
            zen2han();
            submit();
            validate();
        }
    }
}();
$(function(){
    login.init();

});