<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <script type="text/javascript">
        var base_url = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
    </script>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo \yii\helpers\BaseUrl::base() ?>/favicon.ico" />
	<?= Html::csrfMetaTags() ?>
	<title><?= isset(Yii::$app->view->title) ? Yii::$app->view->title : 'SSサポートサイトTOP'; ?></title>
	<?php $this->head() ?>
	<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/jquery-2.1.4.min.js"></script>
	<script>
		var baseUrl = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
	</script>
</head>
<body>
<header id="header">
    <a href="#side_menu" id="navSideMenu">Side Menu</a>
	<h1 class="titlePage">
        <?php echo isset(Yii::$app->params['titlePage']) ? Yii::$app->params['titlePage'] : ''; ?>
    </h1>
	<div class="navHeader">
        <span class="iconMember"> <?php
            $login_info = Yii::$app->session->get('login_info');
            echo isset($login_info['M50_USER_NAME']) ? $login_info['M50_USER_NAME'] : '';
            ?></span>
        <a href="<?php echo \yii\helpers\BaseUrl::base().'/user/logout' ?>" class="iconLogout">ログアウト</a></div>
</header>

<?= $content ?>

<div id="sidr" class="sidr">
	<div class="closeSideMenu"><a href="#" id="sidrClose">Close</a></div>
	<?php
		if (Yii::$app->controller->route == 'site/index') {
			?>
			<ul>
				<li><a href="#" onclick="fncType('regist');">作業伝票</a></li>
                <li><a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/list-workslip">作業履歴</a></li>
				<li><a href="/asbo/?sscode=<?php echo isset($login_info['M50_SS_CD']) ? $login_info['M50_SS_CD'] : ''; ?>">作業予約管理</a></li>
				<li><a href="#" onclick="fncCard();">Usappyカード番号変更</a></li>
				<li><a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/maintenance">設定</a></li>
				<li><a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken">車検</a></li>
				<?php if (in_array($login_info['M50_SS_CD'], Yii::$app->params['sateiss'])) { ?>
                <li><a href="/satei/?sscode=<?php echo isset($login_info['M50_SS_CD']) ? $login_info['M50_SS_CD'] : ''; ?>">中古車査定</a></li>
                <?php } ?>
			</ul>
		<?php
		}
	else
	{
	?>
		<ul>
			<li><a href="<?php echo \yii\helpers\BaseUrl::base(true); ?>/menu">SSサポートサイトTOP</a></li>
		</ul>
	<?php
	}
	?>
</div>
<?php $this->endBody() ?>
<div class="please-wait">
    <img width="50" src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/img/loading7_light_blue.gif">
    <span class="loading-text">しばらくお待ちください</span>
</div>

</body>
</html>
<?php $this->endPage() ?>
