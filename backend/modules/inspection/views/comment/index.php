
<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/comment.js"></script>
<main id="contents">
	<section class="readme">
		<h2 class="titleContent">車検コメント登録・編集</h2>
	</section>
	<div class="container">
		<div class="row">
			<?php if(Yii::$app->session->hasFlash('error')) {?>
				<div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('error')?>
					<button data-dismiss="alert" class="close">×</button>
				</div>
			<?php }?>
			<?php
				$post = Yii::$app->request->post();
			?>
			<form class="form-horizontal col-md-12" method="post" id="form_edit_comment">
				<div class="form-inline">
					<label class="col-sm-2 control-label">分類名</label>
					<input value="<?php echo isset($post['NAME']) ? \yii\bootstrap\Html::encode($post['NAME']) : (isset($parent->NAME) ? \yii\helpers\Html::encode($parent->NAME) : '');?>" name="NAME" type="text" class="form-control booking-input textForm" maxlength="60">
				</div>

				<div class="form-inline vmiddle gray">
					<label class="col-sm-2 control-label" style="margin-left: 3.3%">コメント</label>
					<div class="title" style="width: 100%">
					<?php
						if(isset($post['comment']) && empty($comments)) {
							foreach($post['comment'] as $k => $v)
							{
					?>
								<div class="content" attr-index="<?php echo $k?>">
									<input type="hidden" value="" class="comment_id" name="comment[<?php echo $k?>][ID]">
									<input type="text textForm" value="<?php echo \yii\helpers\Html::encode($v['CONTENT']);?>" class="form-control booking-input" name="comment[<?php echo $k?>][CONTENT]" maxlength="450" style="width: 88%">
									<button class="btn btn-sm btn-danger remove_comment" type="button"><i class="glyphicon glyphicon-minus"></i></button>
								</div>
					<?php
							}
						} else {
							$comments = empty($comments) ? [['CONTENT' => '', 'ID' => '']] : $comments;
							foreach($comments as $k => $v) {

					?>
							<div class="content" attr-index="<?php echo $k;?>">
								<input type="hidden" value="<?php echo $v['ID']?>" class="comment_id" name="comment[<?php echo $k?>][ID]">
								<input type="text" value="<?php echo \yii\helpers\Html::encode($v['CONTENT'])?>" class="form-control booking-input textForm" name="comment[<?php echo $k?>][CONTENT]" maxlength="450" style="width: 88%">
								<?php if(!in_array($v['ID'],$comments_used)) {?>
								<button class="btn btn-sm btn-danger remove_comment" type="button"><i class="glyphicon glyphicon-minus"></i></button>
									<?php } else echo '<span style="width: 5%; max-width: 5%">(使用中)</span>'?>
							</div>
					<?php
							}
						}
					?>
					</div>
					<div class="col-sm-1">
						<button class="btn btn-sm btn-success add_comment" type="button"><i class="glyphicon glyphicon-plus"></i></button>
					</div>
				</div>
				<footer id="footer">
					<div class="toolbar">
						<div class="toolbar-left">
							<a href="<?php echo Yii::$app->session->get('url_list_comment')?>" class="btnBack">戻る</a>
						</div>
						<div class="toolbar-right">
							<button href="edit-staff" class="btnSubmit" type="submit"><?php echo isset($parent->ID) ? '更新' : '登録'?></button>
						</div>
					</div>
					<p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
				</footer>
				<input type="hidden" value="0" name="comment_remove" class="comment_remove">
			</form>
		</div>
	</div>
</main>