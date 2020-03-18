<?php 
$asset= app\assets\AppAsset::register($this);
$session = Yii::$app->session;
?>
<div class="breadcrumbs">
    <a href="<?= SITE_PATH ?>">Trang chủ</a>
    &nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <a href="<?= SITE_PATH ?>/repay/repay">Checkout complete</a>
</div>
<div class="col-xs-12 paddingleftright">
    <h3 class="title_category_cate" style="border-bottom: 1px solid #ccc; margin-bottom: 10px; padding-bottom: 10px;">Checkout complete</h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 borderleftrightbottom paddingleftright">
    <?php echo "<p style='color: red; font-size: 14px;'>".Yii::$app->session->getFlash("successrepay")."</p>";?>
</div>