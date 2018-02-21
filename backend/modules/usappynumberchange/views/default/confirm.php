<main id="contents">
    <section class="readme">
        <h2 class="titleContent">入力内容確認</h2>
    </section>
    <article class="container">
        <p class="note">下記の内容でUsappyカード番号を変更します。<br>
            内容に間違いがない場合は、「変更」ボタンを押してください。<br>
            訂正する場合は、「戻る」ボタンを押してください。</p>
        <div class="breadcrumb">
            <ul>
                <li>新カード入力</li>
                <li class="active">入力内容確認</li>
                <li>変更完了</li>
            </ul>
        </div>
        <section>
            <div id="frmCardConfirm">
                <div class="frmContent">
                    <div class="row">
                        <div class="cell bgGray frmLabel">
                            <label>会員氏名</label>
                        </div>
                        <div class="cell bgGrayTrans">
                            <label><?php echo $data['memberKaiinName']; ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cell bgGray frmLabel">
                            <label>旧Usappyカード番号</label>
                        </div>
                        <div class="cell bgGrayTrans">
                            <label>****_****_****-<?php echo substr($data['oldCardNumber'], -4); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cell bgGray frmLabel">
                            <label>新Usappyカード番号</label>
                        </div>
                        <div class="cell bgGrayTrans">
                            <label>****_****_****-<?php echo substr($data['newCardNumber'], -4); ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
    <?php
    $form = \yii\widgets\ActiveForm::begin([
        'id' => 'usappy_number_change_confirm',
        'options' => ['name' => 'frmLogin'],
        'action'  => 'usappy-number-change-complete'
    ])
    ?>
    <?= \yii\helpers\Html::input('hidden', 'kaiinCd', isset($data) ? $data['kaiinCd'] : '', []) ?>
    <?= \yii\helpers\Html::input('hidden', 'memberKaiinName', isset($data) ? $data['memberKaiinName'] : '', []) ?>
    <?= \yii\helpers\Html::input('hidden', 'oldCardNumber', isset($data) ? $data['oldCardNumber'] : '', []) ?>
    <?= \yii\helpers\Html::input('hidden', 'newCardNumber', isset($data) ? $data['newCardNumber'] : '', []) ?>
    <?=  \yii\helpers\Html::input('hidden', 'infoCard', isset($data) ? $data['infoCard'] : '', []) ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
</main>
<footer id="footer">
    <div class="toolbar">
        <a href="#" id="btnBackCardNumberConfirm"  class="btnBack">戻る</a>
        <a href="#" id="btnCardNumberConfirm" class="btnNext">変更</a>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>

<script type="text/javascript" src="js/module/usappynumberchange.js"></script>
<script type="text/javascript">
    $(function(){
        history.pushState(null, null, 'usappy-number-change-confirm');
        window.addEventListener('popstate', function(event) {
            history.pushState(null, null, 'usappy-number-change-confirm');
        });
    });
</script>