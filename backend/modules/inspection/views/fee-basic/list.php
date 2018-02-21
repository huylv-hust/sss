<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/fee_basic.js"></script>
<main id="contents">
    <section class="readme">
        <h2 class="titleContent">基本料金設定一覧</h2>
    </section>
    <article class="container">
        <?php if (Yii::$app->session->hasFlash('success')) {?>
        <div class="alert alert-danger">
            <?php echo Yii::$app->session->getFlash('success') ?>
            <button data-dismiss="alert" class="close">×</button>
        </div>
        <?php }
         if (Yii::$app->session->hasFlash('error')) {?>
            <div class="alert alert-danger">
                <?php echo Yii::$app->session->getFlash('error') ?>
                <button data-dismiss="alert" class="close">×</button>
            </div>
        <?php }?>
        <section class="nolineContent fee_basic">
            <section class="areaSearch">
                <!--<a style="float: right" class="btnFormTool" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee-basic">新規登録</a>-->
            </section>
            <div class="clearfix"></div>
            <table class="tableList">
                <tbody><tr>
                    <th>分類名</th>
                    <th style="width:20%; max-width: 20%">基本料金</th>
                    <th style="width:12%; max-width: 12%; min-width: 150px">操作</th>
                </tr>
                <?php foreach ($fee_basic as $k => $v) {?>
                <tr>
                    <td><?php echo \yii\helpers\Html::encode($v['NAME'])?></a></td>
                    <td><?php echo number_format($v['VALUE']).'円'?></td>
                    <td style="min-width: 150px;">
                        <a class="btnFormTool btnGreen" href="<?php echo \yii\helpers\BaseUrl::base(true).'/fee-basic?id='.$v['ID']?>">編集</a>
                        <a class="btnFormTool btnYellow btn_remove" attr-id="<?php echo $v['ID']?>">削除</a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <nav class="paging">
                <?php
                echo yii\widgets\LinkPager::widget([
                        'pagination' => $pagination,
                        'nextPageLabel' => '&gt;',
                        'prevPageLabel' => '&lt;',
                        'firstPageLabel' => '&laquo;',
                        'lastPageLabel' => '&raquo;',
                ]);
                ?>
            </nav>
            <!-- modal remove -->
            <div class="modal fade" id="modalWorkSlipComp">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">基本料金削除</h4>
                        </div>
                        <div class="modal-body">
                            <p class="note">基本料金を削除します。よろしいですか？</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btnCancel flLeft" data-dismiss="modal" aria-label="Close">いいえ</a>
                            <form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee-basic/remove">
                                <input type="hidden" name="fee_basic_id" value="" id="remove_fee_basic">
                                <button type="submit" class="btnSubmit flRight">はい</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
    <footer id="footer">
        <div class="toolbar">
            <div class="toolbar-left">
                <a href="<?php echo \yii\helpers\BaseUrl::base(true) . '/fee'; ?>" class="btnBack">車検料金設定に戻る</a>
            </div>
            <div class="toolbar-right">
                <a href="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee-basic" class="btnSubmit">新規登録</a>
            </div>
        </div>
        <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
    </footer>
</main>