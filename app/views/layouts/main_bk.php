<?php
$asset = app\assets\AppAsset::register($this);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\article\api\Article;
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=409301182573688";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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
<?php $this->endContent(); ?>