<?php
$asset = app\assets\AppAsset::register($this);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\article\api\Article;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>	
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">		
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>    
        <link rel="icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
		<?php $this->head() ?>
    </head>    
    <body style="padding-top: 0px;">
        <?php $this->beginBody() ?>
			<div class="header" style="position: relative;">
				<?php $this->beginContent('@app/views/layouts/header.php'); ?><?php $this->endContent(); ?>
				<?php $this->beginContent('@app/views/layouts/menu.php'); ?><?php $this->endContent(); ?> 
			</div>
			<div class="container wwraper">
				<?php $this->beginContent('@app/views/layouts/slide_hd.php'); ?><?php $this->endContent(); ?>
				<?php if ($flash = Yii::$app->session->getFlash("Register-success")) { ?>
				<div class="alert alert-success" style="margin-top: 10px;">
						<h4 style="padding:10px 0px;"><?= $flash ?></h4>
						<h6>
							Bạn đã đăng ký tài khoản thành công, hiện giờ bạn có thể đăng nhập mua hàng tại hangvip.vn
						</h6>
						<h5>
							Chúc bạn một ngày tốt lành.
						</h5> 
						<a style="font-weight:bold; color:#bb1b2e;" href="<?php echo SITE_PATH;?>/user/account">Cập nhật thông tin tài khoản </a>
					</div>        
				<?php }?>
				<?= $content ?>  
			</div>
			<div class="clearfix"></div>
			<div class="footer" style="margin-top:10px;">
				<?php $this->beginContent('@app/views/layouts/footer.php'); ?><?php $this->endContent(); ?>    
			</div>
		<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>