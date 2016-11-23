var discount = function () {

    var click_add_discount = function () {
        $(document).on('click', '.add_discount', function () {
            var discount = $(this).closest('.row').find('.discount');
            var length = discount.find('.booking-row').length;
            var parent_index = $(this).closest('.booking_title_discount').attr('attr-index');
            if (length < 5) {
                var next_index = length == 0 ? 0 : parseInt(discount.find('.booking-row:last').attr('attr-index')) + 1;
                var html = '<div class="row booking-row" attr-index="' + next_index + '">'
                    + '<div class="col-sm-6">'
                    + '<label class="col-sm-3 font-normal">説明</label>'
                    + '<input class="form-control textForm booking-input-200 description" type="text" name="discount[' + parent_index + '][' + next_index + '][DESCRIPTION]">'
                    + '</div>'
                    + '<div class="col-sm-5">'
                    + '<label class="col-sm-3 font-normal">金額</label>'
                    + '<input class="col-sm-8 form-control textForm booking-input-200 value number_han" type="text" name="discount[' + parent_index + '][' + next_index + '][VALUE]" maxlength="10">'
                    + '<span class="col-sm-1" style="">円</span>'
                    + '</div>'
                    + '<div class="col-sm-1">'
                    + '<button class="btn btn-danger btn-sm booking-btn-remove remove_discount" type="button"><i class="glyphicon glyphicon-minus"></i></button>'
                    + '</div>'
                    + '<input type="hidden" class="discount_id" name="discount[' + parent_index + '][' + next_index + '][ID]" value="0">'
                    + '</div>';
                $(html).appendTo(discount);
                validate();
            }
        });
    };

    var click_remove_discount = function () {
        $(document).on('click', '.remove_discount', function () {
            var id = $(this).closest('.booking-row').find('.discount_id').val();
            var val = $(this).closest('#fee_form').find('.discount_remove').val();
            if (id) {
                val = val + ',' + id;
                $(this).closest('#fee_form').find('.discount_remove').val(val);
            }
            $(this).closest('.booking-row').remove();
        });

    };

    var click_add_title_discount = function () {
        $('.add_title_discount').on('click', function () {
            var title_discount = $(this).closest('.form-group').find('.title_discount');
            var length = title_discount.find('.booking_title_discount').length;
            if (length < 5) {
                var next_index = length == 0 ? 0 : parseInt(title_discount.find('.booking_title_discount:last').attr('attr-index')) + 1;
                var html = '<div class="booking_title_discount" attr-index="' + next_index + '" style="margin-top: 10px">'
                    + '<div class="form-group gray booking-div-form">'
                    + '<label class="col-sm-2">割引・割増名</label>'
                    + '<div class="col-sm-9">'
                    + '<input class="form-control textForm booking-input package" type="text" name="package[' + next_index + '][NAME]" maxlength="255">'
                    + '<input type="hidden" class="package_id" name="package[' + next_index + '][ID]" value="0">'
                    + '</div>'
                    + '<div class="col-sm-1">'
                    + '<button type="button" class="btn btn-sm btn-danger remove_title_discount" style="float: right"><i class="glyphicon glyphicon-remove"></i></button>'
                    + '</div>'
                    + '</div>'
                    + '<div class="form-group booking-div-form">'
                    + '<label class="col-sm-2 font-normal">割引・割増金額</label>'
                    + '<div class="col-sm-10">'
                    + '<div class="row vmiddle">'
                    + '<div class="col-sm-11 discount">'
                    + '<div class="row booking-row" attr-index="0">'
                    + '<div class="col-sm-6">'
                    + '<label class="col-sm-3 font-normal">説明</label>'
                    + '<input class="form-control textForm booking-input-200 description" type="text" name="discount[' + next_index + '][0][DESCRIPTION]" maxlength="250">'
                    + '</div>'
                    + '<div class="col-sm-5">'
                    + '<label class="col-sm-3 font-normal">金額</label>'
                    + '<input class="col-sm-8 form-control textForm booking-input-200 value number_han" type="text" name="discount[' + next_index + '][0][VALUE]" maxlength="10">'
                    + '<span class="col-sm-1" style="">円</span>'
                    + '</div>'
                    + '<div class="col-sm-1">'
                    + '<button class="btn btn-danger btn-sm booking-btn-remove remove_discount" type="button"><i class="glyphicon glyphicon-minus"></i></button>'
                    + '</div>'
                    + '<input type="hidden" class="discount_id" name="discount[' + next_index + '][0][ID]" value="0">'
                    + '</div>'
                    + '</div>'
                    + '<div class="col-sm-1">'
                    + '<button class="btn btn-sm btn-success add_discount" type="button" style="float: right"><i class="glyphicon glyphicon-plus"></i></button>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>';
                $(html).appendTo(title_discount);
                validate();
            }
        });
    };

    var click_remove_title_discount = function () {
        $(document).on('click', '.remove_title_discount', function () {
            var id = $(this).closest('.booking_title_discount').find('.package_id').val();
            var val = $(this).closest('#fee_form').find('.package_remove').val();
            if (id) {
                val = val + ',' + id;
                $(this).closest('#fee_form').find('.package_remove').val(val);
            }
            $(this).closest('.booking_title_discount').remove();
        });
    };

    var click_remove = function () {
        $('.btn_remove').on('click', function () {
            var id = $(this).attr('attr-id');
            $('.modal').modal();
            $(this).closest('.discount').find('#remove_discount').attr('value', id);
        });
    };

    var validate = function () {
        $.validator.addMethod("numberInteger", function (value, element) {
            if (value == '' || value.match(/^-{0,1}[0-9]+$/)) {
                return true;
            }
            return false;
        });

        $('#fee_form').validate({
            rules: {
                'parent[NAME]': {
                    required: true,
                }
            },
            messages: {
                'parent[NAME]': {
                    required: '分類名を選択してください'
                }
            }
        });

        $('.package').each(function () {
            var $element = $(this);
            $(this).rules(
                "add", {
                    required: function () {
                        var description = $element.closest('.booking_title_discount').find('.description'),
                            value = $element.closest('.booking_title_discount').find('.value'),
                            length_description = 0,
                            length_value = 0;
                        description.each(function() {
                           if($(this).val() != '') {
                               length_description += 1;
                           }
                        });
                        value.each(function() {
                            if($(this).val() != '') {
                                length_value += 1;
                            }
                        });
                        if(description.length == 0) {
                            return false;
                        }
                        if (length_description > 0 || length_value > 0) {
                            return true;
                        }
                        return false;
                    },
                    messages: {
                        required: '割引名を入力してください'
                    }
                });
        });

        $('.description').each(function () {
            var $element = $(this);
            $(this).rules(
                "add", {
                    required: function () {
                        var value_closest = $element.closest('.row').find('.value'),
                            value = $element.closest('.booking_title_discount').find(".value"),
                            package_name = $element.closest('.booking_title_discount').find('.package'),
                            description = $element.closest('.booking_title_discount').find('.description'),
                            length_description = 0,
                            length_value = 0;
                        description.each(function() {
                            if($(this).val() != '') {
                                length_description += 1;
                            }
                        });
                        value.each(function() {
                            if($(this).val() != '') {
                                length_value += 1;
                            }
                        });
                        if (value_closest.val() != '' || (package_name.val() && length_description == 0 && length_value == 0)) {
                            return true;
                        }
                        return false;
                    },
                    messages: {
                        required: '説明を入力してください'
                    }
                });
        });

        $('.value').each(function () {
            var $element = $(this);
            $(this).rules(
                "add", {
                    numberInteger: true,
                    max: 9999999999,
                    required: function () {
                        var description_closest = $element.closest('.row').find('.description'),
                            package_name = $element.closest('.booking_title_discount').find('.package'),
                            description = $element.closest('.booking_title_discount').find('.description'),
                            value = $element.closest('.booking_title_discount').find(".value"),
                            length_description = 0,
                            length_value = 0;
                        description.each(function() {
                            if($(this).val() != '') {
                                length_description += 1;
                            }
                        });
                        value.each(function() {
                            if($(this).val() != '') {
                                length_value += 1;
                            }
                        });
                        if (description_closest.val() != '' || (package_name.val() && length_description == 0 && length_value == 0)) {
                            return true;
                        }
                        return false;
                    },
                    messages: {
                        required: '金額を入力してください',
                        numberInteger: '−9999999999 〜 9999999999 の数字で入力してください。',
                        max: '−9999999999 〜 9999999999 の数字で入力してください。'
                    }
                });
        });
    };

    return {
        init: function () {
            click_add_discount();
            click_remove_discount();
            click_add_title_discount();
            click_remove_title_discount();
            click_remove();
            validate();
        }
    }
}();

$(function () {
    discount.init();
});