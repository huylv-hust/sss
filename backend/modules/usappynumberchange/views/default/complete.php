<main  id="contents">
    <section class="readme">
        <h2 class="titleContent">変更完了</h2>
    </section>
    <article class="container">
        <p class="note">
        <?php
            if (Yii::$app->session->hasFlash('info')) {
                echo Yii::$app->session->getFlash('info');
            }
        ?>
        </p>
        <div class="breadcrumb">
            <ul>
                <li>新カード入力</li>
                <li>入力内容確認</li>
                <li class="active">変更完了</li>
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
</main>
<footer id="footer">
    <div class="toolbar">
        <a href="<?php echo \yii\helpers\BaseUrl::base(true); ?>/menu" class="btnBack">メニュー</a></div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>