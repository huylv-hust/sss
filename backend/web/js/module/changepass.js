
var changepass = function(){
    var validate = function(){
        $('#frm_change_pass').validate({
            rules: {
                pass:{
                    required: true
                },
                passcnf:{
                    equalTo: "#form-pass"
                }
            },
            messages: {
                pass:{
                    required: '必須です'
                },
                passcnf:{
                    equalTo: '一致しません'
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
        $('.changepass').click(function(){
            var form = $('#frm_change_pass'),
                valid;
            valid = form.valid();
            if (valid == false)
                return false;
            if(confirm('パスワードを変更します。よろしいですか？')) {
                form.submit();
            }
            return false;
        });
    };
    var zen2han = function() {
        $('#form-password_confirm').on('change', function () {
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
    changepass.init();

});