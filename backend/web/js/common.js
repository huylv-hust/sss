


(function($) {
// checkboxデザイン変更
  //checkedだったら最初からチェックする
  $(".checks").each(function() {
    if ($(this).attr("checked") === "checked") {
      $(this).next().addClass("checked");

    }
  });
  //クリックした要素にクラス割り当てる
  $(".labelChecks,.labelSingleCheck, .labelSingleCheckBtn").click(function() {
    if ($(this).hasClass("checked")) {
      $(this)
        .removeClass("checked")
        .prevAll(".checks:first").prop("checked", false);
    } else {
      $(this)
        .addClass("checked")
        .prevAll(".checks:first").prop("checked", true);
    }
  });
  // radioデザイン変更
  var radioGroup = $(".radioGroup");
  $(".radio", radioGroup)
    //checkedだったら最初からチェックする
    .each(function() {
      if ($(this).attr("checked") === "checked") {
        $(this).next().addClass("checked");
      }
    });
  //クリックした要素にクラス割り当てる
  $(".labelRadios", radioGroup).click(function() {
    $(this).parent().parent().each(function() {
      $(".labelRadios", this).removeClass("checked");
    });
    $(this).addClass("checked");
  });

})(jQuery);

$(function() {
  // sidemenu
  $("#navSideMenu").sidr(open);
  $("#sidrClose").sidr(close);

  // accordion
  $(document).on("click", ".toggleAccordion", function() {
    var acc = $(this).parents(".accordion");
    if (acc.hasClass("accClose")) {
      acc.removeClass("accClose");
      acc.addClass("accOpen");
      $(this).text("削除");
    } else {
      acc.removeClass("accOpen");
      acc.addClass("accClose");
      $(this).text("追加");
    }

    return false;
  });

  //（デモ用の仮script ここから）
  // 商品削除ボタン 後で直す

  $(".removeCommodity").on("click", function() {
    var commodity = $(this).parents(".commodityBox");
    commodity.removeClass("on");
    return false;
  });
  $("#commodity2 .removeCommodity").on("click", function() {
    //$("#warrantyBox").hide();
    return false;
  });
  // 商品追加ボタン 後で直す
  $(".addCommodity").on("click", function() {
    for(var i = 1; i < 11; ++i)
    {
        if($("#commodity" + i).hasClass('on') == false)
        {
            $("#commodity" + i).addClass('on');
            break;
        }
    }

    return false;
  });
  $("#checkWarranty").on("change", function(){
    if($(this).attr("checked") === "checked"){
      $(".toggleWarranty").show();
    }else{
      $(".toggleWarranty").hide();
    }
  });
  //（デモ用の仮script ここまで）

 //validate('#frmLogin','.btnLogin');

 //validate('#frmCardNumber','#btnCardNumberVerify');

});

function  validate(form,btnObj){

  var isValid = true;

  var ssidValid = 'SSIDを入力してください';
  var passValid = 'パスワードを入力してください';
  var oldCardNumber = '旧Usappyカード番号を入力してください';
  var newCardNumber = '新Usappyカード番号を入力してください';

  if(btnObj != '' || form != '' ){

	  //validate login page
	if(form == '#frmLogin'){

		$(btnObj).on('click',function(e){

			$(form).find('input[valid=true]').each(function(index, element) {

				var inputType = $(this).attr('name');

				 switch(inputType){
					case 'ssid':
						if($(this).val() == ''){
							$(this).addClass('invalid');
							showToolTip($(this).parent(),ssidValid);
							e.preventDefault();
							isValid = false;
						}
					 break;
					case 'password':
					    if($(this).val() == ''){
						 	$(this).addClass('invalid');
							showToolTip($(this).parent(),passValid);
							e.preventDefault();
							isValid = false;
						}
					break;
				  }
           });

         });

		 $(form).find('input[valid=true]').each(function(index, element) {
			 var inputType = $(this).attr('name');
			 $(this).on('focusout',function(){
				 switch(inputType){
					case 'ssid':
						 if($(this).val() == ''){
							$(this).addClass('invalid');
							 hideToolTip($(this).parent());
							 showToolTip($(this).parent(),ssidValid);
							 isValid  = false;
						 }else{
							 $(this).removeClass('invalid');
							 hideToolTip($(this).parent());
						 }
					 break;

					case 'password':
						if($(this).val() == ''){
						   $(this).addClass('invalid');
						   hideToolTip($(this).parent());
						   showToolTip($(this).parent(),passValid);
						   isValid  = false;
						}else{
							 $(this).removeClass('invalid');
							 hideToolTip($(this).parent());
						}
					 break;
				  }
			 });

		   });

	   }
  };

  //validate form number change
  if(form == '#frmCardNumber'){

	  //button onclick
	  $(btnObj).on('click',function(e){

			$(form).find('input[valid=true]').each(function(index, element) {

				var inputType = $(this).attr('name');

				 switch(inputType){

					 case 'oldCardNumber':
					    if($(this).val() == ''){
						 	$(this).addClass('invalid');
							showToolTip($(this).parent(),oldCardNumber);
							e.preventDefault();
							isValid = false;
						}
					break;

					case 'newCardNumber':
					    if($(this).val() == ''){
						 	$(this).addClass('invalid');
							showToolTip($(this).parent(),newCardNumber);
							e.preventDefault();
							isValid = false;
						}
					break;

				 }
           });

        });

		//focus out
		$(form).find('input[valid=true]').each(function(index, element) {
			 var inputType = $(this).attr('name');
			 $(this).on('focusout',function(){
				 switch(inputType){
					 case 'oldCardNumber':
						if($(this).val() == ''){
						   $(this).addClass('invalid');
						   hideToolTip($(this).parent());
						   showToolTip($(this).parent(),oldCardNumber);
						   isValid  = false;
						}else{
							 $(this).removeClass('invalid');
							 hideToolTip($(this).parent());
						}
					 break;

					 case 'newCardNumber':
						if($(this).val() == ''){
						   $(this).addClass('invalid');
						   hideToolTip($(this).parent());
						   showToolTip($(this).parent(),newCardNumber);
						   isValid  = false;
						}else{
							 $(this).removeClass('invalid');
							 hideToolTip($(this).parent());
						}
					 break;
			     }

			 });
		});
  }

  return isValid;
}

var hideToolTip = function(parent){
  $(parent).find('.toolTipMsg').hide();
}

var showToolTip = function(parent,msg){
  $(parent).find('.toolTipMsg').show();
  $(parent).find('.tooltipInner').html(msg);
}


// プライバシーポリシーに同意する
var agreeForm = function() {
  var status = $("#agreeCheck");
  var btn = $("#agreeFormBtn");
  if (status.attr("checked")) {
    btn.removeClass("disabled");
    btn.removeAttr("disabled");
  } else {
    btn.addClass("disabled");
    btn.attr("disabled", "disabled");
  }
};


// コード一覧から選択ボタン
function codeSearch(){
    $("#modalCodeSearch input[type=radio]").attr('checked', false);
    $("#modalCodeSearch").modal();
}

// 作業確定ボタン
function fncMakeWarranty(){
  $("#modalWorkSlipComp").modal("hide");
  $("#modalMakeWarranty").modal("show");
}

jQuery.validator.addMethod("hiragana", function(value,element) {
    if(value == '') return true;
    if(value.match(/^[あ-ん]+$/)){
        return true;
    }
    return false;
});

jQuery.validator.addMethod("date_format", function(value,element) {
    var list_month_30 = [4,6,9,11],
        list_month_31 = [1,3,5,7,8,10,12],
        leap_year = false;
    if(value == '') return true;
    if(value.match(/^\d{8}$/))
    {
        var year = parseInt(value.substr(0,4)),
            month = parseInt(value.substr(4,2)),
            day = parseInt(value.substr(6,2));
        if((year % 100 != 0 && year % 4 == 0) || year % 400 == 0) leap_year = true;
        if(((month < 1 || month > 12) || day < 1 || year < 1)
            || (leap_year == true && month == 2 && day > 29)
            || (leap_year == false && month == 2 && day > 28)
            || ($.inArray(month,list_month_30) >=0 && day > 30)
            || ($.inArray(month,list_month_31) >=0 && day > 31))
            return false;

        return true;
    }
    return false;
});

jQuery.validator.addMethod("date_year_month", function(value,element) {
    if(value == '') return true;
    if(value.match(/^\d{6}$/))
    {
        var year = parseInt(value.substr(0,4)),
            month = parseInt(value.substr(4,2));
        if(((month < 1 || month > 12) || year < 1))
            return false;

        return true;
    }
    return false;
});

jQuery.validator.addMethod("check_latin", function(value,element) {
    if(value == '') return true;
    if(value.match(/^[a-zA-Z0-9]+$/)) {
        return true;
    }
    return false;
},'7桁の半角英数字以内で入力してください');

$(function()
{
    $.datepicker.regional["ja"] = {
        clearText: "クリア", clearStatus: "日付をクリアします",
        closeText: "閉じる", closeStatus: "変更せずに閉じます",
        prevText: "&#x3c;前", prevStatus: "前月を表示します",
        prevBigText: "&#x3c;&#x3c;", prevBigStatus: "前年を表示します",
        nextText: "次&#x3e;", nextStatus: "翌月を表示します",
        nextBigText: "&#x3e;&#x3e;", nextBigStatus: "翌年を表示します",
        currentText: "今日", currentStatus: "今月を表示します",
        monthNames: [
            "1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"
        ],
        monthNamesShort: [
            "1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"
        ],
        monthStatus: "表示する月を変更します", yearStatus: "表示する年を変更します",
        weekHeader: "週", weekStatus: "暦週で第何週目かを表します",
        dayNames: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
        dayNamesShort: ["日","月","火","水","木","金","土"],
        dayNamesMin: ["日","月","火","水","木","金","土"],
        dayStatus: "週の始まりをDDにします", dateStatus: "Md日(D)",
        dateFormat: "yymmdd", firstDay: 0,
        initStatus: "日付を選択します", isRTL: false,
        showMonthAfterYear: true};
    $.datepicker.setDefaults($.datepicker.regional["ja"]);

    $('.dateform').datepicker({
		changeMonth: true,
		changeYear: true
	});
    $('.ymform').ympicker({
        closeText: '閉じる',
        prevText: '&#x3c;前',
        nextText: '次&#x3e;',
        currentText: '今日',
        monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
        monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
        dateFormat: 'yymm',
        yearSuffix: '年',
		onSelect : function()
		{
			$(this).trigger('change');
		}
	});

    $('[data-alert]').on('click', function(){
        alert($(this).attr('data-alert'));
        return false;
    });

    $('.number_han').on('change',function(){
        utility.zen2han(this);
    });

});
