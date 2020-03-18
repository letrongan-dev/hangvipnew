<?php
use yii\easyii\modules\page\api\Page;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\helpers\Html;

$page = Page::get('page-shopcart-success');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<div class="breadcrumbs">
    <a href="<?= SITE_PATH ?>"><?=Yii::t('app', 'Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <a href="<?=SITE_PATH?>/thanh-toan.html">Shopcart</a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <a href="#">Success</a>
</div>

<div class="col-xs-12 paddingleftright" style="min-height: 400px;">  
    <h1>Mua hàng thành công !</h1> 
    <h4>
        Cảm ơn bạn đã đặt hàng tại <a href="<?= SITE_PATH;?>">website</a> chúng tôi. Chúng tôi sẽ kiểm tra và giao hàng cho bạn trong thời gian sớm nhất.
    </h4>
    <?php $this->beginContent('@app/views/layouts/random.php'); ?><?php $this->endContent(); ?>
    <div style="border-bottom: 1px solid #e4e4e4; height: 1px; margin-top: -1px;">&nbsp;</div>
</div>
