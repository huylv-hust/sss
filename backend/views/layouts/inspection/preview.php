<?php
use backend\assets\PreviewInspectionAsset;

PreviewInspectionAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset(Yii::$app->view->title) ? Yii::$app->view->title : ''; ?></title>
    <link rel="shortcut icon" href="<?php echo \yii\helpers\BaseUrl::base() ?>/favicon.ico" />
    <?php $this->head() ?>
    <script>
        var baseUrl = '<?php echo \yii\helpers\BaseUrl::base(true); ?>';
    </script>

</head>
<body>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
