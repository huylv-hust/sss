<main id="contents">
    <section class="readme">
        <p>作業伝票システムの設定メニューです。</p>
    </section>
    <article class="container">
        <p class="mt50 centering">項目を選択してください。</p>
        <nav class="navBasic">
            <ul class="ulNavMainte">
                <li><a class="btnNavBasic iconList" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/list-staff">作業者一覧</a></li>
                <li><a class="btnNavBasic iconMoney" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/fee">車検料金設定</a></li>
                <li><a class="btnNavBasic iconComment" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/comment/list">車検コメント設定</a></li>
                <li style="display: none"><a class="btnNavBasic iconXls" href="<?php echo \yii\helpers\BaseUrl::base(true)?>/update-commodity">商品情報更新</a></li>
            </ul>
        </nav>
    </article>
</main>
<footer id="footer">
    <div class="toolbar"><a class="btnBack" href="<?php echo \yii\helpers\BaseUrl::base(true)?>">メニューに戻る</a></div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>