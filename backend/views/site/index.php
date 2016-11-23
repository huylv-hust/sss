<?php $login_info = Yii::$app->session->get('login_info');?>
<main id="contents">
    <article class="container">
        <p class="centering">項目を選択してください。</p>
        <nav class="navBasic">
            <ul class="ulNavBasic">
                <li><a href="#" class="btnNavBasic iconRegistWork" onclick="fncType('regist');">作業伝票</a></li>
                <li><a href="<?php echo \yii\helpers\BaseUrl::base(true)?>/list-workslip" class="btnNavBasic iconSearchWork">作業履歴</a></li>
                <li><a href="/asbo/?sscode=<?php echo $login_info['M50_SS_CD']?>" class="btnNavReserve iconReserve">作業予約</a></li>
                <li><a href="#" class="btnNavOther iconCard" onclick="fncCard();">Usappy<br />カード番号変更</a></li>
                <li><a href="maintenance" class="btnNavBasic iconMainte">設定</a></li>
                <li><a href="shaken" class="btnNavBasic iconOldCar">車検</a></li>
                <?php if (in_array($login_info['M50_SS_CD'], Yii::$app->params['sateiss'])) { ?>
                <li><a href="/satei/?sscode=<?php echo $login_info['M50_SS_CD']?>" class="btnNavOther iconOldCar">中古車査定</a></li>
                <?php } ?>
            </ul>
        </nav>
    </article>
</main>
<footer id="footer">
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
