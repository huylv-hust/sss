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
    <script type="text/javascript">
        var base_url = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
    </script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo \yii\helpers\BaseUrl::base() ?>/favicon.ico" />
    <?= Html::csrfMetaTags() ?>
    <title><?= isset(Yii::$app->view->title) ? Yii::$app->view->title : 'SSサポートサイトTOP'; ?></title>
    <?php $this->head() ?>
    <script>
        var baseUrl = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
    </script>
</head>
<body>
<header id="header"><a href="#side_menu" id="navSideMenu">Side Menu</a>
    <h1 class="titlePage">
        <?php echo isset(Yii::$app->params['titlePage']) ? Yii::$app->params['titlePage'] : ''; ?>
    </h1>
</header>

<?= $content ?>

<div id="sidr" class="sidr">
    <div class="closeSideMenu"><a href="#" id="sidrClose">Close</a></div>
    <ul>
        <li><a href="<?php echo \yii\helpers\BaseUrl::base(true); ?>/menu">SSサポートサイトTOP</a></li>
    </ul>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
