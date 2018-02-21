var denpyo = function () {
    var click_warranty_btn = function () {
        $('.warrantyBtn').on('click', function () {
            if ($(this).hasClass('checked')) {
                $(this).removeClass("checked");
                $('#warrantyBox').removeClass('on');
            } else {
                $(this).addClass('checked');
                $('#warrantyBox').addClass('on');
            }
        });
    };

    var click_search_product = function () {
        $(document).on('click', '.searchGoods', function () {
            var index = $(this).closest('.commodityBox').attr('attr-index');
            var type = $(this).closest('.commodityBox').attr('type');
            $('#modalCodeSearch #index_modalCodeSearch').val(index);
            $('#modalCodeSearch #type_modalCodeSearch').val(type);
            $("#modalCodeSearch").modal();
        });
    };

    // CAR
    var select_car = function () {
        $('select[name=D02_CAR_SEQ_SELECT]').on('change', function () {
            var seq = $(this).val();
            $('.carDataBasic').addClass('hide');
            $('.car_seq_' + seq).removeClass('hide');
            $('[name=D03_CAR_SEQ]').val($('.D03_CAR_SEQ_' + seq).val());
            $('[name=D03_CAR_NAMEN]').val($('.D03_CAR_NAMEN_' + seq).val());
            $('[name=D03_RIKUUN_NAMEN]').val($('.D03_RIKUUN_NAMEN_' + seq).val());
            $('[name=D03_CAR_ID]').val($('.D03_CAR_ID_' + seq).val());
            $('[name=D03_CAR_NO]').val($('.D02_CAR_NO' + seq).val());
            $('[name=D03_HIRA]').val($('.D03_HIRA_' + seq).val());
            $('[name=D03_METER_KM]').val($('.D03_METER_KM_' + seq).val());
            $('[name=D03_JIKAI_SHAKEN_YM]').val($('.D03_JIKAI_SHAKEN_YM_' + seq).val());
            $('[name=D02_SYAKEN_CYCLE]').val($('.D02_SYAKEN_CYCLE_' + seq).val());
            $('#modalEditCar a[id^=delete]').show();
            $('#modalEditCar a#delete' + seq).hide();
        });
    };

    var convert_zen2han = function () {
        $('#D01_CUST_NAMEK').on('change', function () {
            $(this).val(utility.toKatakanaCase($(this).val()));
            utility.convertKanaToOneByte(this);
        });

        $('#D01_CUST_NAMEK, #D01_YUBIN_BANGO , #D01_TEL_NO, #D01_MOBTEL_NO, #D01_KAKE_CARD_NO').on('change', function () {
            utility.zen2han(this);
        });

        $('.D02_JIKAI_SHAKEN_YM , .D02_METER_KM, .D02_CAR_NO, .D02_CAR_ID').on('change', function () {
            utility.zen2han(this);
        });

        $('[name=D03_SEKOU_YMD] , [name=D01_SS_CD], [name=D03_POS_DEN_NO], .noProduct, .priceProduct, .totalPriceProduct').on('change', function () {
            utility.zen2han(this);
        });
        $('[name=date_1] , [name=date_2], [name=date_3], [name=pressure_front], [name=pressure_behind], [name=km]').on('change', function () {
            utility.zen2han(this);
        });
        $('#warrantyBox input[name$=_size],#warrantyBox input[name$=_serial]').on('change', function () {
            utility.zen2han(this);
        });
        $('#code_search_value').on('change', function () {
            utility.zen2han(this);
        });
        $('#front_right, #front_left, #behind_left, #behind_right, #date').on('change', function () {
            utility.zen2han(this);
        });
        $('#right_front_size, #left_front_size, #right_behind_size, #left_behind_size, #other_a_size,#other_b_size').on('change', function () {
            utility.zen2han(this);
        });
        $('#input_weight_tax, #earnest_money').on('change', function () {
            utility.zen2han(this);
        });
    };

    var validate = function () {
        jQuery.validator.addMethod("totalPriceProduct", function (value, element) {
            var count = parseInt($(element).closest('.on').find('.noProduct').val()),
                price = parseInt($(element).closest('.on').find('.priceProduct').val());
            if (parseInt(value) < count * price) return false;
            return true;
        });

        jQuery.validator.addMethod("pos_den_no", function (value, element) {
            if (value == '') return true;
            if (value.match(/^([0-9,]+)?$/)) {
                var arr = value.split(','),
                    arr_length = arr.length,
                    count_delimeter = arr_length - 1;

                if (count_delimeter == 0) {
                    if (value.length != 4) {
                        return false;
                    }
                } else {
                    for (var i = 0; i < arr_length; i++) {
                        if (arr[i].length != 4) return false;
                    }
                }

                return true;
            }
            return false;
        }, 'POS伝票番号はカンマ区切りの4文字の数字で入力してください');

        jQuery.validator.addMethod("check_taisa", function (value, element) {
            var rel = $(element).attr('rel'),
                val = $('#comcd' + rel).val();
            if ($(element).closest('.commodityBox').attr('type') == 'product' && (!$(element).hasClass('no_event') && $('#check_pdf').val() == 'disabled' && parseInt(val) >= 42000 && parseInt(val) <= 42999)) {
                return false
            }

            return true;
        });

        jQuery.validator.addMethod("is_size", function (value, element) {
            if (value.match(/^([0-9A-Za-z]+)?$/)) {
                return true;
            }
            return false;
        });

        jQuery.validator.addMethod("check_date_order", function (value, element) {
            var start_h = parseInt($('[name=D03_AZU_BEGIN_HH]').val()),
                start_m = parseInt($('[name=D03_AZU_BEGIN_MI]').val()),
                end_h = parseInt($('[name=D03_AZU_END_HH]').val()),
                end_m = parseInt($('[name=D03_AZU_END_MI]').val());

            if (start_h > end_h) return false;
            if (start_h == end_h && start_m > end_m) return false;
            return true;
        }, '終了時間は開始時間より入力してください。');

        var validator = $('#login_form').validate({
            ignore: "",
            rules: {
                M08_NAME_MEI_M08_NAME_SEI: {
                    required: true
                },
                D02_CAR_SEQ_SELECT: {
                    required: true
                },
                D03_SEKOU_YMD: {
                    required: false,
                    digits: true,
                    minlength: 8,
                    date_format: true
                },
                D03_POS_DEN_NO: {
                    pos_den_no: true,
                    maxlength: 50
                },
                D03_KAKUNIN: {
                    required: function () {
                        return $('#valuables1').is(':checked');
                    }
                },
                D03_AZU_END_HH: {
                    check_date_order: true
                },
                D03_SUM_KINGAKU: {
                    maxlength: 10
                },
                D03_KITYOHIN: {
                    required: true
                },
                D03_SEISAN: {
                    required: true
                },
                date: {
                    date_format: true
                },
                km: {
                    min: 0
                },
                oil_check: {
                    required: false
                },
                oil_leak_check: {
                    required: false
                },
                cap_check: {
                    required: false
                },
                drain_bolt_check: {
                    required: false
                },
                tire_check: {
                    required: false
                },
                nut_check: {
                    required: false
                },
                note: {
                    required: function () {
                        if ($("input[name=oil_check]:checked").val() == '0' || $("input[name=oil_leak_check]:checked").val() == '0' || $("input[name=cap_check]:checked").val() == '0' || $("input[name=drain_bolt_check]:checked").val() == '0' || $("input[name=tire_check]:checked").val() == '0' || $("input[name=nut_check]:checked").val() == '0')
                            return true;
                        return false;
                    }
                },
                right_front_size: {
                    is_size: true
                },
                left_front_size: {
                    is_size: true
                },
                right_behind_size: {
                    is_size: true
                },
                left_behind_size: {
                    is_size: true
                },
                other_a_size: {
                    is_size: true
                },
                other_b_size: {
                    is_size: true
                },
                right_front_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                left_front_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                right_behind_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                left_behind_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                other_a_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                other_b_serial: {
                    digits: true,
                    rangelength: [4, 4]
                },
                'denpyo_inspection[WEIGHT_TAX]': {
                    digits: true
                },
                'denpyo_inspection[EARNEST_MONEY]': {
                    digits: true
                },
                'denpyo_suggest[D03_POS_DEN_NO]': {
                    pos_den_no: true,
                    maxlength: 50
                }
            },
            messages: {
                M08_NAME_MEI_M08_NAME_SEI: {
                    required: '受付担当者を選択してください'
                },
                D02_CAR_SEQ_SELECT: {
                    required: '先に車両情報を作成して下さい。'
                },
                D03_POS_DEN_NO: {
                    maxlength: 'POS伝票番号50桁の数字以内で入力してください。'
                },
                D03_SEKOU_YMD: {
                    required: '施行日を入力してください',
                    digits: '施行日は8文字の数字で入力してください',
                    minlength: '施行日は8文字の数字で入力してください',
                    date_format: '施行日が正しくありません'
                },
                D03_KAKUNIN: {
                    required: function () {
                        return '貴重品「有」の場合は、お客様確認を行い、お客様確認チェックをＯＮにしてください';
                    }
                },
                D03_SUM_KINGAKU: {
                    maxlength: '合計金額は10桁の数字以内で入力してください。'
                },
                D03_KITYOHIN: {
                    required: '貴重品を選択してください'
                },
                D03_SEISAN: {
                    required: '精算方法を選択してください'
                },
                date: {
                    date_format: '次回交換目安が正しくありません。'
                },
                km: {
                    min: 'kmは0桁の数字以外で入力してください。'
                },
                oil_check: {
                    required: '選択して下さい'
                },
                oil_leak_check: {
                    required: '選択して下さい'
                },
                cap_check: {
                    required: '選択して下さい'
                },
                drain_bolt_check: {
                    required: '選択して下さい'
                },
                tire_check: {
                    required: '選択して下さい'
                },
                nut_check: {
                    required: '選択して下さい'
                },
                note: {
                    required: 'NG理由を記入してください'
                },
                right_front_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                left_front_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                right_behind_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                left_behind_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                other_a_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                other_b_serial: {
                    digits: '4桁の数字で入力してください',
                    rangelength: '4桁の数字で入力してください'
                },
                right_front_size: {
                    is_size: '英数字で入力してください'
                },
                left_front_size: {
                    is_size: '英数字で入力してください'
                },
                right_behind_size: {
                    is_size: '英数字で入力してください'
                },
                left_behind_size: {
                    is_size: '英数字で入力してください'
                },
                other_a_size: {
                    is_size: '英数字で入力してください'
                },
                other_b_size: {
                    is_size: '英数字で入力してください'
                },
                'denpyo_inspection[WEIGHT_TAX]': {
                    digits: '数字で入力したください'
                },
                'denpyo_inspection[EARNEST_MONEY]': {
                    digits: '数字で入力してください'
                }

            },
            invalidHandler: function () {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            }
        });

        jQuery.validator.addMethod("real_1", function (value, element) {
            var value = $(element).val();
            if (value == '') return true;
            if (value.match(/^\d+(.)?(\d{1})?$/)) {
                return true;
            }
            return false;
        });

        $('.noProduct').each(function () {
            var rel = $(this).attr('rel');
            var parent = $(this).closest('.commodityBox');
            $(this).rules("add", {
                real_1: true,
                min: 0.1,
                max: 9999.9,
                required: function () {
                    if (parent.find('.codeSearchProduct[rel=' + rel + ']').val() != '')
                        return true;
                    return false;
                },
                messages: {
                    real_1: '数量が正しくありません',
                    required: '数量を入力してください',
                    min: '数量は0.1以上の値を入力してください',
                    max: '数量は9999.9以内の数字で入力してください。'
                }
            });
        });

        $('.priceProduct').each(function () {
            $(this).rules("add", {
                digits: true,
                messages: {
                    digits: '単価は数字で入力してください'
                }
            });
        });

        $('.totalPriceProduct').each(function () {
            $(this).rules("add", {
                digits: true,
                totalPriceProduct: true,
                maxlength: 10,
                messages: {
                    digits: '金額は数字で入力してください',
                    totalPriceProduct: '金額は数量と単価の掛け算に合っていません',
                    maxlength: '金額は10桁の数字以内で入力してください。'
                }
            });
        });

        $('.codeSearchProduct').each(function () {
            $(this).rules("add", {
                check_taisa: true,
                messages: {
                    check_taisa: 'タイヤの商品（042000~042999）を入力しないでください'
                }
            });
        });
    };

    var confirm_submit = function () {
        $('#btnRegistWorkSlip').on('click', function () {
            var form = $(this).closest('form'),
                valid = form.valid();
            form.removeAttr('target').attr('action', $('#url_action').val());
            if (valid == false) {
                var tooltip_hidden = $('input[name=D03_KAKUNIN]').attr('aria-describedby');
                if (tooltip_hidden != '') {
                    $('#' + tooltip_hidden).css({"top": "0px", "left": "300px"});
                    $('#' + tooltip_hidden).find('.tooltip-arrow').css("left", "50%");
                }
                var tooltip_hidden = $('input[name=D03_SUM_KINGAKU]').attr('aria-describedby');
                if (tooltip_hidden != '') {
                    $('#' + tooltip_hidden).css({"top": "-20px", "left": "300px"});
                    $('#' + tooltip_hidden).find('.tooltip-arrow').css("left", "80%");
                }
                return false;
            }
            $('#modalRegistConfirm').modal();
        });
    };

    var submit = function () {
        $(".btnSubmitDenpyo").click(function () {
            $("#login_form").submit();
        });
    };

    var preview = function () {
        $('#preview').on('click', function () {
            $('#login_form').attr('action', baseUrl + '/shaken/denpyo/preview').attr('target', '_blank');
            $('#login_form')[0].submit();
        });
    };

    return {
        init: function () {
            click_warranty_btn();
            click_search_product();
            convert_zen2han();
            select_car();
            validate();
            confirm_submit();
            submit();
            preview();
        }
    }
}();

var modal_user = function () {
    var click_confirm_user = function () {
        $('.user_confirm').on('click', function () {
            if ($(this).hasClass('checked')) {
                $(this).removeClass("checked");
                $(this).closest('.user_info').find('.btnSubmit').addClass('disabled').attr('disabled', true);
            } else {
                $(this).addClass('checked');
                $(this).closest('.user_info').find('.btnSubmit').removeClass('disabled').attr('disabled', false);
            }
        });
    };

    var validate_customer = function () {
        $.validator.addMethod("isKatakana", function (value, element) {
            if (value.match(/^[\uFF65-\uFF9F0-9\-\+\s\(\)]+$/) || value == '') {
                return true;
            }
            return false;
        });

        $('#modal_customer').validate({
            rules: {
                D01_CUST_NAMEN: {
                    required: true
                },
                D01_CUST_NAMEK: {
                    required: true,
                    isKatakana: true
                },
                D01_KAKE_CARD_NO: {
                    digits: true,
                    minlength: 16
                },
                D01_YUBIN_BANGO: {
                    minlength: 7
                },
                D01_TEL_NO: {
                    digits: true,
                    required: function () {
                        if ($('#D01_MOBTEL_NO').val() == '') {
                            return true;
                        }
                        return false;
                    }
                },
                D01_MOBTEL_NO: {
                    required: function () {
                        if ($('#D01_TEL_NO').val() == '') {
                            return true;
                        }
                        return false;
                    },
                    digits: true
                }
            },
            messages: {
                D01_CUST_NAMEN: {
                    required: 'お名前を入力してください'
                },
                D01_CUST_NAMEK: {
                    required: 'お名前（フリガナ）を入力してください',
                    isKatakana: 'お名前（フリガナ）はカタカナで入力してください'
                },
                'D01_KAKE_CARD_NO': {
                    minlength: '掛カード番号は16文字の数字で入力してください',
                    digits: '掛カード番号は16文字の数字で入力してください'
                },
                D01_YUBIN_BANGO: {
                    minlength: '郵便番号は7文字の数字で入力してください'
                },
                D01_TEL_NO: {
                    digits: '電話番号は数字で入力してください',
                    required: '電話番号または携帯電話番号を入力してください'
                },
                D01_MOBTEL_NO: {
                    digits: '携帯電話番号は数字で入力してください',
                    required: '電話番号または携帯電話番号を入力してください'
                }
            }
        });
    };

    var get_addr_from_zipcode = function () {
        $('#btn_get_address').off('click').on('click', function () {
            var zipcode = $('#modalEditCustomer input[name=D01_YUBIN_BANGO]').val();
            if (zipcode.length != 7) return;
            var request = $.ajax({
                type: 'post',
                data: {
                    zipcode: zipcode
                },
                url: baseUrl + '/registworkslip/search/address'
            });
            var response = request.done(function (data) {
                var html = '';
                if (data != false) {
                    html = data[0].prefecture + ' ' + data[0].city + ' ' + data[0].town;
                }

                $('#D01_ADDR').val(html);
            });
        });
    };

    var submit_modal_user = function () {
        $("#agreeFormBtn").click(function () {
            var form = $(this).closest('form'),
                valid = form.valid();
            if (valid == false) {
                return false;
            }

            utility.showLoading('登録中です');
            $.post(base_url + '/inspection/denpyo/customer',
                {
                    'D01_CUST_NO': $("#modalEditCustomer input[name=D01_CUST_NO]").val(),
                    'D01_CUST_NAMEN': $("#modalEditCustomer input[name=D01_CUST_NAMEN]").val(),
                    'D01_CUST_NAMEK': $("#modalEditCustomer input[name=D01_CUST_NAMEK]").val(),
                    'D01_KAKE_CARD_NO': $("#modalEditCustomer input[name=D01_KAKE_CARD_NO]").val(),
                    'D01_ADDR': $("#modalEditCustomer input[name=D01_ADDR]").val(),
                    'D01_TEL_NO': $("#modalEditCustomer input[name=D01_TEL_NO]").val(),
                    'D01_MOBTEL_NO': $("#modalEditCustomer input[name=D01_MOBTEL_NO]").val(),
                    'D01_NOTE': $("#modalEditCustomer #D01_NOTE").val(),
                    'D01_KAIIN_CD': $("#modalEditCustomer input[name=D01_KAIIN_CD]").val(),
                    'D01_YUBIN_BANGO': $("#modalEditCustomer input[name=D01_YUBIN_BANGO]").val(),
                    'type_redirect': $('#type_redirect').val()
                },
                function (data) {
                    utility.hideLoading();
                    if (data.card_number_exist == 1) {
                        $("#updateInfo").html('<div class="alert alert-danger">同じ掛カード番号が既に登録されています。</div>');
                        return;
                    }
                    //success
                    if (data.result_api && data.result_db) {
                        $("#updateInfo").html('<div class="alert alert-success">編集が成功しました。</div>');
                        var type_redirect = $('#type_redirect').val(),
                            result = data.member_info;
                        $('input[name=D01_CUST_NO]').val(result['D01_CUST_NO']);
                        $('#hidden_cust_note').val(result['D01_NOTE']);
                        if (type_redirect == 1) {
                            $('#hidden_cust_yubin_bango').val(result['member_yuubinBangou']);
                            $('#hidden_cust_addr').val(result['member_address']);
                            $('#hidden_cust_tel_no').val(result['member_telNo1']);
                            $('#hidden_cust_mobtel_no').val(result['member_telNo2']);
                            $('#hidden_cust_namen').val(result['member_kaiinName']);
                            $('#hidden_cust_namek').val(result['member_kaiinKana']);
                            //update text in modal
                            $("#modalEditCustomer input[name=D01_KAIIN_CD]").val(result['member_kaiinCd']);
                            $("#modalEditCustomer input[name=D01_MOBTEL_NO]").val(result['member_telNo2']);
                        } else {
                            $('#hidden_cust_yubin_bango').val(result['D01_YUBIN_BANGO']);
                            $('#hidden_cust_addr').val(result['D01_ADDR']);
                            $('#hidden_cust_tel_no').val(result['D01_TEL_NO']);
                            $('#hidden_cust_mobtel_no').val(result['D01_MOBTEL_NO']);
                            $('#hidden_cust_namen').val(result['D01_CUST_NAMEN']);
                            $('#hidden_cust_namek').val(result['D01_CUST_NAMEK']);
                            //update text in modal
                            $("#modalEditCustomer input[name=D01_KAIIN_CD]").val(result['D01_KAIIN_CD']);
                            $("#modalEditCustomer input[name=D01_MOBTEL_NO]").val(result['D01_MOBTEL_NO']);
                        }

                        //update text in customer modal
                        $("#modalEditCustomer input[name=D01_CUST_NO]").val($('#hidden_cust_no').val());
                        $("#modalEditCustomer input[name=D01_CUST_NAMEN]").val($('#hidden_cust_namen').val());
                        $("#modalEditCustomer input[name=D01_CUST_NAMEK]").val($('#hidden_cust_namek').val());
                        $("#modalEditCustomer input[name=D01_ADDR]").val($('#hidden_cust_addr').val());
                        $("#modalEditCustomer input[name=D01_TEL_NO]").val($('#hidden_cust_tel_no').val());
                        $("#modalEditCustomer input[name=D01_MOBTEL_NO]").val($('#hidden_cust_mobtel_no').val());
                        $("#modalEditCustomer #D01_NOTE").val($('#hidden_cust_note').val());
                        $("#modalEditCustomer input[name=D01_YUBIN_BANGO]").val($('#hidden_cust_yubin_bango').val());
                        $("#modalEditCustomer input[name=D01_KAKE_CARD_NO]").val(result['D01_KAKE_CARD_NO']);

                        //update text in main screen
                        $('p#text_cust_namen').text($('#hidden_cust_namen').val());
                        $('p#text_cust_namek').text($('#hidden_cust_namek').val());
                        $('p#text_cust_note').text($('#hidden_cust_note').val());

                        //update input customer no in car modal
                        $('#modalEditCar [name=D02_CUST_NO]').val($('#hidden_cust_no').val());

                        $('.user_confirm').removeClass('checked');
                        $('#agreeFormBtn').addClass('disabled').attr('disabled', true);
                        $("#modalEditCustomer").modal('hide');
                        $("#updateInfo").html('');
                    }
                    else {
                        $("#updateInfo").html('<div class="alert alert-danger">編集が失敗しました。</div>');
                    }
                }
            );
        });
    };
    return {
        init: function () {
            click_confirm_user();
            validate_customer();
            get_addr_from_zipcode();
            submit_modal_user();
        }
    }
}();

var modal_car = function () {
    var change_maker_cd = function () {
        $(document).on('change', '#modalEditCar select.D02_MAKER_CD', function () {
            var cur = $(this);
            $(cur).parents('section').find('.D02_MODEL_CD option[value!=""]').remove();
            $(cur).parents('section').find('.D02_TYPE_CD option[value!=""]').remove();
            $(cur).parents('section').find('.D02_GRADE_CD option[value!=""]').remove();
            $(cur).parents('section').find('.D02_SHONENDO_YM').val('');

            $(cur).parents('section').find('#D02_CAR_NAMEN_OTHER').attr('disabled', 'disabled').hide();
            if (cur.val() == '-111') {
                $(cur).parents('section').find('#D02_CAR_NAMEN_OTHER').removeAttr('disabled').show();
            }
            if (cur.val() == '' || cur.val() == '-111') return;

            $.getJSON(
                $('#modalEditCar #url_car_api').val(), {
                    'maker_code': cur.val()
                }
            ).done(function (data) {
                $.each(data, function () {
                    $(cur).parents('section').find('.D02_MODEL_CD').append(
                        $('<option value="' + this.model_code + '">' + this.model + '</option>')
                    );
                });
            });
        });
    };

    var change_model_cd = function () {
        $(document).on('change', '.D02_MODEL_CD', function () {
            var cur = $(this);
            $(cur).parents('section').find('.D02_TYPE_CD option[value!=""]').remove();
            $(cur).parents('section').find('.D02_GRADE_CD option[value!=""]').remove();
            var year = $(cur).parents('section').find('.D02_SHONENDO_YM').val().substr(0, 4);

            if ($(this).val() == '0' || year.length == 0) {
                return;
            }

            $.getJSON(
                $('#modalEditCar #url_car_api').val(),
                {
                    'maker_code': $(cur).parents('section').find('.D02_MAKER_CD').val(),
                    'model_code': $(this).val(),
                    'year': year
                }
            ).done(function (data) {
                if (data.error == undefined) {
                    $.each(data, function () {
                        $(cur).parents('section').find('.D02_TYPE_CD').append(
                            $('<option value="' + this.type_code + '">' + this.type + '</option>')
                        );
                    });
                }
            });
        });
    };

    var change_type_cd = function () {
        $(document).on('change', '.D02_TYPE_CD', function () {
            var cur = $(this);
            $(cur).parents('section').find('.D02_GRADE_CD option[value!=""]').remove();
            var year = $(cur).parents('section').find('.D02_SHONENDO_YM').val().substr(0, 4);

            if ($(this).val() == '' || year.length == 0) {
                return;
            }

            $.getJSON(
                $('#modalEditCar #url_car_api').val(),
                {
                    'type_code': $(this).val(),
                    'year': year,
                    'maker_code': $(cur).parents('section').find('.D02_MAKER_CD').val(),
                    'model_code': $(cur).parents('section').find('.D02_MODEL_CD').val()
                }
            ).done(function (data) {
                $.each(data, function () {
                    $(cur).parents('section').find('.D02_GRADE_CD').append(
                        $('<option value="' + this.grade_code + '">' + this.grade + '</option>')
                    );
                });
            });
        });
    };

    var change_shoendo_ym = function () {
        $(document).on('change', '.D02_SHONENDO_YM', function () {
            $(this).parents('section:first').find('.D02_MODEL_CD').trigger('change');
        });
    };

    var change_prefecture = function () {
        $(document).on('change', '#modalEditCar select[name=prefecture]', function () {
            var carPlaces = jQuery.parseJSON($('#modalEditCar #car_palaces').val());
            var items = [];
            if ($(this).val() != '') {
                items = carPlaces[$(this).val()];
            }
            var select = $(this).parents('div.formGroup:first').find('select[name^=D02_RIKUUN_NAMEN]');
            select.find('option[value!=""]').remove();

            for (var i = 0; i < items.length; i++) {
                select.append(
                    $('<option></option>').attr('value', items[i]).text(items[i])
                );
            }

            if (items.length == 1) {
                select.find('option:last').prop('selected', true);
            }
        });
    };

    var set_prefecture_edit_denpyo = function () {
        $('select[name^=D02_RIKUUN_NAMEN]').each(function () {
            var carPlaces = jQuery.parseJSON($('#modalEditCar #car_palaces').val());
            var inputPlace = $(this).val();
            if (inputPlace == '') {
                return;
            }
            for (var prefecture in carPlaces) {
                for (var i = 0; i < carPlaces[prefecture].length; i++) {
                    if (carPlaces[prefecture][i] == inputPlace) {
                        $(this).parents('div.formGroup:first').find('select[name=prefecture]').val(prefecture);
                        return;
                    }
                }
            }
        });
    };

    var validate = function () {
        jQuery.validator.addMethod("car_no", function (value, element) {
            if (value == '0000') return false;
            return true;
        });
        $('#modal_car').validate({});

        $('#modalEditCar .D02_SHONENDO_YM').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules('add', {
                minlength: 6,
                date_year_month: true,
                messages: {
                    minlength: function () {
                        return rel + '台目の初年度登録年月は6文字の数字で入力してください';
                    },
                    date_year_month: rel + '台目の初年度登録年月が正しくありません'
                }
            });
        });

        $('#modalEditCar .D02_JIKAI_SHAKEN_YM').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: false,
                digits: true,
                minlength: 8,
                date_format: true,
                messages: {
                    required: function () {
                        return rel + '台目の車検満了日を入力してください';
                    },
                    digits: function () {
                        return rel + '台目の車検満了日は8文字の数字で入力してください';
                    },
                    minlength: function () {
                        return rel + '台目の車検満了日は8文字の数字で入力してください';
                    },
                    date_format: rel + '台目の車検満了日が正しくありません'
                }
            });
        });

        $('#modalEditCar .D02_METER_KM').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                digits: true,
                messages: {
                    required: function () {
                        return rel + '台目の走行距離を入力してください';
                    },
                    digits: function () {
                        return rel + '台目の走行距離は数字で入力してください';
                    }
                }
            });
        });

        $('#modalEditCar .D02_RIKUUN_NAMEN').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                messages: {
                    required: function () {
                        return rel + '台目の運輸支局を入力してください';
                    }
                }
            });
        });

        $('#modalEditCar .D02_CAR_ID').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                digits: true,
                minlength: 3,
                messages: {
                    required: function () {
                        return rel + '台目の分類コードを入力してください';
                    },
                    minlength: rel + '台目の分類コードは3文字の数字で入力してください',
                    digits: rel + '台目の分類コードは3文字の数字で入力してください'
                }
            });
        });

        $('#modalEditCar .D02_HIRA').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                hiragana: true,
                messages: {
                    required: function () {
                        return rel + '台目のひらがなを入力してください';
                    },
                    hiragana: function () {
                        return rel + '台目のひらがなはひらがなで入力してください';
                    }
                }
            });
        });

        $('#modalEditCar .D02_CAR_NO').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                digits: true,
                minlength: 4,
                car_no: true,
                messages: {
                    required: function () {
                        return rel + '台目の登録番号を入力してください';
                    },
                    digits: function () {
                        return rel + '台目の登録番号は4文字の数字で入力してください';
                    },
                    minlength: function () {
                        return rel + '台目の登録番号は4文字の数字で入力してください';
                    },
                    car_no: function () {
                        return rel + '台目の登録番号に0000は入力できません';
                    }
                }
            });
        });

        $('#modalEditCar .D02_CAR_NAMEN_OTHER').each(function () {
            var rel = $(this).closest('section').attr('rel');
            $(this).rules("add", {
                required: function () {
                    if ($('#modal_car section[rel=' + rel + '] #D02_MAKER_CD option:selected').val() == -111) {
                        return true;
                    }
                    return false;
                },
                messages: {
                    required: function () {
                        return rel + 'メーカーを入力してください';
                    }
                }
            });
        });

        $('#modalEditCar .D02_MAKER_CD').each(function () {
            var rel = $(this).closest('section').attr('rel');
            $(this).rules("add", {
                required: true,
                messages: {
                    required: function () {
                        return rel + 'メーカーを入力してください';
                    }
                }
            });
        });

        $('#modalEditCar .D02_MODEL_CD').each(function () {
            var rel = $(this).closest('section').attr('rel');
            $(this).rules("add", {
                required: function () {
                    if ($('#modal_car section[rel=' + rel + '] #D02_MAKER_CD option:selected').val() == -111) {
                        return false;
                    }
                    return true;
                },
                messages: {
                    required: function () {
                        return rel + '車名を入力してください';
                    }
                }
            });
        });


    };

    var submit_car = function () {
        $("#updateCar").click(function () {
            var form = $(this).closest('form'),
                valid = form.valid();
            if (valid == false) {
                return false;
            }
            var arr = [],
                arr_seq = [],
                string;
            for (var j = 1; j < 6; ++j) {
                if ($("#dataCar" + j).hasClass('accOpen')) {
                    string = {
                        'D02_CUST_NO': $("#hidden_cust_no").val(),
                        'D02_CAR_SEQ': $("#dataCar" + j).find("#D02_CAR_SEQ").val(),
                        'D02_CAR_NAMEN': $("#dataCar" + j).find("#D02_MODEL_CD option:selected").html(),
                        'D02_JIKAI_SHAKEN_YM': $("#dataCar" + j).find("#D02_JIKAI_SHAKEN_YM" + j).val(),
                        'D02_METER_KM': $("#dataCar" + j).find("#D02_METER_KM").val(),
                        'D02_SYAKEN_CYCLE': $("#dataCar" + j).find("#D02_SYAKEN_CYCLE").val(),
                        'D02_RIKUUN_NAMEN': $("#dataCar" + j).find("#D02_RIKUUN_NAMEN").val(),
                        'D02_CAR_ID': $("#dataCar" + j).find("#D02_CAR_ID").val(),
                        'D02_HIRA': $("#dataCar" + j).find("#D02_HIRA").val(),
                        'D02_CAR_NO': $("#dataCar" + j).find("#D02_CAR_NO").val(),
                        'D02_INP_DATE': $("#dataCar" + j).find("#D02_INP_DATE").val(),
                        'D02_INP_USER_ID': $("#dataCar" + j).find("#D02_INP_USER_ID").val(),
                        'D02_UPD_DATE': $("#dataCar" + j).find("#D02_UPD_DATE").val(),
                        'D02_UPD_USER_ID': $("#dataCar" + j).find("#D02_UPD_USER_ID").val(),
                        'D02_MAKER_CD': $("#dataCar" + j).find("#D02_MAKER_CD").val(),
                        'MAKER_CD_OTHER': $("#dataCar" + j).find("#D02_CAR_NAMEN_OTHER").val(),
                        'car_makerNamen': $("#dataCar" + j).find("#D02_MAKER_CD option:selected").html(),
                        'D02_MODEL_CD': $("#dataCar" + j).find("#D02_MODEL_CD").val(),
                        'car_modelNamen': $("#dataCar" + j).find("#D02_MODEL_CD option:selected").html(),
                        'D02_SHONENDO_YM': $("#dataCar" + j).find("#D02_SHONENDO_YM" + j).val(),
                        'D02_TYPE_CD': $("#dataCar" + j).find("#D02_TYPE_CD").val(),
                        'car_typeNamen': $("#dataCar" + j).find("#D02_TYPE_CD option:selected").html(),
                        'D02_GRADE_CD': $("#dataCar" + j).find("#D02_GRADE_CD").val(),
                        'car_gradeNamen': $("#dataCar" + j).find("#D02_GRADE_CD option:selected").html(),
                        'dataCarApiField': $("#dataCar" + j).find("#CAR_API_FIELD").val(),

                    };
                    arr.push(string);
                    arr_seq.push($("#dataCar" + j).find("#D02_CAR_SEQ").val());
                }
            }

            if (arr.length == 0) {
                alert('車両情報1台目は必須です');
                return false;
            }

            utility.showLoading('登録中です');
            $.post(base_url + '/inspection/denpyo/car', {
                'dataPost': JSON.stringify(arr),
                'D02_CUST_NO': $("#D02_CUST_NO").val(),
                'D03_DEN_NO': $("#D03_DEN_NO").val(),
                'D01_KAIIN_CD': $("#D01_KAIIN_CD").val(),
                'type_redirect': $("#type_redirect").val()
            }).done(function (data) {
                utility.hideLoading();
                if (!$("#D02_CUST_NO").val()) {
                    $("#updateCarInfo").html('<div class="alert alert-danger">先にお客様情報を作成して下さい。</div>');
                    return;
                }
                if (data.result == '1') {
                    $("#updateCarInfo").html('<div class="alert alert-success">編集が成功しました。</div>');

                    var arr_data = data.data;
                    //input hidden section car
                    //If maker is Other
                    if (arr_data[0]['D02_CAR_NAMEN'] == '' && arr_data[0]['MAKER_CD_OTHER'] != '') {
                        arr_data[0]['D02_CAR_NAMEN'] = arr_data[0]['MAKER_CD_OTHER'];
                    }
                    $('#section_car input[name=D03_CAR_SEQ]').val(1);
                    $('#section_car input[name=D03_CAR_NAMEN]').val(arr_data[0]['D02_CAR_NAMEN']);
                    $('#section_car input[name=D03_RIKUUN_NAMEN]').val(arr_data[0]['D02_RIKUUN_NAMEN']);
                    $('#section_car input[name=D03_CAR_ID]').val(arr_data[0]['D02_CAR_ID']);
                    $('#section_car input[name=D03_CAR_NO]').val(arr_data[0]['D02_CAR_NO']);
                    $('#section_car input[name=D03_HIRA]').val(arr_data[0]['D02_HIRA']);
                    $('#section_car input[name=D03_METER_KM]').val(arr_data[0]['D02_METER_KM']);
                    $('#section_car input[name=D03_JIKAI_SHAKEN_YM]').val(arr_data[0]['D02_JIKAI_SHAKEN_YM']);
                    $('#section_car input[name=D02_SYAKEN_CYCLE]').val(arr_data[0]['D02_SYAKEN_CYCLE']);

                    var html_select = '',
                        html = '';
                    for (var i = 1; i <= arr_data.length; i++) {
                        //section car
                        //If maker is Other
                        if (arr_data[i - 1]['D02_CAR_NAMEN'] == '' && arr_data[i - 1]['MAKER_CD_OTHER'] != '') {
                            arr_data[i - 1]['D02_CAR_NAMEN'] = arr_data[i - 1]['MAKER_CD_OTHER'];
                        }
                        var hidden = (i == 1) ? '' : 'hide';
                        html_select += '<option value="' + i + '">' + arr_data[i - 1]['D02_CAR_NO'] + '</option>';
                        html += '<div class="car_info car_seq_' + i + ' ' + hidden + '">' +
                            '<input type="hidden" value="' + i + '" class="D03_CAR_SEQ_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_CAR_NAMEN'] + '" class="D03_CAR_NAMEN_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_RIKUUN_NAMEN'] + '" class="D03_RIKUUN_NAMEN_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_CAR_ID'] + '" class="D03_CAR_ID_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_HIRA'] + '" class="D03_HIRA_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_METER_KM'] + '" class="D03_METER_KM_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_JIKAI_SHAKEN_YM'] + '" class="D03_JIKAI_SHAKEN_YM_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_SYAKEN_CYCLE'] + '" class="D02_SYAKEN_CYCLE_' + i + '">' +
                            '<input type="hidden" value="' + arr_data[i - 1]['D02_CAR_NO'] + '" class="D02_CAR_NO' + i + '">' +
                            '</div>' +
                            '<div class="formItem flx-2 carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">車名</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_CAR_NAMEN'] + '</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">車検満了日</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_JIKAI_SHAKEN_YM'] + '</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">走行距離</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_METER_KM'] + 'km</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">運輸支局</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_RIKUUN_NAMEN'] + '</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">分類コード</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_CAR_ID'] + '</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">ひらがな</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_HIRA'] + '</p>' +
                            '</div>' +
                            '<div class="formItem carDataBasic car_seq_' + i + ' ' + hidden + '">' +
                            '<label class="titleLabel">登録番号</label>' +
                            '<p class="txtValue">' + arr_data[i - 1]['D02_CAR_NO'] + '</p>' +
                            '</div>';
                    }
                    $('#section_car [name=D02_CAR_SEQ_SELECT]').html(html_select);
                    $('.car_info, .carDataBasic').remove();
                    $(html).appendTo($('#section_car .formGroup'));
                    //modal car
                    if (arr_data.length < 5) {
                        var html_close = $('#modalEditCar section.accClose').clone();
                        for (i = 1; i < 6; i++) {
                            if (i <= arr_data.length) {
                                var html_modal = $('#modalEditCar section#dataCar' + arr_seq[i - 1]).html();
                                $('#modalEditCar section#dataCar' + i).html(html_modal);
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NAMEN').val(arr_data[i - 1]['D02_CAR_NAMEN']).attr('name', 'D02_CAR_NAMEN[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #CAR_API_FIELD').val(arr_data[i - 1]['dataCarApiField']).attr('name', 'CAR_API_FIELD[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NAMEN_OTHER').val(arr_data[i - 1]['MAKER_CD_OTHER']).attr('name', 'D02_CAR_NAMEN_OTHER[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_METER_KM').val(arr_data[i - 1]['D02_METER_KM']).attr('name', 'D02_METER_KM[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_SYAKEN_CYCLE').val(arr_data[i - 1]['D02_SYAKEN_CYCLE']).attr('name', 'D02_SYAKEN_CYCLE[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_ID').val(arr_data[i - 1]['D02_CAR_ID']).attr('name', 'D02_CAR_ID[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_HIRA').val(arr_data[i - 1]['D02_HIRA']).attr('name', 'D02_HIRA[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NO').val(arr_data[i - 1]['D02_CAR_NO']).attr('name', 'D02_CAR_NO[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_MAKER_CD').val(arr_data[i - 1]['D02_MAKER_CD']).attr('name', 'D02_MAKER_CD[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_RIKUUN_NAMEN').val(arr_data[i - 1]['D02_RIKUUN_NAMEN']).attr('name', 'D02_RIKUUN_NAMEN[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_MODEL_CD').val(arr_data[i - 1]['D02_MODEL_CD']).attr('name', 'D02_MODEL_CD[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_GRADE_CD').val(arr_data[i - 1]['D02_GRADE_CD']).attr('name', 'D02_GRADE_CD[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_TYPE_CD').val(arr_data[i - 1]['D02_TYPE_CD']).attr('name', 'D02_TYPE_CD[' + i + ']');

                                $('#modalEditCar #dataCar' + i + ' .D02_SHONENDO_YM').attr('id', 'D02_SHONENDO_YM' + i).attr('name', 'D02_SHONENDO_YM[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' .D02_JIKAI_SHAKEN_YM').attr('id', 'D02_JIKAI_SHAKEN_YM' + i).attr('name', 'D02_JIKAI_SHAKEN_YM[' + i + ']');
                                $('#modalEditCar #dataCar' + i + ' #D02_SHONENDO_YM' + i).val(arr_data[i - 1]['D02_SHONENDO_YM']);
                                $('#modalEditCar #dataCar' + i + ' #D02_JIKAI_SHAKEN_YM' + i).val(arr_data[i - 1]['D02_JIKAI_SHAKEN_YM']);

                                $('#modalEditCar section#dataCar' + i).removeClass('accClose').addClass('accOpen');
                            } else {
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NAMEN').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_MAKER_CD').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NAMEN_OTHER').val('').hide();
                                $('#modalEditCar #dataCar' + i + ' #D02_MODEL_CD').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_SHONENDO_YM' + i).val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_TYPE_CD').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_GRADE_CD').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_JIKAI_SHAKEN_YM' + i).val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_METER_KM').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_SYAKEN_CYCLE').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_RIKUUN_NAMEN').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_ID').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_HIRA').val('');
                                $('#modalEditCar #dataCar' + i + ' #D02_CAR_NO').val('');
                                $('#modalEditCar section#dataCar' + i).removeClass('accOpen').addClass('accClose');
                            }
                            $('#modalEditCar section#dataCar' + i + ' #D02_CAR_SEQ').val(i);
                            $('#modalEditCar section#dataCar' + i + ' .titleLegend').html(i + '台目');
                        }
                        $('#modalEditCar .dateform').removeClass('hasDatepicker');
                        $('#modalEditCar .ymform').removeClass('hasYmpicker');
                        date_picker();
                        set_prefecture_edit_denpyo();
                        //todo tomorrow prefecture after ajax
                    }
                    $("#modalEditCar").modal('hide');
                    $("#updateCarInfo").html('');
                } else {
                    $('#modal_car div.modal-body').animate({
                        scrollTop: 0
                    });
                    $("#updateCarInfo").html('<div class="alert alert-danger">編集が失敗しました。</div>');
                }
            }).fail(function (response) {
                $("#updateCarInfo").html('<div class="alert alert-danger">編集が失敗しました。</div>');
            });
        });
    };

    var date_picker = function () {
        $('.dateform').datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('.ymform').ympicker({
            closeText: '閉じる',
            prevText: '&#x3c;前',
            nextText: '次&#x3e;',
            currentText: '今日',
            monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            dateFormat: 'yymm',
            yearSuffix: '年',
            onSelect: function () {
                $(this).trigger('change');
            }
        });
    };

    return {
        init: function () {
            change_maker_cd();
            change_model_cd();
            change_type_cd();
            change_shoendo_ym();
            change_prefecture();
            set_prefecture_edit_denpyo();
            validate();
            submit_car();
        }
    }
}();

var fee = function () {
    var click_change_size_car = function () {
        $('.select_car_size').on('change', function () {
            var value = $(this).val();
            if (value == 0) {
                $('select.car_weight').html('');
                return;
            }
            _process_load_size_car(value, '');
        });
    };

    var _process_load_size_car = function (value, type) {
        $.ajax({
            url: base_url + '/car-size/change',
            data: {value: value},
            method: 'POST',
            success: function (data) {
                var arr = jQuery.parseJSON(data);
                var html = '';
                for (var i = 0, l = arr.length; i < l; i++) {
                    if (type == 'edit' && i == $('#hidden_car_weight_hidden').val()) {
                        html += '<option selected value="' + i + '">' + arr[i] + '</option>';
                    } else {
                        html += '<option value="' + i + '">' + arr[i] + '</option>';
                    }

                }
                $('select.car_weight').html(html);
            }
        });
    };

    var load_size_car_edit = function () {
        if ($('#D03_DEN_NO').val()) {
            var value = $('.select_car_size').val();
            if (value == 0) {
                $('select.car_weight').html('');
                return;
            }
            _process_load_size_car(value, 'edit');
        }
    };

    var change_fee_basic = function () {
        $('.fee_basic_select').on('change', function () {
            var id = $(this).val();
            if (id == '') {
                $('#text_fee_basic_value').html('');
                $('#unit_fee_basic_value').addClass('hide');
                total_fee();
                return;
            }
            var fee_basic_value = jQuery.parseJSON($('#fee_basic_value').val());
            $('#text_fee_basic_value').html(fee_basic_value[id]);
            $('#unit_fee_basic_value').removeClass('hide');
            total_fee();
        });
    };

    var change_fee_registration = function () {
        $('.fee_registration_select').on('change', function () {
            var id = $(this).val();
            if (id == '') {
                $('#input_weight_tax').val('').attr('disabled', true);
                $('#text_mandatory_insurance').html('');
                $('#text_stamp_fee').html('');
                $('#unit_mandatory_insurance').addClass('hide');
                $('#unit_stamp_fee').addClass('hide');
                total_fee();
                return;
            }
            var fee_registration_value = jQuery.parseJSON($('#fee_registration_value').val());
            $('#input_weight_tax').val(fee_registration_value[id]['WEIGHT_TAX']).removeAttr('disabled');
            $('#text_mandatory_insurance').html(fee_registration_value[id]['MANDATORY_INSURANCE']);
            $('#text_stamp_fee').html(fee_registration_value[id]['STAMP_FEE']);
            $('#unit_mandatory_insurance').removeClass('hide');
            $('#unit_stamp_fee').removeClass('hide');
            total_fee();
        });
    };

    var change_weight_tax = function () {
        $('#input_weight_tax').on('change', function () {
            total_fee();
        });
    };

    var change_parent_discount = function () {
        $('.parent_discount_select').on('change', function () {
            var id = $(this).val();
            if (id == '') {
                $('#section_discount').html('');
                total_fee();
                return;
            }
            var param = {
                parent_discount_id: id
            };
            var request = $.ajax({
                type: 'post',
                data: param,
                url: baseUrl + '/inspection/denpyo/discount'
            });
            var response = request.done(function (data) {
                html = '';
                $.each(data, function (key, value) {
                    if (value['type'] == 'select') {
                        var option = '<option value=""></option>';
                        $.each(value['discount'], function (k, v) {
                            option += '<option value="' + k + '">' + utility.html_encode(v['VALUE']) + ' (' + utility.html_encode(v['DESCRIPTION']) + ')</option>';
                        });
                        html += '<div class="formGroup">' +
                            '<div class="formItem">' +
                            '<label class="labelForm">' + utility.html_encode(value['NAME']) + '</label>' +
                            ' <select name="denpyo_inspection[DISCOUNT][]" class="selectForm select_discount" style="max-width: 500px">' + option + '</select>' +
                            '<span class="txtUnit">円</span>' +
                            '</div>' +
                            '</div>';
                    }
                    if (value['type'] == 'checkbox') {
                        $.each(value['discount'], function (k, v) {
                            checkbox = '<input name="denpyo_inspection[DISCOUNT][]" value="' + k + '" type="checkbox" class="checks checkbox_discount">' +
                                '<span class="spanSingleCheck">' + v['VALUE'] + ' (' + utility.html_encode(v['DESCRIPTION']) + ')</span>';
                        });
                        html += '<div class="formGroup">' +
                            '<div class="formItem">' +
                            '<label class="labelForm">' + utility.html_encode(value['NAME']) + '</label>' +
                            checkbox +
                            '<span class="txtUnit">円</span>' +
                            '</div>' +
                            '</div>';
                    }
                });
                $('#section_discount').html(html);
                total_fee();
            });
        });
    };

    var total_fee = function () {
        var total = '',
            total_fee = '',
            discount = 0,
            discount_select = 0,
            discount_checkbox = 0,
            fee_basic = isNaN(parseInt($('#text_fee_basic_value').html())) ? 0 : parseInt($('#text_fee_basic_value').html()),
            weight_tax = isNaN(parseInt($('#input_weight_tax').val())) ? 0 : parseInt($('#input_weight_tax').val()),
            mandatory_insurance = isNaN(parseInt($('#text_mandatory_insurance').html())) ? 0 : parseInt($('#text_mandatory_insurance').html()),
            stamp_fee = isNaN(parseInt($('#text_stamp_fee').html())) ? 0 : parseInt($('#text_stamp_fee').html());
        total_fee = fee_basic + weight_tax + mandatory_insurance + stamp_fee;
        if (!total_fee) {
            total_fee = '';
            $('#unit_total_fee').addClass('hide');
        } else {
            $('#unit_total_fee').removeClass('hide');
        }
        $('#text_total_fee').html(total_fee).digits();

        $('.select_discount').each(function () {
            if ($(this).find('option:selected').val()) {
                discount_select += parseInt($(this).find('option:selected').html());
            }
        });
        $('.checkbox_discount').each(function () {
            if ($(this).is(':checked')) {
                discount_checkbox += parseInt($(this).closest('.formItem').find('.spanSingleCheck').html());
            }
        });
        discount = discount_select + discount_checkbox;
        total = total_fee + discount;
        $('#total_fee_discount').html(total).digits();

        var earnest_money = isNaN(parseInt($('#earnest_money').val())) ? 0 : parseInt($('#earnest_money').val()),
            actually_paid = earnest_money - total;
        $('#actually_paid').html(actually_paid).digits();
    };

    var click_checkbox_discount = function () {
        $(document).on('click', '.spanSingleCheck', function () {
            if ($(this).hasClass("checked")) {
                $(this)
                    .removeClass("checked")
                    .prevAll(".checks:first").prop("checked", false);
            } else {
                $(this)
                    .addClass("checked")
                    .prevAll(".checks:first").prop("checked", true);
            }
            total_fee();
        });
    };

    var change_select_discount = function () {
        $(document).on('change', '.select_discount', function () {
            total_fee();
        });
    };

    var change_earnest_money = function () {
        $('#earnest_money').on('change', function () {
            total_fee();
        });
    };

    return {
        init: function () {
            change_fee_basic();
            click_change_size_car();
            change_fee_registration();
            change_weight_tax();
            total_fee();
            change_parent_discount();
            click_checkbox_discount();
            change_select_discount();
            change_earnest_money();
            load_size_car_edit();
        }
    }
}();

var comments = function () {
    var click_add_comment = function () {
        $(document).on('click', '.addComment', function () {
            var last = $(this).closest('.group_comment').find('.comment:last'),
                length = $(this).closest('.group_comment').find('.comment').length,
                select_parent_comment = $('#hidden_select_parent_comment').html();
            if (length < 5) {
                var next_index = length == 0 ? 0 : parseInt(last.attr('attr-index')) + 1;
                var html = '<div class="formGroup comment" attr-index="' + next_index + '">'
                    + '<div class="formItem2">'
                    + '<label class="titleLabel">分類</label>'
                    + select_parent_comment
                    + '</div>'
                    + '<div class="formItem2">'
                    + '<label class="titleLabel">コメント</label>'
                    + '<select name="denpyo_inspection[COMMENTS][]" class="selectForm select_comment">'
                    + '</select>'
                    + '</div>'
                    + '<div class="formItem2 remove">'
                    + '<label class="titleLabel">&#12288;</label>'
                    + '<a class="removeComment">削除</a>'
                    + '</div>'
                    + '</div>';
                $(html).appendTo($(this).closest('.group_comment'));
            }
        });
    };

    var click_remove_comment = function () {
        $(document).on('click', '.removeComment', function () {
            var length = $(this).closest('.group_comment').find('.comment').length;
            if (length > 1) {
                $(this).closest('.comment').remove();
            }
        });
    };

    var change_parent_comment = function () {
        $(document).on('change', '.select_parent_comment', function () {
            var item = $(this);
            get_list_comment(item, 'change');
        });
    };

    var get_list_comment = function (item, type) {
        id = item.val();
        var param = {
            parent_comment_id: id
        };
        var request = $.ajax({
            type: 'post',
            data: param,
            url: baseUrl + '/inspection/denpyo/comment'
        });
        var response = request.done(function (data) {
            var html = '<option value=""></option>';
            $.each(data, function (key, value) {
                var index = item.closest('.comment').attr('attr-index');
                if (item.closest('.comment').find('.hidden_comment_' + index).val() == key && type == 'load_edit') {
                    html += '<option selected value="' + key + '">' + utility.html_encode(value) + '</option>';
                } else {
                    html += '<option value="' + key + '">' + utility.html_encode(value) + '</option>';
                }

            });
            item.closest('.comment').find('select.select_comment').html(html);
        });

    };

    var load_comment_edit = function () {
        if ($('#D03_DEN_NO').val()) {
            $('.select_parent_comment').each(function () {
                var item = $(this);
                get_list_comment(item, 'load_edit');
            });
        }
    };

    return {
        init: function () {
            click_add_comment();
            click_remove_comment();
            change_parent_comment();
            load_comment_edit();
        }
    }
}();

var product = function () {
    var removeHrefPaging = function () {
        $('#modalCodeSearch .paging a').attr('href', 'javascript:void(0)');
    };

    var paging = function () {
        $('#modalCodeSearch .paging').on('click', 'a', function () {
            var m05_value = '';
            for (var i = 1; i < 9; ++i) {
                if ($("#labelSearch_M05_KIND_DM_NO" + i).hasClass('checked')) {
                    m05_value = i + ',';
                    break;
                }
            }
            var condition = $('#condition').val(),
                value = $('#code_search_value').val(),
                page = $(this).attr('data-page'),
                url = baseUrl + '/registworkslip/search/index',
                param = {
                    condition: condition,
                    condition_1: m05_value,
                    value: value,
                    page: page
                };
            var request = $.ajax({
                type: 'post',
                data: param,
                url: url
            });
            var response = request.done(function (data) {
                $('#modalCodeSearch .paging a').parent('li').removeClass('active');
                $('#modalCodeSearch .paging a[data-page=' + page + ']').parent('li').addClass('active');
                var tr = '<tr><th>商品コード</th><th>荷姿コード</th><th>品名</th></tr>';
                $.each(data.product, function (key, value) {
                    tr += '<tr data-item="' + value.M05_COM_CD + value.M05_NST_CD + ',' + parseInt(value.M05_COM_CD) + '">'
                        + '<td>' + value.M05_COM_CD + '</td>'
                        + '<td>' + value.M05_NST_CD + '</td>'
                        + '<td>' + ((value.M05_COM_NAMEN == null) ? '' : value.M05_COM_NAMEN) + '</td>'
                        + '<input type="hidden" id="name' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_COM_NAMEN + '">'
                        + '<input type="hidden" id="price' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + ((value.M05_LIST_PRICE == null) ? '' : value.M05_LIST_PRICE) + '">'
                        + '<input type="hidden" id="kind' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_KIND_COM_NO + '">'
                        + '<input type="hidden" id="large' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_LARGE_COM_NO + '">'
                        + '<input type="hidden" value="' + value.M05_COM_CD + '" id="comcd' + value.M05_COM_CD + value.M05_NST_CD + '" />'
                        + '<input type="hidden" value="' + value.M05_NST_CD + '" id="nstcd' + value.M05_COM_CD + value.M05_NST_CD + '" />'
                        + '</tr>'

                });

                $('#modalCodeSearch .tableList tbody').html(tr);
                $('nav.paging').html(html_paging(data.count, page, 10));
                $('#modalCodeSearch .radioGroup.itemFlex label').removeClass('checked');
                $('#' + condition).parent().find('label').addClass('checked');
            });
        });
    };

    var html_paging = function (count_data, current_page, per_page) {
        if (count_data / per_page <= 1) return '';
        var total_page,
            prev,
            next,
            start,
            end,
            first,
            last;
        if (count_data <= per_page) total_page = 1;
        else {
            total_page = count_data % per_page > 0 ? parseInt(count_data / per_page) + 1 : count_data / per_page;
        }
        if (current_page == 0) {
            first = '<li class="first disabled"><span>«</span></li>';
            last = '<li class="last"><a data-page="' + (parseInt(total_page) - 1) + '" href="javascript:void(0)">»</a></li>';
            prev = '<li class="prev disabled"><span><</span></li>';
            next = '<li class="next"><a data-page="' + (parseInt(current_page) + 1) + '" href="javascript:void(0)">></a></li>';
        }
        else if (current_page == total_page - 1) {
            first = '<li class="first"><a data-page="0" href="javascript:void(0)">«</a></li>';
            last = '<li class="last disabled"><span>»</span></li>';
            prev = '<li class="prev"><a data-page="' + (parseInt(current_page) - 1) + '" href="javascript:void(0)"><</a></li>';
            next = '<li class="next"><span>></span></li>';
        } else {
            first = '<li class="first"><a data-page="0" href="javascript:void(0)">«</a></li>';
            last = '<li class="last"><a data-page="' + (parseInt(total_page) - 1) + '" href="javascript:void(0)">»</a></li>';
            prev = '<li class="prev"><a data-page="' + (parseInt(current_page) - 1) + '" href="javascript:void(0)"><</a></li>';
            next = '<li class="next"><a data-page="' + (parseInt(current_page) + 1) + '" href="javascript:void(0)">></a></li>';
        }

        if (total_page < 10) {
            start = 0;
            end = total_page;
        } else {
            if (parseInt(current_page) < 6) {
                start = 0;
                end = 10;
            } else {
                if (parseInt(current_page) > total_page - 5) {
                    start = total_page - 10;
                    end = total_page;
                } else {
                    start = parseInt(current_page) - 5;
                    end = parseInt(current_page) + 5;
                }
            }
        }
        var html = '<ul class="pagination">' + first + prev;
        for (var i = start; i < end; i++) {
            var display = i + 1;
            if (i == current_page)
                html += '<li class="active"><a data-page="' + i + '" href="javascript:void(0)">' + display + '</a></li>';
            else
                html += '<li><a data-page="' + i + '" href="javascript:void(0)">' + display + '</a></li>';
        }

        html += next + last + '</ul>';
        return html;
    };

    var search = function () {
        var m05_value = '';
        for (var i = 1; i < 9; ++i) {
            if ($("#labelSearch_M05_KIND_DM_NO" + i).hasClass('checked')) {
                m05_value = i + ',';
                break;
            }
        }
        var condition = $('#modalCodeSearch .inspection_labelRadios.checked').parent().find('input').attr('id'),
            value = $('#code_search_value').val(),
            page = 0,
            url = baseUrl + '/registworkslip/search/index',
            param = {
                condition: condition,
                condition_1: m05_value,
                value: value,
                page: page
            };
        var request = $.ajax({
            type: 'post',
            data: param,
            url: url
        });
        var response = request.done(function (data) {
            $('#modalCodeSearch .paging a').parent('li').removeClass('active');
            $('#modalCodeSearch .paging a[data-page=' + page + ']').parent('li').addClass('active');
            var tr = '<tr><th>商品コード</th><th>荷姿コード</th><th>品名</th></tr>';
            $.each(data.product, function (key, value) {
                tr += '<tr data-item="' + value.M05_COM_CD + value.M05_NST_CD + ',' + parseInt(value.M05_COM_CD) + '">'
                    + '<td>' + value.M05_COM_CD + '</td>'
                    + '<td>' + value.M05_NST_CD + '</td>'
                    + '<td>' + ((value.M05_COM_NAMEN == null) ? '' : value.M05_COM_NAMEN) + '</td>'
                    + '<input type="hidden" id="name' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_COM_NAMEN + '">'
                    + '<input type="hidden" id="price' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + ((value.M05_LIST_PRICE == null) ? '' : value.M05_LIST_PRICE) + '">'
                    + '<input type="hidden" id="kind' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_KIND_COM_NO + '">'
                    + '<input type="hidden" id="large' + value.M05_COM_CD + value.M05_NST_CD + '" value="' + value.M05_LARGE_COM_NO + '">'
                    + '<input type="hidden" value="' + value.M05_COM_CD + '" id="comcd' + value.M05_COM_CD + value.M05_NST_CD + '" />'
                    + '<input type="hidden" value="' + value.M05_NST_CD + '" id="nstcd' + value.M05_COM_CD + value.M05_NST_CD + '" />'
                    + '</tr>'

            });
            $('#modalCodeSearch .tableList tbody').html(tr);
            $('nav.paging').html(html_paging(data.count, page, 10));
            $('#condition').val(condition);
        });
    };

    var click_search = function () {
        $('#code_search_btn').on('click', function () {
            search();
            return false;
        });

        $('#codeSearchForm').on('submit', function () {
            search();
            return false;
        });
    };

    var click_condition_search = function () {
        $(".labelChecksSearch").click(function () {
            var id = $(this).attr('for');
            if ($(this).hasClass('checked')) {
                $(this).removeClass('checked');
                $("#" + id).removeAttr('checked');
            }
            else {
                $(this).addClass('checked');
                $("#" + id).attr('checked', 'checked');
            }

            $(".labelChecksSearch").each(function () {
                var id_each = $(this).attr('for');
                if (id_each != id) {
                    $(this).removeClass('checked');
                    $("#" + id_each).removeAttr('checked');
                }
            });

            search();
        });
    };

    var reset_warranty = function () {
        $("#warrantyBox select.select_product, #warrantyBox input").val('');
        $("#warrantyBox select.select_product_second").html('');
        $("#warrantyBox .number_product_p").html('');
        $("#checkWarranty_label").removeClass('checked');
        $("#checkWarranty").prop('checked', false);
        $("#text_warranty_no").html('').addClass('toggleWarranty');
        $("#text_warranty_date").addClass('toggleWarranty');
        $("#text_warranty_period").addClass('toggleWarranty');
        $('#M09_WARRANTY_NO').attr('disabled', true);
        $('#warranty_period').attr('disabled', true);
        $('#M09_INP_DATE').attr('disabled', true);
    };

    var click_select_product = function () {
        $('#modalCodeSearch table.tableList').on('click', 'tr[data-item]', function () {
            var index = $('#modalCodeSearch #index_modalCodeSearch').val(),
                type = $('#modalCodeSearch #type_modalCodeSearch').val(),
                itemCode = $(this).attr('data-item').split(','),
                m05ComCD = itemCode[0],
                comCd = itemCode[1],
                current_commodityBox = $('.commodityBox[attr-index=' + index + '][type=' + type + ']');

            //text
            current_commodityBox.find("#txtValueName" + index).html($("#name" + m05ComCD).val());
            var price = $("#price" + m05ComCD).val() == null || $("#price" + m05ComCD).val() == 'null' ? '' : $("#price" + m05ComCD).val();
            current_commodityBox.find("#txtValuePrice" + index).html(price);

            //hidden input
            current_commodityBox.find('#nstcd' + index).val($("#nstcd" + m05ComCD).val());
            current_commodityBox.find('#comcd' + index).val($("#comcd" + m05ComCD).val());
            current_commodityBox.find("#code_search" + index).val(m05ComCD);
            current_commodityBox.find("#code_search" + index).attr('title1', m05ComCD);
            current_commodityBox.find("#list" + index).val($("#name" + m05ComCD).val());

            if (type == 'product') {
                var count = 0;
                $('.commodityBox.on[type=' + type + ']').each(function () {
                    var value = parseInt($(this).find('.D05_COM_CD').val());
                    if (value > 41999 && value < 43000) {
                        count++;
                    }
                });

                if (count == 0) {
                    reset_warranty();
                    $("#warrantyBox").removeClass('on');
                    $("#puncon_warrantyBox_Box").removeClass('show').addClass('hide');
                    $("#puncon_warrantyBox").removeClass('checked');
                    $("#puncon").prop("checked", false);
                }
                else {
                    $("#puncon_warrantyBox_Box").removeClass('hide').addClass('show');
                    if ($("#puncon").is(':checked')) {
                        $("#warrantyBox").addClass('on');
                    }
                    else {
                        $("#warrantyBox").removeClass('on');
                    }
                }
            }
            $("#modalCodeSearch").modal('hide');
        });
    };

    var change_code_product = function () {
        $(".codeSearchProduct").change(function () {
            utility.zen2han(this);
            if ($(this).val() == '') {
                var tooltip = $(this).closest('.commodityBox').find('.noProduct').attr('aria-describedby');
                $('#' + tooltip).hide();
                $(this).closest('.commodityBox').find('.noProduct').attr('aria-invalid', false);
            }
            if ($(this).closest('.commodityBox').attr('type') == 'product') {
                _process_shaken_product($(this));
            }
            if ($(this).closest('.commodityBox').attr('type') == 'suggest') {
                _process_suggest_product($(this));
            }
        });
    };

    var _process_shaken_product = function (item) {
        var current_box = item.closest('.commodityBox'),
            index = item.attr('rel'),
            code = item.val(),
            length = parseInt(code.length),
            type = item.closest('.commodityBox').attr('type');
        if (code.length == 8) {
            $("#code_search" + index).val('0' + code);
            length = 9;
        }

        if (length < 9) {
            var count = 0;
            current_box.find('#nstcd' + index).val('');
            current_box.find('#comcd' + index).val('');
            current_box.find('#comseq' + index).val('');
            current_box.find('#list' + index).val('');
            current_box.find('#no_' + index).val('');
            current_box.find("#txtValueName" + index).html('');
            current_box.find("#txtValuePrice" + index).html('');
            current_box.find('#no_' + index).trigger('change');
            current_box.find('#price_' + index).val('');
            current_box.find('#total_' + index).val('');
            $('.commodityBox.on[type=' + type + ']').each(function () {
                var value = parseInt($(this).find('.D05_COM_CD').val());
                if (value > 41999 && value < 43000) {
                    count++;
                }
            });

            if (count == 0) {
                $("#warrantyBox").removeClass('on');
                reset_warranty();
                $("#puncon_warrantyBox_Box").removeClass('show').addClass('hide');
                $("#puncon_warrantyBox").removeClass('checked');
                $("#puncon").prop("checked", false);
            }
            return;
        }

        $.post(base_url + '/registworkslip/default/search',
            {
                'code': $("#code_search" + index).val()
            },
            function (data) {
                if (data == false) {
                    current_box.find("#txtValueName" + index).html('');
                    current_box.find("#txtValuePrice" + index).html('');
                    current_box.find('#nstcd' + index).val('');
                    current_box.find('#comcd' + index).val('');
                    current_box.find(item).attr('title1', '');
                }
                else {
                    current_box.find("#txtValueName" + index).html(data.M05_COM_NAMEN);
                    current_box.find("#txtValuePrice" + index).html(data.M05_LIST_PRICE);
                    current_box.find('#nstcd' + index).val(data.M05_NST_CD);
                    current_box.find('#comcd' + index).val(data.M05_COM_CD);
                    current_box.find("#list" + index).val(data.M05_COM_NAMEN);
                    current_box.find(item).attr('title1', (data.M05_COM_CD + data.M05_NST_CD));
                }

                var count = 0;
                $('.commodityBox.on').each(function () {
                    var value = parseInt($(this).find('.D05_COM_CD').val());
                    if (value > 41999 && value < 43000) {
                        count++;
                    }
                });

                if (count == 0) {
                    $("#warrantyBox").removeClass('on');
                    reset_warranty();
                    $("#warrantyBox select.select_product, #warrantyBox input").val('');
                    $("#puncon_warrantyBox_Box").removeClass('show').addClass('hide');
                    $("#puncon_warrantyBox").removeClass('checked');
                    $("#puncon").prop("checked", false);
                }
                else {
                    $("#puncon_warrantyBox_Box").removeClass('hide').addClass('show');
                    if ($("#puncon").is(':checked')) {
                        $("#warrantyBox").addClass('on');
                    }
                    else {
                        $("#warrantyBox").removeClass('on');
                    }
                }
            });
    };

    var _process_suggest_product = function (item) {
        var current_box = item.closest('.commodityBox'),
            index = item.attr('rel'),
            code = item.val(),
            length = parseInt(code.length),
            type = item.closest('.commodityBox').attr('type');
        if (code.length == 8) {
            $("#code_search" + index).val('0' + code);
            length = 9;
        }

        if (length < 9) {
            var count = 0;
            current_box.find('#nstcd' + index).val('');
            current_box.find('#comcd' + index).val('');
            current_box.find('#comseq' + index).val('');
            current_box.find('#list' + index).val('');
            current_box.find('#no_' + index).val('');
            current_box.find("#txtValueName" + index).html('');
            current_box.find("#txtValuePrice" + index).html('');
            current_box.find('#no_' + index).trigger('change');
            current_box.find('#price_' + index).val('');
            current_box.find('#total_' + index).val('');
            return;
        }

        $.post(base_url + '/registworkslip/default/search',
            {
                'code': $("#code_search" + index).val()
            },
            function (data) {
                if (data == false) {
                    current_box.find("#txtValueName" + index).html('');
                    current_box.find("#txtValuePrice" + index).html('');
                    current_box.find('#nstcd' + index).val('');
                    current_box.find('#comcd' + index).val('');
                    current_box.find(item).attr('title1', '');
                }
                else {
                    current_box.find("#txtValueName" + index).html(data.M05_COM_NAMEN);
                    current_box.find("#txtValuePrice" + index).html(data.M05_LIST_PRICE);
                    current_box.find('#nstcd' + index).val(data.M05_NST_CD);
                    current_box.find('#comcd' + index).val(data.M05_COM_CD);
                    current_box.find("#list" + index).val(data.M05_COM_NAMEN);
                    current_box.find(item).attr('title1', (data.M05_COM_CD + data.M05_NST_CD));
                }
            });
    };

    var click_checkWarranty = function () {
        $("#checkWarranty_label").click(function () {
            var warranty_no = $("#M09_WARRANTY_NO").val();
            if ($(this).hasClass('checked')) {
                $(this).removeClass('checked');
                $("#checkWarranty").prop('checked', false);
                $("#text_warranty_no").html('').addClass('toggleWarranty');
                $("#text_warranty_date").addClass('toggleWarranty');
                $("#text_warranty_period").addClass('toggleWarranty');
                $('#M09_WARRANTY_NO').attr('disabled', true);
                $('#warranty_period').attr('disabled', true);
                $('#M09_INP_DATE').attr('disabled', true);
            } else {
                $(this).addClass('checked');
                $("#checkWarranty").prop('checked', true);
                $("#text_warranty_no").html(warranty_no).removeClass('toggleWarranty');
                $("#text_warranty_date").removeClass('toggleWarranty');
                $("#text_warranty_period").removeClass('toggleWarranty');
                $('#M09_WARRANTY_NO').removeAttr('disabled');
                $('#warranty_period').removeAttr('disabled');
                $('#M09_INP_DATE').removeAttr('disabled');
            }

            if (warranty_no != '') {
                return;
            }

            $.post(base_url + '/registworkslip/default/warranty',
                {
                    'ss_cd': $("#D01_SS_CD").val()
                },
                function (data) {
                    $("#text_warranty_no").html(data.numberWarrantyNo);
                    $("#M09_WARRANTY_NO").val(data.numberWarrantyNo);
                }
            );
        });
    };

    var change_maker_warranty = function () {
        $('.select_product').change(function () {
            var formGroup = $(this).closest('div.formGroup'),
                prodSelect = formGroup.find('select[name$=_product]'),
                tire = jQuery.parseJSON($('#warranty_item').val()),
                items = tire[$(this).val()],
                number = 0,
                text = '';
            if ($(this).val()) {
                number = 1;
                text = 1;
            }
            $(this).closest('.lineBottom').find('.number_product_hidden').val(number);
            $(this).closest('.lineBottom').find('.number_product_p').html(text);
            if ($(this).val().length == 0) {
                prodSelect.html('');
                return;
            }
            var html = '';
            $.each(items, function (index, val) {
                html += '<option value="' + val + '">' + val + '</option>';
            });
            prodSelect.html(html);

            if (formGroup.find('input[name$=_size]').val().length == 0 &&
                formGroup.find('input[name$=_serial]').val().length == 0
            ) {
                prodSelect.val($("#right_front_product").val());
                formGroup.find('input[name$=_size]').val($("#right_front_size").val());
                formGroup.find('input[name$=_serial]').val($("#right_front_serial").val());
            }

            if (prodSelect.attr('data-value').length > 0) {
                prodSelect.val(prodSelect.attr('data-value'));
            }
        }).trigger('change');
    };

    var total_price_shaken = function () {
        var total = 0,
            price,
            totalHasVat;
        $('.commodityBox.on[type=product]').each(function () {
            price = parseInt($(this).find('.totalPriceProduct').val());
            total += isNaN(price) ? 0 : price;
        });
        totalHasVat = (total * (100 + parseFloat($('#vat').val()))) / 100;
        totalHasVat = Math.round(totalHasVat);
        $('#shaken_totalPrice').html(totalHasVat);
        $('#shaken_D03_SUM_KINGAKU').val(totalHasVat);
    };

    var total_price_suggest = function () {
        var total = 0,
            price,
            totalHasVat;
        $('.commodityBox.on[type=suggest]').each(function () {
            price = parseInt($(this).find('.totalPriceProduct').val());
            total += isNaN(price) ? 0 : price;
        });
        totalHasVat = (total * (100 + parseFloat($('#vat').val()))) / 100;
        totalHasVat = Math.round(totalHasVat);
        $('#suggest_totalPrice').html(totalHasVat);
        $('#suggest_D03_SUM_KINGAKU').val(totalHasVat);
    };

    var change_total_price_product = function () {
        $('.totalPriceProduct').on('change', function () {
            utility.zen2han(this);
            $(this).closest('.commodityBox').find('.priceProduct').val('');
            if ($(this).closest('.commodityBox').attr('type') == 'product') {
                total_price_shaken();
            }
            if ($(this).closest('.commodityBox').attr('type') == 'suggest') {
                total_price_suggest();
            }
        });
    };

    var change_number_price_product = function () {
        $(".priceProduct,.noProduct").change(function () {
            utility.zen2han(this);
            var parent = $(this).closest('.commodityBox'),
                type = parent.attr('type'),
                price_item = parent.find('.priceProduct'),
                no_item = parent.find('.noProduct'),
                price = isNaN(parseInt(price_item.val())) ? 0 : parseInt(price_item.val()),
                no = isNaN(parseInt(no_item.val())) ? 0 : parseFloat(no_item.val()),
                total_item = parent.find('.totalPriceProduct'),
                total = Math.round(no * price);
            total_item.val(total);
            if (type == 'product') {
                total_price_shaken();
            }
            if (type == 'suggest') {
                total_price_suggest();
            }
        });
    };

    var click_add_suggest = function () {
        $(".addSuggest").on("click", function () {
            for (var i = 1; i < 11; ++i) {
                if ($("#commodity_suggest" + i).hasClass('on') == false) {
                    $("#commodity_suggest" + i).addClass('on');
                    break;
                }
            }

            return false;
        });
    };

    var click_remove_suggest = function () {
        $(".removeSuggest").on("click", function () {
            var suggest = $(this).closest(".commodityBox");
            suggest.removeClass("on");
            suggest.find('input[type=text]').val('');
            suggest.find('input[type=hidden]').val('');
            suggest.find('.txtValue').html('');
            total_price_suggest();
            return false;
        });
    };

    var click_remove_commodity = function () {
        $(".removeCommodity").click(function () {
            var commodity = $(this).closest('.commodityBox');
            commodity.removeClass('on');
            commodity.find('input[type=text]').val('');
            commodity.find('input[type=hidden]').val('');
            commodity.find('.txtValue').html('');
            total_price_shaken();
        });
    };

    return {
        init: function () {
            click_select_product();
            removeHrefPaging();
            paging();
            search();
            click_search();
            click_condition_search();
            change_code_product();
            click_checkWarranty();
            change_maker_warranty();
            change_total_price_product();
            change_number_price_product();
            click_add_suggest();
            click_remove_suggest();
            click_remove_commodity();
        }
    }
}();

$(function () {
    $.fn.digits = function (str) {
        return this.each(function () {
            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        })
    };
    $.fn.autoKana('#autokana-name', '#D01_CUST_NAMEK', {katakana: true});
    denpyo.init();
    modal_user.init();
    modal_car.init();
    fee.init();
    comments.init();
    product.init();
});