<?php
use yii\helpers\Html;
?>
<script src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/js/module/admin.js"></script>
<form method="post" id="user_form">
    <input type="hidden" id="user_action" value="<?php echo $action?>">
<main id="contents">
    <section class="readme">
    </section>
    <article class="container">
        <p class="note">
            <span class="must">*</span>は必須入力項目です。
            パスワードは変更しない場合は入力不要です。
        </p>

        <?php
        if (Yii::$app->session->hasFlash('success')) {
            ?>
            <div class="alert alert-danger">
                <?php echo Yii::$app->session->getFlash('success')?>
                <button data-dismiss="alert" class="close">×</button>
            </div>
            <?php
        }
        ?>

        <?php
        if (Yii::$app->session->hasFlash('error')) {
            ?>
            <div class="alert alert-danger">
                <?php echo Yii::$app->session->getFlash('error')?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        ?>

        <?php

        $post = Yii::$app->request->post('Tm50ssuser');
        if (!empty($post)) {
            $model-> M50_USER_ID = $post['M50_USER_ID'];
            $model->M50_USER_NAME = $post['M50_USER_NAME'];
            $model->M50_SS_CD = $post['M50_SS_CD'];
        }
        $model->M50_PASSWORD = '';
        ?>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">ログインID<span class="must">*</span></label>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M50_USER_ID', ['class' => 'textForm', 'maxlength' => 20]); ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">ユーザー名<span class="must">*</span></label>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M50_USER_NAME', ['class' => 'textForm', 'maxlength' => 128]); ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">SSコード<span class="must">*</span></label>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M50_SS_CD', ['class' => 'textForm formWidthS', 'maxlength' => 6]); ?>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">パスワード<span class="must">*</span></label>
                        <?= \yii\helpers\Html::activePasswordInput($model, 'M50_PASSWORD', ['class' => 'textForm']); ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">パスワード確認用<span class="must">*</span></label>
                        <?= \yii\helpers\Html::input('password', 'M50_PASSWORD_CONFIRM', '', ['class' => 'textForm']); ?>
                    </div>
                    <div class="formItem">
                    </div>
                </div>
            </fieldset>
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left">
            <?php
            $url = Yii::$app->session->has('url_list_user') ? Yii::$app->session->get('url_list_user') : \yii\helpers\BaseUrl::base().'/admin/users';
            ?>
            <a class="btnBack" href="<?php echo $url;?>">戻る</a>
        </div>
        <div class="toolbar-right">
            <button type="submit" class="btnSubmit">保存</button>
            <?php if($action == 'edit') {?>
                <a data-toggle="modal" class="btnTool btnYellow" href="#modalRemoveStaffConfirm">削除</a>
            <?php }?>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
</form>
<div id="modalRemoveStaffConfirm" class="modal fade in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">ログインアカウント削除</h4>
            </div>
            <div class="modal-body">
                <p class="note">ログインアカウントを削除します。よろしいですか？</p>
            </div>
            <div class="modal-footer">
                <a aria-label="Close" data-dismiss="modal" class="btnCancel flLeft" href="#">いいえ</a>
                <form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/admin/user/delete">
                    <?php
                    ?>
                    <?= \yii\helpers\Html::activeHiddenInput($model, 'M50_USER_ID'); ?>
                    <button type="submit" class="btnSubmit flRight">はい</button>
                </form>
        </div>
    </div>
</div>