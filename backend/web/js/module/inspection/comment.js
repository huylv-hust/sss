var comment = function () {

    var click_add_title_comment = function () {
        $(document).on('click', '.add_title', function () {
            var last = $(this).closest('.vmiddle:last'),
                length = $('.vmiddle').length;
            if (length < 5) {
                var next_index = length == 0 ? 0 : parseInt(last.attr('attr-index')) + 1;
                var html = '<div class="form-inline vmiddle gray" attr-index="'+ next_index+ '">'
                        + '<label class="col-sm-2 control-label">分類名</label>'
                        + '<div>'
                        + '<input type="text" class="form-control booking-input add_parent_input" name="NAME[]" maxlength="60">'
                        + ' <button class="btn btn-sm btn-danger remove_title" type="button"><i class="glyphicon glyphicon-minus"></i></button>'
                        + '</div>'
                        + '<div class="col-sm-3 wrap_add_parent_comment">'
                        + '<button class="btn btn-sm btn-success add_title" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
                        + '</div>'
                        + '</div>';
                $('.wrap_add_parent_comment').remove();
                $(html).appendTo($('#form_parent_wrap'));
                validate_add();
            }
        });
    };

    var click_remove_title_comment = function () {
        $(document).on('click','.remove_title', function(){
            var length = $('.vmiddle').length;
            if (length > 1) {
                var html = '<div class="col-sm-3 wrap_add_parent_comment">'
                    + '<button class="btn btn-sm btn-success add_title" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
                    + '</div>';
                $(this).closest('.vmiddle').remove();
                $('.wrap_add_parent_comment').remove();
                $(html).appendTo($('.vmiddle:last'));
                validate_add();
            }
        });
    };

    var click_add_comment = function () {
        $('.add_comment').on('click',function () {
            var content = $(this).closest('.form-inline').find('.content');
            var last = $(this).closest('.form-inline').find('.content:last');
            var length = content.length;
            if (length < 5) {
                var next_index = length == 0 ? 0 : parseInt(last.attr('attr-index')) + 1;
                var html = '<div class="content" attr-index="'+ next_index+ '">'
                    + '<input type="hidden" value="" class="comment_id" name="comment['+ next_index+ '][ID]">'
                    + '<input type="text" class="form-control booking-input textForm" name="comment['+ next_index +'][CONTENT]"  maxlength="450" style="width: 88%">'
                    + ' <button class="btn btn-sm btn-danger remove_comment" type="button"><i class="glyphicon glyphicon-minus"></i></button>'
                    + '</div>';
                $(html).appendTo($(this).closest('.form-inline').find('.title'));
            }
        });
    };

    var click_remove_comment = function () {
        $(document).on('click','.remove_comment', function(){
            /*remove comment*/
            var id = $(this).closest('.content').find('.comment_id').val();
            var val = $(this).closest('#form_edit_comment').find('.comment_remove').val();
            if (id) {
                val = val + ',' + id;
                $(this).closest('#form_edit_comment').find('.comment_remove').val(val);
            }

            var length = $(this).closest('.title').find('.content').length;
            $(this).closest('.content').remove();
        });
    };

    var remove_parent_comment = function () {
        $('a.btn_remove').on('click', function(){
            $('#parent_comment_id_hidden').val($(this).attr('attr-id'));
            $('#modalRemoveConfirm').modal();
        });
    };

    var validate_add = function () {
        var validator = $('#form_comment').validate({});
        if($('.add_parent_input').length) {
            $('.add_parent_input:first').rules("add", {
                required: function() {
                    var flag = true;
                    var $nonempty = $('.add_parent_input').filter(function() {
                        if ($(this).val() != '') {
                            flag = false;
                            return flag;
                        }
                    });

                    return flag;
                },
                messages: {
                    required:'分類名を入力してください'
                }
            });
        }
    };

    var validate_edit = function () {
        var validator = $('#form_edit_comment').validate({
            rules: {
                NAME: {
                    required: true
                }
            },
            messages: {
                NAME: {
                    required: '分類名を入力してください'
                }
            }
        });
    };

    return {
        init:function () {
            click_add_title_comment();
            click_remove_title_comment();
            click_add_comment();
            click_remove_comment();
            remove_parent_comment();
            validate_add();
            validate_edit();
        }
    }
}();

$(function () {
    comment.init();
});