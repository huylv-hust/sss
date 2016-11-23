<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<header id="header"><!-- <a href="#side_menu" id="navSideMenu">Side Menu</a> -->
    <h1 class="titlePage">
        パンク保証データダウンロード
    </h1>

</header>
<main id="contents">
    <div class="container">

        <?php
        $form = ActiveForm::begin([
            'id' => 'frmLogin',
            'options' => ['name' => 'frmLogin'],
        ])
        ?>
        <?php
        if(Yii::$app->session->hasFlash('error'))
        {
            ?>
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php
                echo Yii::$app->session->getFlash('error');
                ?>
            </div>
        <?php
        }
        ?>
            <div class="frmContent pt100">
                <div class="row" >
                    <div class="cell bgGray frmLabel">
                        <label for="ssid">ID</label>
                    </div>
                    <div class="cell bgGrayTrans"> <span class="toolTipMsg"> <span class="tooltipArrow"></span> <span class="tooltipInner"></span> </span>
                        <?= Html::input('text', 'ssid', Yii::$app->request->post('ssid'), ['class' => 'borderGreen borderRadius','id' => 'form-ssid']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="cell bgGray frmLabel">
                        <label for="password">バスワード</label>
                    </div>
                    <div class="cell bgGrayTrans"> <span class="toolTipMsg"> <span class="tooltipArrow"></span> <span class="tooltipInner"></span> </span>
                        <?= Html::input('password', 'password', '', ['class' => 'borderGreen borderRadius', 'id' => 'form-password']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="cell"></div>
                    <div class="cell">
                        <?= Html::submitButton('ログイン', ['class' => 'btnLogin bgGreen borderRadius', 'name' => 'login-button']) ?>

                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</main>
<script type="text/javascript" src="<?php echo \yii\helpers\BaseUrl::base(true); ?>/js/module/login.js"></script>
<footer id="footer">
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
