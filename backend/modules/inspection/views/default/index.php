<main id="contents">
    <section class="readme">
        <p>車検伝票システムの設定メニューです。</p>
    </section>
    <article class="container">
        <p class="mt50 centering">項目を選択してください。</p>
        <nav class="navBasic">
            <ul class="ulNavMainte">
                <li><a href="javascript:void(0)" id="selectTypeMember" class="btnNavBasic iconOldCar">車検</a></li>
                <li><a class="btnNavBasic iconList" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/shaken/denpyo/list">車検履歴</a></li>
            </ul>
        </nav>
    </article>
</main>
<footer id="footer">
    <div class="toolbar"><a class="btnBack" href="<?php echo \yii\helpers\BaseUrl::base(true)?>">メニューに戻る</a></div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>

<!-- modal 会員選択 -->
<div class="modal fade" id="modalSelectMember">
    <div class="modal-dialog widthS">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">会員種別選択</h4>
            </div>
            <div class="modal-body">
                <p class="note">会員種別を選択してください。</p>
                <nav class="navAccountType clearfix">
                    <ul class="listNavAccountType">
                        <li><a href="javascript:void(0)" class="btnMemberUsappy" id="btnMemberUsappy">Usappy会員</a></li>
                        <li><a href="javascript:void(0)" class="btnMemberReceivable" id="btnOnlyCard">掛カード顧客</a></li>
                        <li><a href="javascript:void(0)" class="btnMemberEtc" id="btnGuest">非会員</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TYPE 1 (MEMBER) -->
<div class="modal fade" id="modalAuthUsappy">
    <div class="modal-dialog widthS">
        <form id="card_member_usappy">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title pull-left">Usappy会員認証</h4>
                    <div class="pull-right"><a href="javascript:void(0)" class="btnSubmit" id="moveTypeUsappy">次へ</a>　</div>
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
<!-- END MODAL TYPE 1 (MEMBER) -->


<!-- MODAL TYPE 2 (ONLY CARD) -->
<div class="modal fade" id="modalAuthReceivable">
    <form id="card_usappy">
        <div class="modal-dialog widthS">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title pull-left">掛カード会員</h4>
                    <div class="pull-right"><a href="#" class="btnSubmit" id="moveTypeReceivable">次へ</a>　</div>
                </div>
                <div class="modal-body">
                    <div class="box-alert">

                    </div>
                    <p class="note">掛カード番号を入力し、「次へ」ボタンを押してください。</p>
                    <div class="frmContent">
                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="cardType">掛カード番号</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <input class="borderGreen borderRadius" name="card_number_auth" id="form_card_number_auth" type="text" maxlength="16">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END MODAL TYPE 2 (ONLY CARD) -->


<script type="text/javascript" src="js/module/inspection/default.js?052801"></script>
