<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/comment.js"></script>
<main id="contents">
    <section class="readme">
        <h2 class="titleContent">車検コメント分類一覧</h2>
    </section>
    <article class="container">
        <?php if(Yii::$app->session->hasFlash('success')) {?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('success')?>
                <button data-dismiss="alert" class="close">×</button>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('error')) {?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('error')?>
                <button data-dismiss="alert" class="close">×</button>
            </div>
        <?php }?>
        <section class="nolineContent">
            <section class="areaSearch">
                <!--<a style="float: right" class="btnFormTool" href="<?php //echo \yii\helpers\BaseUrl::base(true) ?>/comment/create">新規登録</a>-->
            </section>
            <div class="clearfix"></div>
            <table class="tableList">
                <tbody>
                <tr>
                    <th>分類名</th>
                    <th style="width:12%; max-width: 12%; min-width: 150px;">操作</th>
                </tr>
                <?php
                foreach($parent_comments as $parent_comment) {
                ?>
                <tr>
                    <td><?php echo \yii\helpers\Html::encode($parent_comment['NAME'])?></td>
                    <td><a class="btnFormTool btnGreen"
                           href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/comment?id=<?php echo $parent_comment['ID']?>">編集</a>
                        <a class="btnFormTool btnYellow btn_remove" attr-id="<?php echo $parent_comment['ID']?>">削除</a></td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <nav class="paging">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                    'nextPageLabel' => '&gt;',
                    'prevPageLabel' => '&lt;',
                    'firstPageLabel' => '&laquo;',
                    'lastPageLabel' => '&raquo;',
                ]);
                ?>
            </nav>
        </section>
    </article>
    <div id="modalRemoveConfirm" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">車検コメント削除</h4>
                </div>
                <div class="modal-body">
                    <p class="note">車検コメントを削除します。よろしいですか？</p>
                </div>
                <div class="modal-footer">
                    <a aria-label="Close" data-dismiss="modal" class="btnCancel flLeft" href="#">いいえ</a>
                    <form action="<?php echo \yii\helpers\BaseUrl::base(true) ?>/comment/delete" method="post">
                        <input type="hidden" value="" name="ID" id="parent_comment_id_hidden">
                        <button class="btnSubmit flRight" type="submit">はい</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer id="footer">
        <div class="toolbar">
            <div class="toolbar-left">
                <a href="<?php echo \yii\helpers\BaseUrl::base(true) . '/maintenance'; ?>" class="btnBack">設定メニューに戻る</a>
            </div>
            <div class="toolbar-right">
                <a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/comment" class="btnSubmit">新規登録</a>
            </div>
        </div>
        <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
    </footer>
</main>