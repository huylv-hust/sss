<form id="update_commodity" method="post" enctype="multipart/form-data" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/maintenance/commodity/import">
<main id="contents">
    <section class="readme">
        <h2 class="titleContent">商品情報更新</h2>
    </section>
    <article class="container">
        <p class="note">商品を更新する場合は、ファイルを選択して「インポート」ボタンを押してください。<br>
            現在登録済の商品を確認する場合は、「現在の商品リストをダウンロードする」を押してください。</p>
        <?php if (Yii::$app->session->hasFlash('success')) {?>
        <div class="alert alert-danger">商品リストのインポートが完了しました。
            <button data-dismiss="alert" class="close">×</button>
        </div>
        <?php } ?>

        <?php if (!empty(Yii::$app->session->hasFlash('error'))) {?>
            <div class="alert alert-danger">
                <button data-dismiss="alert" class="close">×</button>
                <?php foreach(Yii::$app->session->getFlash('error') as $k => $v) {
                    echo '<p>'.$v.'</p>';
                }?>

            </div>
        <?php } ?>

        <div class="alert alert-warning" id="loading">
            <img src="<?php echo \yii\helpers\BaseUrl::base() ?>/img/loading7_light_blue.gif" width="30" height="30">
            インポート中です。
            <button data-dismiss="alert" class="close">×</button>
        </div>

            <section class="bgContent">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">商品リストインポート</label>
                        <input name="commodity" type="file" class="inputFile">
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem"> <a class="txtSub" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/maintenance/commodity/export">現在の商品リストをダウンロード</a> </div>
                </div>
            </section>
    </article>
</main>

<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left">
            <a href="<?php echo \yii\helpers\BaseUrl::base()?>/maintenance" class="btnBack">戻る</a>
        </div>
        <div class="toolbar-right">
            <button type="submit" class="btnSubmit">インポート</button>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>


</form>
<script src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/js/module/maintenance.js"></script>
<script>
$(function()
{
    $('#loading').hide();
});
</script>