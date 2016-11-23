var regist_work = function () {

    var removeHrefPaging = function () {
        $('#modalCodeSearch .paging a').attr('href', 'javascript:void(0)');
    };

    var paging = function () {
        $('#modalCodeSearch .paging').on('click', 'a', function () {
            var m05_value = '';
            for(var i = 1; i < 9; ++i){
                if($("#labelSearch_M05_KIND_DM_NO" + i).hasClass('checked'))
                {
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

    var search = function () {
            var m05_value = '';
            for(var i = 1; i < 9; ++i){
                if($("#labelSearch_M05_KIND_DM_NO" + i).hasClass('checked'))
                {
                        m05_value = i + ',';
                        break;
                }
            }
            var condition = $('#modalCodeSearch .labelRadios.checked').parent().find('input').attr('id'),
                value = $('#code_search_value').val(),
                page = 0,
                url = baseUrl + '/registworkslip/search/index',
                param = {
                    condition: condition,
                    condition_1:m05_value,
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
    var clickBtn = function ()
    {
        $('#code_search_btn').on('click', function ()
        {
            search();
            return false;
        });

        $('#codeSearchForm').on('submit', function()
        {
            search();
            return false;
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

    var validate_car = function () {
        jQuery.validator.addMethod("car_no", function (value, element) {
            if (value == '0000') return false;
            return true;
        });
        $('#modal_car').validate({});

        $('.D02_SHONENDO_YM').each(function () {
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

        $('.D02_JIKAI_SHAKEN_YM').each(function () {
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

        $('.D02_METER_KM').each(function () {
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

        $('.D02_RIKUUN_NAMEN').each(function () {
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

        $('.D02_CAR_ID').each(function () {
            var rel = $(this).parents('section').attr('rel');
            $(this).rules("add", {
                required: true,
                minlength: 3,
                messages: {
                    required: function () {
                        return rel + '台目の分類コードを入力してください';
                    },
                    minlength: rel + '台目の分類コードは3文字の数字で入力してください'
                }
            });
        });

        // $('.D02_HIRA').each(function () {
        //     var rel = $(this).parents('section').attr('rel');
        //     $(this).rules("add", {
        //         required: true,
        //         hiragana: true,
        //         messages: {
        //             required: function () {
        //                 return rel + '台目のひらがなを入力してください';
        //             },
        //             hiragana: function () {
        //                 return rel + '台目のひらがなはひらがなで入力してください';
        //             }
        //         }
        //     });
        // });

        $('.D02_CAR_NO').each(function () {
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

        $('.D02_CAR_NAMEN_OTHER').each(function () {
            var rel = $(this).closest('section').attr('rel');
            $(this).rules("add", {
                required: function() {
                    if($('#modal_car section[rel=' + rel + '] #D02_MAKER_CD option:selected').val() == -111) {
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

        $('.D02_MAKER_CD').each(function () {
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

        $('.D02_MODEL_CD').each(function () {
            var rel = $(this).closest('section').attr('rel');
            $(this).rules("add", {
                required: function() {
                    if($('#modal_car section[rel=' + rel + '] #D02_MAKER_CD option:selected').val() == -111) {
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

    var validate_workslip = function () {
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
            if (!$(element).hasClass('no_event') && $('#check_pdf').val() == 'disabled' && parseInt(val) >= 42000 && parseInt(val) <= 42999) {
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
                D01_SS_CD: {
                    required: true,
                    digits: true,
                    minlength: 6
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
                D03_KITYOHIN:{
                    required: true
                },
                D03_SEISAN:{
                    required: true
                },
                date: {
                    date_format:true
                },
                km: {
                    min: 0
                },
                oil_check:{
                    required: false
                },
                oil_leak_check:{
                    required: false
                },
                cap_check:{
                    required: false
                },
                drain_bolt_check:{
                    required: false
                },
                tire_check:{
                    required: false
                },
                nut_check:{
                    required: false
                },
                note:{
                   required: function () {
                        if($("input[name=oil_check]:checked").val() == '0' || $("input[name=oil_leak_check]:checked").val() == '0' || $("input[name=cap_check]:checked").val() == '0' || $("input[name=drain_bolt_check]:checked").val() == '0' || $("input[name=tire_check]:checked").val() == '0' || $("input[name=nut_check]:checked").val() == '0')
                            return true;
                        return false;
                    }
                },
                right_front_size:{
                  is_size: true
                },
                left_front_size:{
                    is_size:true
                },
                right_behind_size:{
                    is_size:true
                },
                left_behind_size:{
                    is_size:true
                },
                other_a_size:{
                    is_size:true
                },
                other_b_size:{
                    is_size:true
                },
                right_front_serial:{
                    digits:true,
                    rangelength:[4,4]
                },
                left_front_serial:{
                    digits:true,
                    rangelength:[4,4]
                },
                right_behind_serial:{
                    digits:true,
                    rangelength:[4,4]
                },
                left_behind_serial:{
                    digits:true,
                    rangelength:[4,4]
                },
                other_a_serial:{
                    digits:true,
                    rangelength:[4,4]
                },
                other_b_serial:{
                    digits:true,
                    rangelength:[4,4]
                }


            },
            messages: {
                M08_NAME_MEI_M08_NAME_SEI: {
                    required: '受付担当者を選択してください'
                },
                D01_SS_CD: {
                    required: 'SSコードを入力してください',
                    digits: 'SSコードは6文字の数字で入力してください',
                    minlength: 'SSコードは6文字の数字で入力してください'
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
                D03_KITYOHIN:{
                    required: '貴重品を選択してください'
                },
                D03_SEISAN:{
                     required: '精算方法を選択してください'
                },
                date: {
                    date_format: '次回交換目安が正しくありません。'
                },
                km: {
                    min: 'kmは0桁の数字以外で入力してください。'
                },
                oil_check:{
                    required: '選択して下さい'
                },
                oil_leak_check:{
                    required: '選択して下さい'
                },
                cap_check:{
                    required: '選択して下さい'
                },
                drain_bolt_check:{
                    required: '選択して下さい'
                },
                tire_check:{
                    required: '選択して下さい'
                },
                nut_check:{
                    required: '選択して下さい'
                },
                note:{
                    required:'NG理由を記入してください'
                },
                right_front_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                left_front_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                right_behind_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                left_behind_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                other_a_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                other_b_serial:{
                    digits:'4桁の数字で入力してください',
                    rangelength:'4桁の数字で入力してください'
                },
                right_front_size:{
                    is_size:'英数字で入力してください'
                },
                left_front_size:{
                    is_size:'英数字で入力してください'
                },
                right_behind_size:{
                    is_size:'英数字で入力してください'
                },
                left_behind_size:{
                    is_size:'英数字で入力してください'
                },
                other_a_size:{
                    is_size:'英数字で入力してください'
                },
                other_b_size:{
                    is_size:'英数字で入力してください'
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
            if(value == '') return true;
            if(value.match(/^\d+(.)?(\d{1})?$/))
            {
                return true;
            }
            return false;
        });

        $('.noProduct').each(function () {
            var rel = $(this).attr('rel');
            $(this).rules("add", {
                real_1:true,
                min:0.1,
                max: 9999.9,
                required: function () {
                        if($('.codeSearchProduct[rel='+ rel + ']').val() != '')
                            return true;
                        return false;
                },
                messages: {
                    real_1: '数量が正しくありません',
                    required:'数量を入力してください',
                    min:'数量は0.1以上の値を入力してください',
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
                    totalPriceProduct: '伝票作業データを更新できませんでした',
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

    var submit_registWork = function () {
        $('#btnRegistWorkSlip').on('click', function () {
            var form = $(this).closest('form'),
                valid = form.valid();
            form.removeAttr('target').attr('action', $('#url_action').val());
            if (valid == false) {
                var tooltip_hidden = $('input[name=D03_KAKUNIN]').attr('aria-describedby');
                if (tooltip_hidden != '') {
                    $('#' + tooltip_hidden).css({"top": "-29px", "left": "-202px"});
                    $('#' + tooltip_hidden).find('.tooltip-arrow').css("left", "50%");
                }
                var tooltip_hidden = $('input[name=D03_SUM_KINGAKU]').attr('aria-describedby');
                if (tooltip_hidden != '') {
                    $('#' + tooltip_hidden).css({"top": "-30px", "left": "948px"});
                    $('#' + tooltip_hidden).find('.tooltip-arrow').css("left", "80%");
                }
                return false;
            }
            $('#modalRegistConfirm').modal();
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
        $('.codeSearchProduct').on('change', function () {
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
    };

    var click_label_modal_customer = function () {
        var agreeChecked = false;
        $('#agreeLabel').on('click', function () {
            var btn = $("#agreeFormBtn");
            if (!agreeChecked) {
                agreeChecked = true;
                btn.removeClass("disabled");
                btn.removeAttr("disabled");
            } else {
                agreeChecked = false;
                btn.addClass("disabled");
                btn.attr("disabled", "disabled");
            }
        });
    };

    var preview = function () {
        $('#preview').on('click', function () {
            $('#login_form').attr('action', baseUrl + '/preview2').attr('target', '_blank');
            $('#login_form')[0].submit();
        });
    };

    var get_addr_from_zipcode = function () {
        $('#btn_get_address').off('click').on('click', function () {
            var zipcode = $('#D01_YUBIN_BANGO').val();
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

    //select product_number
    var change_number_product = function () {
        $('.select_product').on('change', function () {
            var number = 0,
                text = '';
            if ($(this).val())  {
                number = 1;
                text = 1;
            }
            $(this).closest('.lineBottom').find('.number_product_hidden').val(number);
            $(this).closest('.lineBottom').find('.number_product_p').html(text);
        });
    };

    return {
        init: function () {
            removeHrefPaging();
            paging();
            clickBtn();
            validate_customer();
            convert_zen2han();
            validate_car();
            validate_workslip();
            submit_registWork();
            click_label_modal_customer();
            preview();
            get_addr_from_zipcode();
            change_number_product();
        }
        ,search:function(){
            search();
        }
    };
}();

$(function () {
    regist_work.init();
});