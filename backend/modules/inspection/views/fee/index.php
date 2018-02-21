<main id="contents">
    <section class="readme">
        <p> 車検料金の設定メニューです。</p>
    </section>
    <article class="container">
        <p class="mt50 centering">項目を選択してください。</p>
        <nav class="navBasic">
            <ul class="ulNavMainte">
                <li><a class="btnNavBasic iconMoney" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee-basic/list">基本料金</a></li>
                <li><a class="btnNavBasic iconMoney" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee-registration/list">法定料金</a></li>
                <li><a class="btnNavBasic iconMoney" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/discount/list">割引・割増料金</a></li>
            </ul>
        </nav>
    </article>
</main>
<footer id="footer">
    <div class="toolbar"><a class="btnBack" href="<?php echo \yii\helpers\BaseUrl::base(true) . '/maintenance'; ?>">設定メニューに戻る</a></div>
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
                        <li><a href="#" class="btnMemberUsappy" onclick="fncAuth('usappy');">Usappy会員</a></li>
                        <li><a href="#" class="btnMemberReceivable" onclick="fncAuth('receivable');">掛カード顧客</a></li>
                        <li><a href="#" value="" class="btnMemberEtc" id="moveTypeEtc">非会員</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /modal 会員選択 -->
<!-- modal Usappy会員認証 -->
<?php
echo \common\widgets\Cardmember::widget();
?>
<!-- /modal Usappy会員認証 -->
<!-- modal 掛会員認証 -->
<?php
echo \common\widgets\Card::widget();
?>
<!-- /modal 掛会員認証 -->

<script type="text/javascript" src="js/module/top.js?052801"></script>
