<main id="contents">
    <section class="readme">
        <h2 class="titleContent">新カード入力</h2>
    </section>
    <article class="container">
        <?php
        if (Yii::$app->session->hasFlash('error')) {
            ?>
            <div class="alert alert-warning">
                <?php
                echo Yii::$app->session->getFlash('error');
                ?>
            </div>
        <?php
        }
        ?>
        <p class="note">新しいカード番号を入力して、「確認」ボタンを押してください。<span class="must">*</span>は必須入力項目です。<br>
            カード番号の変更をやめる場合は、「戻る」ボタンを押してください。</p>
        <div class="breadcrumb">
            <ul>
                <li class="active">新カード入力</li>
                <li>入力内容確認</li>
                <li>変更完了</li>
            </ul>
        </div>
        <section>
            <?php
            $form = \yii\widgets\ActiveForm::begin([
                'id' => 'usappynumberchange',
                'options' => ['name' => 'frmLogin'],
                'action'  => 'usappy-number-change-confirm'
            ])
            ?>
                <div id="frmCardNumber">
                    <div class="frmContent">
                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label>会員氏名</label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <label><?php echo $cus_info['member_kaiinName']; ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="oldcardNumber">旧Usappyカード番号 <span class="must">*</span></label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <?= \yii\helpers\Html::input('text', 'oldCardNumber', Yii::$app->request->post('oldCardNumber'), ['class' => 'borderGreen borderRadius','id' => 'form_oldCardNumber']) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="cell bgGray frmLabel">
                                <label for="newCardNumber">新Usappyカード番号 <span class="must">*</span></label>
                            </div>
                            <div class="cell bgGrayTrans">
                                <?= \yii\helpers\Html::input('text', 'newCardNumber', Yii::$app->request->post('newCardNumber'), ['class' => 'borderGreen borderRadius','id' => 'form_newCardNumber']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar">
        <a href="<?php echo \yii\helpers\BaseUrl::base(true); ?>/menu" class="btnBack">メニューに戻る</a>
        <a href="#" id="btnCardNumberVerify" class="btnNext">確認</a>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
<script type="text/javascript" src="js/module/usappynumberchange.js"></script>
<script type="text/javascript">
    $(function(){
        history.pushState(null, null, 'usappy-number-change');
        window.addEventListener('popstate', function(event) {
            history.pushState(null, null, 'usappy-number-change');
        });
    });
</script>
