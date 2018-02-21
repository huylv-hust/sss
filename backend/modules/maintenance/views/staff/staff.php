<?php
    use yii\helpers\Html;
?>
<script src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/js/module/maintenance.js"></script>
<form method="post" id="staff_form">
<main id="contents">
    <section class="readme">
        <?php $title_top = ($action == 'edit') ? '作業者編集' : '作業者登録';?>
        <h2 class="titleContent">
            <?php echo $title_top;?>
        </h2>
    </section>
    <article class="container">
        <p class="note">作業車情報を入力して更新ボタンを押してください。 <span class="must">*</span>は必須入力項目です。</p>
        <?php
            if (Yii::$app->session->hasFlash('success')) {
        ?>
                <div class="alert alert-danger">更新を完了しました。
                    <button class="close" data-dismiss="alert">&times;</button>
                </div>
        <?php
            }
        ?>

        <?php
        if (Yii::$app->session->hasFlash('error')) {
            ?>
            <div class="alert alert-danger">
                <?php echo Yii::$app->params['message_save_error']?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        ?>

        <?php
            $post = Yii::$app->request->post('Sdptm08sagyosya');
            if (!empty($post)) {
                $model->M08_HAN_CD = $post['M08_HAN_CD'];
                $model->M08_SS_CD = $post['M08_SS_CD'];
                $model->M08_HAN_CD = $post['M08_HAN_CD'];
                $model->M08_JYUG_CD = $post['M08_JYUG_CD'];
                $model->M08_NAME_SEI = $post['M08_NAME_SEI'];
                $model->M08_NAME_MEI = $post['M08_NAME_MEI'];
                $model->M08_ORDER = $post['M08_ORDER'];
                $all_ss = \backend\modules\maintenance\controllers\StaffController::processGetss($post['M08_HAN_CD'], $api);
            } else {
                if ($action == 'create') {
                    $model->M08_HAN_CD = $default_value['M08_HAN_CD'];
                    $model->M08_SS_CD = $default_value['M08_SS_CD'];
                }
            }


        ?>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">支店<span class="must">*</span></label>
                        <?= Html::activeDropDownList($model, 'M08_HAN_CD', $all_branch, array('class' => 'selectForm', 'id' => 'selectBranch')) ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">SS<span class="must">*</span></label>
                        <?= Html::activeDropDownList($model, 'M08_SS_CD', $all_ss, array('class' => 'selectForm', 'id' => 'selectSS')) ?>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">従業員CD<span class="must">*</span></label>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M08_JYUG_CD', ['class' => 'textForm', 'maxlength' => 10]); ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">作業者名<span class="must">*</span></label>
                        <span class="txtUnit">姓&nbsp;</span>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M08_NAME_SEI', ['class' => 'textForm formWidthS', 'maxlength' => 15]); ?>
                        &nbsp;<span class="txtUnit">名&nbsp;</span>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M08_NAME_MEI', ['class' => 'textForm formWidthS', 'maxlength' => 15]); ?>
                        <label id="name-error" class="error" for="name"></label>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">表示順</label>
                        <?= \yii\helpers\Html::activeTextInput($model, 'M08_ORDER', ['class' => 'textForm formWidthS', 'maxlength' => 3]); ?>
                    </div>
                </div>
            </fieldset>
        </section>
    </article>
</main>
<footer id="footer">
    <?php
        $url = Yii::$app->session->has('url_list_staff') ? Yii::$app->session->get('url_list_staff') : \yii\helpers\BaseUrl::base().'/list-staff';
    ?>
    <div class="toolbar">
        <div class="toolbar-left">
            <a class="btnBack" href="<?php echo $url;?>">戻る</a>
        </div>
        <div class="toolbar-right">
            <button type="submit" class="btnSubmit" href="edit-staff">更新</button>
            <?php if($action == 'edit') {?>
            <a data-toggle="modal" class="btnTool btnYellow" href="#modalRemoveStaffConfirm">削除</a>
            <?php }?>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
</form>
<div id="modalRemoveStaffConfirm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">作業者削除</h4>
            </div>
            <div class="modal-body">
                <p class="note">作業者データを削除します。よろしいですか？</p>
            </div>
            <div class="modal-footer">
                <a aria-label="Close" data-dismiss="modal" class="btnCancel flLeft" href="#">いいえ</a>
                <form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/maintenance/staff/delete">
                    <?php
                    ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'M08_HAN_CD'); ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'M08_SS_CD'); ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'M08_JYUG_CD'); ?>
                <button type="submit" class="btnSubmit flRight">はい</button>
                </form>
            </div>
        </div>
    </div>
</div>