<?php

use app\models\AddToCartForm;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\helpers\Image;
use yii\easyii\models\Photo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\StringHelper;
$notation = Yii::$app->session->get('notation');
$asset = app\assets\AppAsset::register($this);
$addToCartForm = new \app\models\AddToCartForm();
$this->title = $item->seo('title', $item->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = ['label' => $item->cat->title, 'url' => ['shop/cat', 'slug' => $item->cat->slug]];
$this->params['breadcrumbs'][] = $item->model->title;
$colors = [];
if (!empty($item->data->color) && is_array($item->data->color)) {
    foreach ($item->data->color as $color) {
        $colors[$color] = $color;
    }
}
?>

<div class="breadcrumbs">
    <a href="<?= SITE_PATH ?>">Trang chủ</a>&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>&nbsp;&nbsp;
    <?php
    $category = Catalog::cat($item->category_id);
    if ($category->depth == 0) {
        ?>
        <a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.html'; ?>"><?php echo $category->title; ?></a>&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>&nbsp;&nbsp;
    <?php } else { ?>
        <?php $category1 = Catalog::cat($category->tree); ?>
        <a href="<?php echo SITE_PATH; ?>/<?= $category1->slug . '.html'; ?>"><?php echo $category1->title; ?></a>&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>&nbsp;&nbsp;
        <a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.html'; ?>"><?php echo $category->title; ?></a>&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>&nbsp;&nbsp;
    <?php } ?>
    <a href="<?= SITE_PATH . '/' . $item->slug . '.html' ?>"><?= $item->title ?></a>    
</div>
<!--<h1 class="title-main"><a href="#"><?= $item->title ?></a></h1> -->
<div class="col-xs-12" style="padding:0;">
    <div class="col-xs-12" style="padding:0;">
        <h1 class="title-card" style="border-color:#bb1b2e;margin-bottom:20px;">
            <?= Html::a($item->title, ['the/chitiet', 'slug' => $item->slug], ['style' => 'color:#bb1b2e;']) ?> 
        </h1>
    </div>
    <?php if (Yii::$app->request->get(AddToCartForm::SUCCESS_VAR)) { ?>
        <h4 class="text-success"><i class="glyphicon glyphicon-ok"></i> Added to cart successful</h4>
    <?php } ?>
    <style>
        .productde-img-long{padding:2px; border:1px solid #ccc; border-radius: 5px; margin-bottom: 15px;}
        .productde-main h1{text-transform: uppercase;font-size: 24px; color: #781446;margin: 10px 0px;}
        .productde-main h3{color: #333;font-size: 16px;padding-left: 2px;margin: 7px 0px;}
        .productde-main p{font-weight: bold;}
        .product-submit{text-transform: uppercase;background-color: #e95f90;font-weight: bold; color: #fff; border: 0px;padding:10px 20px; border-radius: 5px;}
        .paddingleftright{padding-left: 0px !important;padding-right: 0px !important;}        
        .padding5{padding-left: 5px !important;padding-right: 5px !important;}
        .product-related a{color:#000; text-decoration: none;}
        .product-related a:hover{color: #781446;}
        .rg-image img{padding:2px; border-radius:5px; border:1px solid #ccc;}
    </style>   

    <div class="col-xs-12 productde" style="padding-left:0px; padding-right:0px;">
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 paddingleftright productde-img">            
            <div id="rg-gallery" class="rg-gallery">                    
                <div class="rg-thumbs">
                    <!-- Elastislide Carousel Thumbnail Viewer -->
                    <div class="es-carousel-wrapper">
                        <div class="es-carousel">
                            <ul>                            
                                <li><a href="#"><img src="<?php echo Image::thumb($item->image, 250, 250) ?>" data-large="<?php echo Image::thumb($item->image, 250, 240) ?>" alt="" data-description="" /></a></li>
                                <?php
                                $pho = Photo::Getimages($item->id);
                                foreach ($pho as $p) {
                                    ?>
                                    <li>
                                        <a href="#">
                                            <img src="<?php echo Image::thumb($p->image, 65, 65) ?>" data-large="<?php echo Image::thumb($p->image, 325, 240) ?>" alt="" data-description="" />
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- End Elastislide Carousel Thumbnail Viewer -->
                </div><!-- rg-thumbs -->
            </div><!-- rg-gallery -->  
        </div>
        <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 productde-main">
            <h1><?php echo $item->title; ?></h1>
            <h3><?php echo $item->short_description; ?></h3>
            <p style="border-top:2px solid #f5f5f5;padding-top: 5px;">Giá gốc: <?php echo formatprice($item->price, $notation) . ' ' . $notation; ?> </p>
            <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->id]), 'options' => ['style' => 'padding-bottom:10px;']]); ?>
            <div class="col-xs-2" style="padding: 5px 0 0 0; width: 70px; float: left;">Số lượng: </div>
            <?= $form->field($addToCartForm, 'count', ['options' => ['class' => 'col-lg-2 col-xs-10 paddingleftright']])->textInput(['maxlength' => 255, 'class' => 'form-control input-sm', 'style' => 'padding:5px;'])->label(false); ?>
            <?= $form->field($addToCartForm, 'item_id', ['options' => ['style' => 'height:0;margin:0;']])->hiddenInput(['value' => $item->id])->label(false) ?>
            <div class="clearfix"></div>
            <?= Html::submitButton('Mua ngay', ['class' => 'product-submit']) ?>
            <div class="clearfix"></div>
            <?php ActiveForm::end(); ?>
            <h2 style="margin: 10px 0px;  font-size: 18px;">Sản phẩm liên quan</h2>
            <div class="product-related" style="border:1px solid #ccc;padding: 15px 5px 0px 5px; border-radius: 2px;">
                <?php
                $randomitem = Item::GetRandom($item->category_id);
                foreach ($randomitem as $r) {
                    ?>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding5 sanphamlienquan" style="text-align:center;">
                        <?= Html::a('<img src="' . Image::thumb($r->image, 85, 85) . '" />', ['the/chitiet', 'slug' => $r->slug]) ?>
                        <p style="font-weight: normal !important;"><?= Html::a($r->title, ['the/chitiet', 'slug' => $r->slug]) ?></p>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
        <style>
            .product-info{ margin-top: 30px;}
            .product-info h3{
                margin: 0px;
                text-transform: uppercase;
                font-size: 24px;
                border-top: 2px solid #70992f;
                padding: 10px;
                width: 270px;
                border-left: 1px solid #ccc;
                border-right: 1px solid #ccc;
                margin-bottom: -1px;
                position: relative;
                z-index: 50;
                border-bottom: 0px;
                background: #fff;
            }
        </style>
        <div class="row">
            <div class="col-xs-12 product-info">
                <h3 class="infomobi">Thông tin chi tiết</h3>
                <div class="col-xs-12" style="border:1px solid #ccc; padding-top: 20px;">
                    <?= $item->description ?>
                </div>
            </div>
        </div>
    </div>
</div>

