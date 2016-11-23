//GLOBAR var
var url_usappy_number_change = base_url+'/usappy-number-change';
var url_regist_workslip = base_url+'/regist-workslip';
var type_usappy = '1';
var type_receivable = '2';
var type_other = '3';


//Remove value and validate if modal hide
function clearValueModal(form) {
    $('.box-alert').html('');
    $(form).find('input,textarea,select').each(function(index, element) {
        $(element).val('').end();
        $(element).removeClass('invalid');
        $(element).parent().children('.tooltip').css('display','none');
    });
}

// Menu Usappy‚ カード番号変更
function fncCard(){
    $('#modalAuthUsappy').on('hidden.bs.modal', function (e) {
        clearValueModal(this);
    });
    $('.card_url_redirect').attr('value', url_usappy_number_change);
    $('.card_type_redirect').attr('value',type_usappy);

    $.sidr("close", "sidr", function(){
        return false;
    });
    $("#modalAuthUsappy").modal();
}


// Menu ä½œæ¥­ä¼ç¥¨ä½œæˆãƒ»æƒ…å ±æ¤œç´¢ãƒœã‚¿ãƒ³
function fncType(moveType) {
    $("#modalAuthUsappy").on('hidden.bs.modal', function (e) {
        clearValueModal(this);
    });
    $("#modalAuthReceivable").on('hidden.bs.modal', function (e) {
        clearValueModal(this);
    });
    if(moveType === "regist"){
        $('.card_url_redirect').attr('value', url_regist_workslip);
        // $.sidr("close", "sidr");
        $.sidr("close", "sidr", function(){
            return false;
        });
        $("#modalSelectMember").modal();
    }
}
function fncAuth(modalType){
    $("#modalAuthUsappy").on('hidden.bs.modal', function (e) {
        clearValueModal(this);
    });
    $("#modalAuthReceivable").on('hidden.bs.modal', function (e) {
        clearValueModal(this);
    });
    $("#modalSelectMember").modal("hide");
    if(modalType === "usappy"){
        $('.card_type_redirect').attr('value',type_usappy);
        $.sidr("close", "sidr", function(){
            return false;
        });
        $("#modalAuthUsappy").modal("show");
    }else if(modalType === "receivable"){
        $('.card_type_redirect').attr('value',type_receivable);
        $.sidr("close", "sidr", function(){
            return false;
        });
        $("#modalAuthReceivable").modal("show");
    }
}


var cardmembers = function(){
    var validate = function(){
        $.validator.addMethod("mynumber", function (value, element) {
            if (value == '') {
                return true;
            }
            if (value.match(/^(\d)*$/)) {
                return true;
            } else {
                return false;
            }
        });
        $.validator.addMethod("isKatakana", function (value, element) {
            if (value.match(/^[\uFF65-\uFF9F0-9\-\+\s\(\)]+$/) || value == '') {
                return true;
            }
            return false;
        });

        $('#card_member_usappy #moveTypeUsappy').click(function(){
            $('#card_member_usappy').validate({
                rules: {
                    card_number:{
                        rangelength: [16,16],
                        required: true,
                        mynumber: true
                    },
                    member_birthday:{
                        mynumber: true,
                        rangelength: [8,8],
                        required: function(){
                            if($('#form_license_plates').val() == '' && $('#form_member_kaiinKana').val() == '' && $('#form_member_tel').val() == '')
                            {
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    },
                    license_plates: {
                        mynumber: true,
                        rangelength: [4,4],
                        required: function(){
                            if($('#form_member_birthday').val() == '' && $('#form_member_kaiinKana').val() == '' && $('#form_member_tel').val() == '')
                            {
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    },
                    member_kaiinKana:{
                        isKatakana: true,
                        required: function(){
                            if($('#form_member_birthday').val() == '' && $('#form_license_plates').val() == '' && $('#form_member_tel').val() == '')
                            {
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    },
                    member_tel: {
                        mynumber: true,
                        maxlength: 11,
                        required: function(){
                            if($('#form_member_birthday').val() == '' && $('#form_license_plates').val() == '' && $('#form_member_kaiinKana').val() == '')
                            {
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    card_number:{
                        rangelength: 'カード番号は16文字で入力してください',
                        required: 'カード番号を入力してください',
                        mynumber: 'カード番号は数字で入力してください'
                    },
                    member_birthday:{
                        mynumber: '生年月日は数字で入力してください',
                        rangelength: '生年月日は8文字で入力してください',
                        required: '生年月日/車番/氏名カナ/電話番号のいずれか１つ以上を入力してください'
                    },
                    license_plates: {
                        mynumber: '車番は4文字で入力してください',
                        rangelength: '車番は4文字で入力してください',
                        required: '生年月日/車番/氏名カナ/電話番号のいずれか１つ以上を入力してください'
                    },
                    member_kaiinKana:{
                        isKatakana: '氏名カナはカタカナ50文字以内で入力してください',
                        required: '生年月日/車番/氏名カナ/電話番号のいずれか１つ以上を入力してください'
                    },
                    member_tel: {
                        mynumber: '電話番号は11文字の数字以内で入力してください',
                        maxlength: '電話番号は11文字の数字で入力してください',
                        required: '生年月日/車番/氏名カナ/電話番号のいずれか１つ以上を入力してください'
                    }
                },
                highlight: function(element, errorClass) {
                    $(element).addClass('invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('invalid');
                }
            }).form();
        });
    };
    var submit = function(){
        $('#card_member_usappy #moveTypeUsappy').click(function() {
            $('.box-alert').html('');
            var form = $('#card_member_usappy'),
                valid;
            valid = form.valid();
            if (valid == false)
                return false;
            //Get url and type
            $('.box-alert').html('<img src="'+base_url+'/img/loading7_light_blue.gif" width="30px">');
            var url_redirect = $('#card_member_usappy .card_url_redirect').val();
            var type_redirect = $('#card_member_usappy .card_type_redirect').val();

            var card_number = $('#card_member_usappy #form_card_number').val();
            var member_birthday = $('#card_member_usappy #form_member_birthday').val();
            var member_kaiinKana = $('#card_member_usappy #form_member_kaiinKana').val();
            var member_tel = $('#card_member_usappy #form_member_tel').val();
            var license_plates = $('#card_member_usappy #form_license_plates').val();
            $.ajax({
                url: base_url + '/site/checkmember',
                method: 'post',
                data: {
                    card_number: card_number,
                    member_birthday: member_birthday,
                    member_kaiinKana: member_kaiinKana,
                    member_tel: member_tel,
                    license_plates: license_plates,
                    url_redirect: url_redirect,
                    type_redirect: type_redirect
                },
                dataType: 'json'
            })
                .success(function (data) {
                    if (data === false) {
                        $('.box-alert').html('<div class="alert alert-danger" role="alert">入力条件に該当する会員が存在しません</div>');
                    }
                    else {
                        $('.box-alert').html('');
                        window.location.href = url_redirect;
                    }
                });
        });
    };

    var zen2han = function() {
        $('#form_card_number').on('change', function () {
            utility.zen2han(this);
        });
        $('#form_member_birthday').on('change', function () {
            utility.zen2han(this);
        });
        $('#form_license_plates').on('change', function () {
            utility.zen2han(this);
        });
        $('#form_member_tel').on('change', function () {
            utility.zen2han(this);
        });
        $('#form_member_kaiinKana').on('change', function () {
            $(this).val(utility.toKatakanaCase($(this).val()));
            utility.convertKanaToOneByte(this);
        });
    };

    return{
        init: function(){
            validate();
            submit();
            zen2han();
        }
    };
}();

var card = function(){
    var ready = function(){
        $(function(){

        });
    };

    var validate = function(){
        $.validator.addMethod("mynumber", function (value, element) {
            if (value == '') {
                return true;
            }
            if (value.match(/^(\d)*$/)) {
                return true;
            } else {
                return false;
            }
        });
        $('#card_usappy #moveTypeReceivable').click(function(){
            $('#card_usappy').validate({
                rules: {
                    card_number_auth:{
                        required: true,
                        mynumber: true
                    }
                },
                messages: {
                    card_number_auth:{
                        required: '掛カード番号を入力してください。',
                        mynumber: '掛カード番号は数字で入力してください。'
                    }
                },
                highlight: function(element, errorClass) {
                    $(element).addClass('invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('invalid');
                }
            }).form();
        });
    };

    var zen2han = function() {
        $('#form_card_number_auth').on('change', function () {
            utility.zen2han(this);
        });
    };

    var submit = function(){
        $('#card_usappy #moveTypeReceivable').click(function() {
            $('.box-alert').html('');
            var form = $('#card_usappy'),
                valid;
            valid = form.valid();
            if (valid == false)
                return false;
            //Get url and type
            var url_redirect = $('#card_member_usappy .card_url_redirect').val();
            var type_redirect = $('#card_member_usappy .card_type_redirect').val();
            var card_number_auth = $('#card_usappy #form_card_number_auth').val();

            $.ajax({
                url: base_url + '/site/checkcard',
                method: 'post',
                data: {
                    card_number_auth: card_number_auth,
                    url_redirect: url_redirect,
                    type_redirect: type_redirect
                },
                dataType: 'json'
            })
                .success(function (data) {
                    if (data == false) {
                        $('.box-alert').html('<div class="alert alert-danger" role="alert">設定された掛カード番号が存在しません</div>');
                    }
                    else {
                        $('.box-alert').html('');
                        window.location.href = url_redirect;

                    }
                });
        });
    };

    return{
        init: function(){
            ready();
            zen2han();
            validate();
            submit();
        }
    };
}();

var other = function(){
    var submit = function(){
        $('.btnMemberEtc').click(function() {
            var url_redirect = $('.card_url_redirect').val();
            $.ajax({
                url: base_url + '/site/checkother',
                method: 'post',
                data: {
                    url_redirect: url_redirect,
                    type_redirect: type_other
                },
                dataType: 'json'
            })
                .success(function (data) {
                    if(data == true) {
                        window.location.href = url_redirect;
                    } else {
                        $('.box-alert').html('<div class="alert alert-danger" role="alert">設定された掛カード番号が存在しません</div>');
                    }
                });
        });
    };
    return{
        init: function(){
            submit();
        }
    }
}();

$(function(){
    cardmembers.init();
    card.init();
    other.init();

});
