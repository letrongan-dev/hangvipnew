<?php
$asset = app\assets\AppAsset::register($this);

use yii\helpers\Html;
use yii\helpers\Url;

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
        <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WVZ22WT');
</script>
<!-- End Google Tag Manager -->
    </head>   
    <body>
        <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVZ22WT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php $this->beginBody() ?>

		<!-- Load Facebook SDK for JavaScript -->
      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v5.0'
          });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      <!-- Your customer chat code -->
      <div class="fb-customerchat"
        attribution=setup_tool
        page_id="106576577492769"
  theme_color="#0084ff"
  logged_in_greeting="Xin chào! Chúng tôi có thể giúp gì cho bạn?"
  logged_out_greeting="Xin chào! Chúng tôi có thể giúp gì cho bạn?">
      </div>

        <div class="wrapper_hv">
			<?php $this->beginContent('@app/views/layouts/header.php'); ?><?php $this->endContent(); ?>
            <div class="col-xs-12 paddingleftright logo" style="position: relative">
                <a href="<?= SITE_PATH ?>"><img style="max-width:220px; position:relative; z-index:10;" src="<?=SITE_PATH?>/uploads/logo_hv1.png" /></a>
            </div>
			<?php $this->beginContent('@app/views/layouts/menu.php'); ?><?php $this->endContent(); ?>
            
            <div class="clearfix"></div>
			<?php $this->beginContent('@app/views/layouts/slide.php'); ?><?php $this->endContent(); ?>
			
            <div class="main_content">
                <div class="container">
                    <div class="product_home">
						
						<?php if ($flash = Yii::$app->session->getFlash("Register-success")) { ?>
						<div class="alert alert-success" style="margin-top: 10px;">
								<h4 style="padding:10px 0px;"><?= $flash ?></h4>
								<h6>
									<?=Yii::t('app','Bạn đã đăng ký tài khoản thành công, hiện giờ bạn có thể đăng nhập mua hàng tại hangvip.vn')?>
								</h6>
								<h5>
									 <?=Yii::t('app','Chúc bạn một ngày tốt lành')?>
								</h5> 
								<a style="font-weight:bold; color:#bb1b2e;" href="<?php echo SITE_PATH;?>/cap-nhat-tai-khoan.html"> <?=Yii::t('app',"Cập nhật tài khoản")?></a>
							</div>        
						<?php }?>
						<?= $content ?>
					
                        
                    </div>
                </div>
            </div>
            <div class="footer" style="position: relative; overflow: hidden;">				
				<?php $this->beginContent('@app/views/layouts/footer.php'); ?><?php $this->endContent(); ?>                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#toTop").click(function () {
                    $("html, body").animate({scrollTop: 0}, 1000);
                });
            });
        </script>
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>