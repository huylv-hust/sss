<!-- modal Usappy会員認証 -->
<div class="modal fade" id="modalAuthUsappy">
	<div class="modal-dialog widthS">
        <form id="card_member_usappy">
            <div class="modal-content">
                <input type="hidden" class="card_url_redirect" value="">
                <input type="hidden" class="card_type_redirect" value="">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title pull-left">Usappy会員認証</h4>
                    <div class="pull-right"><a href="#" class="btnSubmit" id="moveTypeUsappy">次へ</a>　</div>
                </div>
                <div class="modal-body">
                    <div class="box-alert">

                    </div>
                    <p class="note">項目を入力してください。<span class="must">*</span>は必須入力項目です。<br>
                        ﻿生年月日・車番・氏名カナ・電話番号のいずれか１つは必須入力です。</p>
                    <div class="frmContent">

                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="cardType">カード番号<span class="must">*</span></label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="card_number" id="form_card_number" type="text" valid="true" maxlength="16">
                            </div>
                        </div>
                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="birthday">生年月日</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="member_birthday" id="form_member_birthday" type="text" style="width:16em;" maxlength="8">
                            </div>
                        </div>

                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="car_no">車番</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="license_plates" id="form_license_plates" type="text" style="width:10em;" maxlength="4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="name_kana">氏名カナ</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="member_kaiinKana" id="form_member_kaiinKana" type="text" style="width:16em;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="tel_no">電話番号</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="member_tel" id="form_member_tel" type="text" style="width:16em;" valid="true" maxlength="11">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
	</div>
</div>
<!-- /modal Usappy会員認証 -->
