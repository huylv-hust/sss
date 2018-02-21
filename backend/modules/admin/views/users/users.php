<?php use yii\helpers\BaseUrl; ?>
<main id="contents">
        <section class="readme">
        </section>
        <article class="container">
			 <?php if (Yii::$app->session->hasFlash('success')) { ?>
				<div class="alert alert-danger">削除を完了しました。
					<button data-dismiss="alert" class="close">×</button>
				</div>
			<?php } ?>
            <form class="formSearchList" action="">

                <section class="bgContent mt10">
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">ユーザー名</label>
                            <input type="text" class="textForm" name="M50_USER_NAME" value="<?php echo $filters['M50_USER_NAME']?>">
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">&#12288;</label>
                            <button type="submit" class="btnSearch" style="border: none;">検索</button>
                        </div>
                    </div>
                </section>
            </form>
			<?php if (count($listM50) == 0) { ?>
				<div class="alert alert-danger">入力条件に該当するユーザーが存在しません
					<button data-dismiss="alert" class="close">×</button>
				</div>
			<?php } ?>
            <section class="nolineContent">

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
				<?php if(count($listM50)) {?>
                <table class="tableList">
                    <tbody><tr>
                        <th>ログインID</th>
                        <th>ユーザー名</th>
                    </tr>
					<?php foreach($listM50 as $row) {?>
						<tr>
							<td><a href="<?php echo BaseUrl::base() . '/admin/user' ?>?M50_USER_ID=<?php echo $row['M50_USER_ID'];?>"><?php echo $row['M50_USER_ID']?></a></td>
							<td><?php echo $row['M50_USER_NAME']?></td>
						</tr>
					<?php } ?>

                </tbody>
				</table>
				<?php } ?>
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
            </section>
        </article>
    </main>
	<footer id="footer">
        <div class="toolbar">
            <div class="toolbar-left">
            </div>
            <div class="toolbar-right">
                <a class="btnSubmit" href="<?php echo BaseUrl::base() . '/admin/user' ?>">新規登録</a>
            </div>
        </div>
        <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
    </footer>