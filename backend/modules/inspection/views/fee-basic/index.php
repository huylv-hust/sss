<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/fee_basic.js"></script>
<main id="contents">
	<section class="readme">
		<h2 class="titleContent">基本料金登録・編集</h2>
	</section>
	<div class="container">
		<div class="row">
            <?php if (Yii::$app->session->hasFlash('success')) {?>
                <div class="alert alert-danger col-md-12">
                    <?php echo Yii::$app->session->getFlash('success') ?>
                    <button data-dismiss="alert" class="close">×</button>
                </div>
            <?php }
            if (Yii::$app->session->hasFlash('error')) {?>
                <div class="alert alert-danger col-md-12">
                    <?php echo Yii::$app->session->getFlash('error') ?>
                    <button data-dismiss="alert" class="close">×</button>
                </div>
            <?php }?>

			<form class="form-horizontal col-md-12" method="post" id="fee_basic_form">
				<div class="form-group booking-div-form">
					<label class="col-sm-2 col-sm-offset-2">分類名</label>
					<div class="">
						<input class="form-control textForm" style="width: 450px" type="text"  name="NAME" value="<?php echo isset($fee_basic['NAME']) ? \yii\helpers\Html::encode($fee_basic['NAME']) : ''?>" maxlength="60">
					</div>
				</div>
				<div class="form-group gray booking-div-form">
					<label class="col-sm-2  col-sm-offset-2">基本料金</label>
					<div class="">
						<input class="form-control col-sm-10 textForm number_han" style="width: 450px" type="text"  name="VALUE" value="<?php echo isset($fee_basic['VALUE']) ? $fee_basic['VALUE'] : ''?>" maxlength="10">
						<span class="col-sm-2">円</span>
					</div>
				</div>
				<footer id="footer">
					<div class="toolbar">
						<div class="toolbar-left">
							<a href="<?php echo Yii::$app->session->get('url_list_fee_basic')?>" class="btnBack">戻る</a>
						</div>
						<div class="toolbar-right">
							<button href="edit-staff" class="btnSubmit" type="submit"><?php echo isset($fee_basic['ID']) ? '更新' : '登録'?></button>
						</div>
					</div>
					<p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
				</footer>
			</form>
		</div>
	</div>
</main>
