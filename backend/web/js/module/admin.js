
var admin = function(){
    /*SCREEN CREATE*/
    var validate = function(){
        $('#user_form').validate({
            rules: {
                'Tm50ssuser[M50_USER_ID]': {
                    required: true,
                    check_latin: true
                },
                'Tm50ssuser[M50_USER_NAME]': {
                    required: true
                },
                'Tm50ssuser[M50_SS_CD]' : {
                    required: true,
                    digits: true,
                    minlength: 6
                },
                'Tm50ssuser[M50_PASSWORD]': {
                    required: function() {
                        if ($('#user_action').val() == 'create') {
                            return true;
                        }
                        return false;
                    }
                },
                'M50_PASSWORD_CONFIRM': {
                    required: function() {
                        if ($('#tm50ssuser-m50_password').val() != '') {
                            return true;
                        }
                        return false;
                    },
                    equalTo: "#tm50ssuser-m50_password"
                }
            },
            messages: {
                'Tm50ssuser[M50_USER_ID]': {
                    required: 'ログインIDを入力して下さい',
                    check_latin: '20桁の半角英数字以内で入力してください'
                },
                'Tm50ssuser[M50_USER_NAME]': {
                    required: 'ユーザー名を入力して下さい'
                },
                'Tm50ssuser[M50_SS_CD]': {
                    required: 'SSコードを入力してください',
                    digits: 'SSコードが6桁数字で入力してください',
                    minlength: 'SSコードが6桁数字で入力してください'
                },
                'Tm50ssuser[M50_PASSWORD]': {
                    required: 'パスワードを入力してください'
                },
                'M50_PASSWORD_CONFIRM': {
                    required: 'パスワード確認用を入力してください',
                    equalTo: 'パスワードが一致していません'
                }
            }
        });
    };

    var convert_zen2han = function(){
        $('#tm50ssuser-m50_user_id , #tm50ssuser-m50_ss_cd').on('change',function(){
            utility.zen2han(this);
        });
    };

    return {
        init:function(){
            validate();
            convert_zen2han();
        }
    };
}();

$(function(){
    admin.init();
});

