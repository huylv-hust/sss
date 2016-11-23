<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<header id="header">
    <h1 class="titlePage">
        <?php echo isset(Yii::$app->params['titlePage']) ? Yii::$app->params['titlePage'] : ''; ?>
    </h1>
</header>
<main id="contents">
    <?php
    $form = ActiveForm::begin([
        'id' => 'frmLogin',
        'options' => ['name' => 'frmLogin'],
    ])
    ?>
    <section class="readme text-center">
        SSID、パスワードを入力して、「ログイン」ボタンを押してください。
        <?= Html::submitButton('ログイン', ['class' => 'btnSubmit bgGreen borderRadius', 'name' => 'login-button']) ?>
    </section>
    <div id="login-block" class="container">
        <?php
        if(Yii::$app->session->hasFlash('success_logout'))
        {
            echo Yii::$app->session->get('success_logout');
        }
        ?>
        <?php
        if(Yii::$app->session->hasFlash('error'))
        {
            ?>
            <div class="alert alert-warning">
                <?php
                echo Yii::$app->session->getFlash('error');
                ?>
            </div>
        <?php
        }
        ?>
        <div id="login-forms">
            <div class="frmContent">
                <div class="row">
                    <div class="cell bgGray frmLabel">
                        <label for="ssid">SSID</label>
                    </div>
                    <div class="cell bgGrayTrans">
                        <?= Html::input('text', 'ssid', Yii::$app->request->post('ssid'), ['class' => 'borderGreen borderRadius','id' => 'form-ssid']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="cell bgGray frmLabel">
                        <label for="password">パスワード</label>
                    </div>
                    <div class="cell bgGrayTrans">
                        <?= Html::input('password', 'password', '', ['class' => 'borderGreen borderRadius', 'id' => 'form-password']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="cell"></div>
                    <div class="cell">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
	<script src="<?php echo \yii\helpers\BaseUrl::base(true); ?>/js/module/login.js"></script>
</main>
<footer id="footer">
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>