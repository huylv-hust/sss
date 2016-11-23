var listworkslip = function(){
    /* END SCREEN SEARCH */
    var change_branch_search = function(){
        $('#selectBranch').off('change').on('change', function(){
           load_ss_search();
        });
    };

    var load_ss_search = function(){
        var param = {
            branch_id: $('#selectBranch').val()
        };
        var request = $.ajax({
            type: 'post',
            data: param,
            url: baseUrl + '/listworkslip/default/getss'
        });
        var result = request.done(function(data){
            var option = '';
            $.each(data, function(key, value){
                option += '<option value="' + key + '">' + value + '</option>';
            });
            $('#selectSS').html(option);
        });
    };

    var search = function(){
        $('.btnSearch').off('click').on('click', function(){
            $(this).closest('form').submit();
        });
    };
    /* END SCREEN SEARCH */

    /* start change status */
    var click_change_status = function(){
        $('#pdf').off('click').on('click', function(){
            change_status();
        });
    };
    var change_status = function(){
        var den_no = $('#den_no').val(),
            link = window.open('');
            link.document.title = 'View PDF';
        $.ajax({
            url: baseUrl + '/listworkslip/detail/updatestatus',
            type: 'post',
            data: {
                status: 1,
                den_no: den_no,
            },
            success: function(data){
                $('#pdf').addClass('off').attr('href', '#').off('click').on('click', function(){
                    alert('パンク保証書は印刷済みです');
                    return false;
                });
                link.location = data;
            }
        });
    };
    /* end change status */
    var validate = function(){
        $('#listworkslip').validate({
            rules: {
                'start_time' : {
                    digits: true,
                    minlength: 8
                },
                'end_time': {
                    digits: true,
                    minlength: 8
                },
                'car': {
                    digits: true,
                    minlength: 4
                }
            },
            messages: {
                'start_time': {
                    digits : '施行日（予約日）開始日は8文字の数字で入力してください',
                    minlength: '施行日（予約日）開始日は8文字の数字で入力してください'
                },
                'end_time': {
                    digits : '施行日（予約日）終了日は8文字の数字で入力してください',
                    minlength: '施行日（予約日）終了日は8文字の数字で入力してください'
                },
                'car': {
                    digits: '車両No.は4文字の数字で入力してください',
                    minlength: '車両No.は4文字の数字で入力してください'
                }
            }
        });
    };

    var convert_zen2han = function(){
        $('#start_time , #end_time, #car, #D03_POS_DEN_NO, #D01_KAKE_CARD_NO').on('change',function(){
            utility.zen2han(this);
        });
    };

    var adjust_layout = function(){
        var height = $(window).height() - 460;
        if (height > 200) {
            $('#wslist-box').css('height', height + 'px');
        }
    };

    var set_on_resize = function(){
        $(window).on('resize', function(){
            adjust_layout();
        }).trigger('resize');
    };

    return{
      init:function(){
          change_branch_search();
          search();
          validate();
          convert_zen2han();
          click_change_status();
          set_on_resize();
      }
    };
}();
$(function(){
    listworkslip.init();
});