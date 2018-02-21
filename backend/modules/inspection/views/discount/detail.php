<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/discount.js"></script>
<main id="contents">
	<section class="readme">
		<h2 class="titleContent"> 割引・割増詳細</h2>
	</section>
	<article class="container">
		<section class="discount">
			<table class=" table table-striped table-bordered">
				<tbody>
				<tr>
					<th style="width:300px; max-width: 300px">分類名</th>
					<th><?php echo \yii\helpers\Html::encode($parent['NAME']);?></th>
				</tr>
				<?php
				foreach ($package as $k => $v) {
					?>
					<tr>
						<td><?php echo \yii\helpers\Html::encode($v['NAME']);?></td>
						<td>
							<?php
							foreach ($discount[$k] as $key => $value) {
								?>
								<p><?php echo number_format($value['VALUE']). '円 ' . ' (' . \yii\helpers\Html::encode($value['DESCRIPTION']) . ')';?></p>

							<?php } ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<div class="modal fade" id="modalWorkSlipComp">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">割引・割増料金削除</h4>
						</div>
						<div class="modal-body">
							<p class="note">割引・割増料金を削除します。よろしいですか？</p>
						</div>
						<div class="modal-footer">
							<a href="#" class="btnCancel flLeft" data-dismiss="modal" aria-label="Close">いいえ</a>
							<form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/discount/remove">
								<input type="hidden" name="parent_id" value="" id="remove_discount">
								<button type="submit" class="btnSubmit flRight">はい</button>
							</form>
						</div>
					</div>
				</div>
			</div>
            <footer id="footer">
                <div class="toolbar">
                    <div class="toolbar-left">
                        <a href="<?php echo Yii::$app->session->get('url_list_discount')?>" class="btnBack">戻る</a>
                    </div>
                    <div class="toolbar-right">
						<a class="btnTool btnYellow btn_remove"attr-id="<?php echo $parent['ID']?>">削除</a>
                        <a  class="btnSubmit" href="<?php echo \yii\helpers\BaseUrl::base(true).'/discount?id='.$parent['ID']?>">編集</a>
                    </div>
                </div>
                <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
            </footer>
		</section>
	</article>
</main>