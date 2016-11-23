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
    <title><?= isset(Yii::$app->view->title) ? Yii::$app->view->title : 'ログインアカウント'; ?></title>
    <?php $this->head() ?>
    <script src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/js/jquery-2.1.4.min.js"></script>
    <script>
        var baseUrl = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
    </script>
</head>
<body>
<header id="header">
    <h1 class="titlePage"><?= isset(Yii::$app->params['titlePage']) ? Yii::$app->params['titlePage'] : 'ログインアカウント'; ?></h1>
</header>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
