
var maintenance = function(){
    /* SCREEN SEARCH */
    var process_load_ss_search = function(){
        var param = {
            branch_id: $('#selectBranch').val()
        };
        var request = $.ajax({
            type: 'post',
            data: param,
            url: baseUrl + '/maintenance/staff/getss'
        });
        var response = request.done(function(data){
            var option = '<option value=""></option>';
            $.each(data, function(key, value){
                option += '<option value="' + key + '">' + value + '</option>';
            });
            $('#selectSS').html(option);
        });
    };

    var onchange_branch_search = function(){
        $('#selectBranch').off('change').on('change',function(){
            process_load_ss_search();
        });
    };

    var search = function(){
        $('.btnSearch').off('click').on('click', function(){
            $(this).closest('form').submit();
        });
    };
    /* END SCREEN SEARCH */

    /*SCREEN CREATE*/
    var validate = function(){
        $('#staff_form').validate({
            rules: {
                'Sdptm08sagyosya[M08_HAN_CD]': {
                    required: true
                },
                'Sdptm08sagyosya[M08_SS_CD]': {
                    required: true
                },
                'Sdptm08sagyosya[M08_JYUG_CD]' : {
                    required: true,
                    digits: true
                },
                'Sdptm08sagyosya[M08_NAME_SEI]': {
                    required: true
                },
                'Sdptm08sagyosya[M08_NAME_MEI]': {
                    required: true
                },
                'Sdptm08sagyosya[M08_ORDER]': {
                    digits:true
                }
            },
            messages: {
                'Sdptm08sagyosya[M08_SS_CD]': {
                    required: 'SSを選択してください'
                },
                'Sdptm08sagyosya[M08_HAN_CD]': {
                    required: '支店を選択してください'
                },
                'Sdptm08sagyosya[M08_JYUG_CD]': {
                    required: '従業員CDを入力してください',
                    digits: '従業員CDが10桁以内の数字で入力されていない場合'
                },
                'Sdptm08sagyosya[M08_NAME_SEI]': {
                    required: '作業者名 姓を入力してください'
                },
                'Sdptm08sagyosya[M08_NAME_MEI]': {
                    required: '作業者名 姓を入力してください'
                },
                'Sdptm08sagyosya[M08_ORDER]': {
                    digits: '表示順は3文字以内の数字で入力してください'
                }
            }
        });
    };

    var convert_zen2han = function(){
        $('#sdptm08sagyosya-m08_jyug_cd , #sdptm08sagyosya-m08_order').on('change',function(){
            utility.zen2han(this);
        });
    };

    var convert_han2zen = function() {
        $('#sdptm08sagyosya-m08_name_sei , #sdptm08sagyosya-m08_name_mei').on('change',function(){
            utility.han2zen(this);
        });
    };

    var validate_commodity = function(){
        $('#update_commodity').validate({
            rules: {
                commodity : {
                    required: true
                }
            },
            messages: {
                commodity: {
                    required: 'required'
                }
            },
            submitHandler: function(form)
            {
                $('#loading').show();
                return true;
            }
        });
    };


    return {
        init:function(){
            onchange_branch_search();
            search();
            validate();
            convert_zen2han();
            convert_han2zen();
            validate_commodity();
        }
    };
}();

$(function(){
    maintenance.init();
});
