<main id="contents">
    <section class="readme">
        <h2 class="titleContent">エラー</h2>
    </section>
    <article class="container">
        <p class="note">エラーが発生しました。<br>
            メニューページへ戻り、再度操作を行ってください。</p>
    </article>
</main>
<footer id="footer">
    <?php $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>
    <div class="toolbar"><a href="<?php echo \yii\helpers\BaseUrl::base(true).(substr_count($url, \yii\helpers\BaseUrl::base(true).'/admin') ? '/admin/login' : '/menu'); ?>" class="btnBack">メニュー</a></div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>