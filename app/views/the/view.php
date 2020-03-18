<?php
use app\models\AddToCartForm;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\helpers\Image;
use yii\easyii\models\Photo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\StringHelper;
use yii\easyii\modules\catalog\models\Price;
use yii\easyii\modules\page\api\Page;
use yii\easyii\modules\catalog\models\Item;

$page = Page::get('chi-tiet');

$notation = Yii::$app->session->get('notation');
$asset = app\assets\AppAsset::register($this);
$addToCartForm = new \app\models\AddToCartForm();
$this->title = $item->seo('title', $item->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = ['label' => $item->cat->title, 'url' => ['shop/cat', 'slug' => $item->cat->slug]];
$this->params['breadcrumbs'][] = $item->model->title;
?>
<div class="breadcrumbs">
    <a href="<?= SITE_PATH ?>"><?=Yii::t('app','Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <?php
    $category = Catalog::cat($item->category_id);
    if ($category->depth == 0) {
        ?>
        <a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.htm'; ?>"><?php echo $category->title; ?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <?php } else { ?>
        <?php $category1 = Catalog::cat($category->tree); ?>
        <a href="<?php echo SITE_PATH; ?>/<?= $category1->slug . '.htm'; ?>"><?php echo $category1->title; ?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
        <a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.htm'; ?>"><?php echo $category->title; ?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <?php } ?>
    <a href="<?= SITE_PATH . '/' . $item->slug . '.html' ?>"><?= $item->title ?></a>    
</div>
<div class="col-xs-12 paddingleftright content_view_product">
    <div class="col-xs-12 paddingleftright">
        <h2 class="title-card" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
					<?= Html::a($item->title, ['the/chitiet', 'slug' => $item->slug], ['style' => 'color:#333;']) ?> 
				</h2>
        <style>
            .title-card{margin-bottom:20px;}
            .title-card a:hover{text-decoration: none}
            .spanct{font-size: 14px; font-weight: bold; color: #333;}
            .detai_img{}
        </style>
        
        <div class="col-xs-12 paddingleftright content_product">
            <div class="col-sm-4 col-xs-12 paddingleftright detai_img">
                <div style="text-align: center; padding-bottom: 20px;"><img style="max-width: 100%;" src="<?=SITE_PATH.$item->image;?>"></div>
                <div class="demo-gallery" style="padding-left: 5px;">
                    <ul id="lightgallery" class="list-unstyled">
                        <li data-src="<?=SITE_PATH.$item->image;?>" data-sub-html="">
							<a href="" >
								<img style="width: 100%; max-width: 100%; max-height: 100%; margin: auto;" class="img-responsive" src="<?=SITE_PATH.$item->image;?>" />
							</a>
						</li>
						<?php
                        $album = \yii\easyii\models\Photo::find()->where("item_id=" . $item->id)->orderBy("order_num DESC")->all();
                        if ($album) {
                                foreach ($album as $alb) {
                                        ?>
                                        <li data-src="<?= SITE_PATH ?>/<?= $alb->image ?>" data-sub-html="">
                                            <a href="" >
                                                <img style="width: 100%; max-width: 100%; max-height: 100%; margin: auto;" class="img-responsive" src="<?= SITE_PATH ?>/<?= $alb->image ?>" />
                                            </a>
                                        </li>
                                <?php } ?>
                        <?php } ?>
                    </ul>
                </div><div class="clearfix"></div>
            </div>
            <div class="col-sm-8 col-xs-12 detai_img_info">    
				<?php if ($item->video != "") { ?> 
					<?php
					$str=$item->video;
					$vitricat=strpos($str,"?v=");
					$chuoi1=substr($str,  $vitricat+3, strlen($str));        
					$vitricat1=strpos($chuoi1,"&");
					if($vitricat1>0){
						$kq=substr($chuoi1,  0, $vitricat1);
					}else{
						$kq=$chuoi1;
					}
					?>
					<iframe style="margin-bottom:10px;" id="videoArea" height="250px" width="100%" src="https://www.youtube.com/embed/<?=$kq?>" frameborder="0" allowfullscreen></iframe>                
				<?php } ?>
				<p> 
                    <span class="spanct"><?=Yii::t('app','Danh mục')?></span>
                    <?php
                    $category = Catalog::cat($item->category_id);
                    if ($category->depth == 0) {
                        ?>    
                    <span class="spanct"><a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.htm'; ?>"><?php echo $category->title; ?></a></span>
                    <?php } else { ?>
                        <?php $category1 = Catalog::cat($category->tree); ?>
                    <span class="spanct"><a href="<?php echo SITE_PATH; ?>/<?= $category1->slug . '.htm'; ?>"><?php echo $category1->title; ?></a></span>, 
                    <span class="spanct"><a href="<?php echo SITE_PATH; ?>/<?= $category->slug . '.htm'; ?>"><?php echo $category->title; ?></a></span>
                    <?php } ?>
                </p>
                <p>
                    <span class="spanct"><?=Yii::t('app','Giá bán')?></span> 
                        <?php 
						$price= Price::getPriceProductShow($item->id);
						$item_info=Item::findOne($item->id);
						?>
                        <?php if($item_info['price']==0){?>
                            <span class="paddingleftright price_no_km"><?=$price?> VND</span>
                        <?php }else{?>
                            <span class="price">&nbsp;<?=$price?> VND&nbsp;</span>
                            <span class="pricekm"><?=number_format($item_info['price'], 0, ',', '.')?> VND</span>
                        <?php }?>
                </p>
                
				<?php
					if($item_info['status_product']==0){
				?>				
					<?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->id]), 'options' => ['style' => 'padding-bottom:10px;']]); ?>                
					<?= $form->field($addToCartForm, 'count', ['options' => ['class' => 'col-lg-2 col-xs-10 paddingleftright']])->hiddenInput(['maxlength' => 255, 'class' => 'form-control input-sm', 'style' => 'padding:5px; border-radius:2px;'])->label(false); ?>
					<?= $form->field($addToCartForm, 'item_id', ['options' => ['style' => 'height:0;margin:0;']])->hiddenInput(['value' => $item->id])->label(false) ?>
					<div class="clearfix"></div>
					<?= Html::submitButton(Yii::t('app','Đặt mua'), ['class' => 'btn btn-primary','style' => 'border-radius:2px;']) ?>
					<div class="clearfix"></div>
					<?php ActiveForm::end(); ?>				
				<?php }else{?>
					<button class="btn btn-primary" style="border-radius:2px;" disabled="disabled"><?=Yii::t('app','Đặt mua')?></button>
					<i style="color: #a3a3a3;"><?=Yii::t('app','hết hàng')?></i>
				<?php }?>
				<div style="border-bottom: 1px dashed #ccc;margin-top: 10px;margin-bottom: 20px;"></div>				
				<div>
					<?=$page->text?>
				</div>
            </div>
        </div>
        <div class="detail_product">
            <style>
                .tabs-top {margin-bottom:25px;}
                .nav-tabs {border: 0;}
                .tabs-4 .nav-tabs > li {border: 0;margin-right: 3px;}
                .nav-tabs > li > a {
                    width:100%;
                    border:0;
                    background:#e1e1e1;
                    color: #333;
                    border-radius: 0;
                    text-align: center;
                }
                .nav-tabs > li > a:hover, .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
                    background: #337ab7;
                    border:0;
                    color: #fff;
                }
                .nav-tabs>li.active> a {
                    border: 0;
                    background: #337ab7;
                    color: #fff;
                }
                #tab_chitiet ul,#tab_video ul{padding-left:30px;}
            </style>

            <div class="tabs-wrapper tabbable tabs-top tabs-4">
                <ul class="nav nav-tabs" style="padding-bottom: 1px; border-bottom: 1px solid #337ab7;">
                    <li class="active tab-1">
                        <a href="#tab_chitiet" data-toggle="tab"><?=Yii::t('app','Chi tiết sản phẩm')?></a>
                    </li>
                    <!--<li class="tab-2">
                        <a href="#tab_video" data-toggle="tab">Video</a>
                    </li>-->
                </ul>
                <div class="tab-content" style="padding:10px;">
                    <div id="tab_chitiet" class="tab-pane fade in active"><?=$item->description?></div><!-- .tab (end) -->
                    <!--<div id="tab_video" class="tab-pane fade ">
						<div style="margin-top: 10px; text-align: center;">
							<?php if ($item->video != "") { ?> 
								<?php
								$str=$item->video;
								$vitricat=strpos($str,"?v=");
								$chuoi1=substr($str,  $vitricat+3, strlen($str));        
								$vitricat1=strpos($chuoi1,"&");
								if($vitricat1>0){
									$kq=substr($chuoi1,  0, $vitricat1);
								}else{
									$kq=$chuoi1;
								}
								?>
								<iframe id="videoArea" height="450px" width="100%" src="https://www.youtube.com/embed/<?=$kq?>" frameborder="0" allowfullscreen></iframe>                
							<?php } ?>
						</div><div class="clearfix"></div>
					</div><!-- .tab (end) -->
                </div><!-- .tab-content (end) --></div>            
        </div>        
    </div>
</div>
<div class="col-xs-12 paddingleftright product_other">
    <h2 class="title-card" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;"><?=Yii::t('app','Sản phẩm liên quan')?></h2>
    <?php 
    if (count($items)) {
        foreach ($items as $item) {	                    
                echo $this->render("_item_tran", ['items' => $item]);
        }
    }
    ?>
</div>

<link href="<?= SITE_PATH ?>/uploads/dist/css/lightgallery.css" rel="stylesheet">
<style type="text/css">     
    #lightgallery li a { height: 75px !important;line-height: 75px !important;}
    #lightgallery li {width: 23%; margin-right: 2.0%;}
    .demo-gallery > ul {margin-bottom: 0;}
    .demo-gallery > ul > li {float: left;margin-bottom: 15px;margin-right: 20px;width: 200px;}
    .demo-gallery > ul > li a {display: block;overflow: hidden;	position: relative;text-align: center;}
    .demo-gallery > ul > li a > img {-webkit-transition: -webkit-transform 0.15s ease 0s;
            -moz-transition: -moz-transform 0.15s ease 0s;-o-transition: -o-transform 0.15s ease 0s;
            transition: transform 0.15s ease 0s;-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);height: 100%;width: 100%; border: 1px solid #ccc; padding: 1px;}
    .demo-gallery > ul > li a:hover > img {-webkit-transform: scale3d(1.1, 1.1, 1.1);transform: scale3d(1.1, 1.1, 1.1);}
    .demo-gallery > ul > li a:hover .demo-gallery-poster > img {opacity: 1;}
    .demo-gallery > ul > li a .demo-gallery-poster {background-color: rgba(0, 0, 0, 0.1);bottom: 0;left: 0;position: absolute;right: 0;top: 0;-webkit-transition: background-color 0.15s ease 0s;
            -o-transition: background-color 0.15s ease 0s;transition: background-color 0.15s ease 0s;}
    .demo-gallery > ul > li a .demo-gallery-poster > img {left: 50%;margin-left: -10px;margin-top: -10px;opacity: 0;position: absolute;top: 50%;-webkit-transition: opacity 0.3s ease 0s;
            -o-transition: opacity 0.3s ease 0s;transition: opacity 0.3s ease 0s;}
    .demo-gallery > ul > li a:hover .demo-gallery-poster {background-color: rgba(0, 0, 0, 0.5);}
    .demo-gallery .justified-gallery > a > img {-webkit-transition: -webkit-transform 0.15s ease 0s;-moz-transition: -moz-transform 0.15s ease 0s;-o-transition: -o-transform 0.15s ease 0s;transition: transform 0.15s ease 0s;-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);height: 100%;width: 100%;}
    .demo-gallery .justified-gallery > a:hover > img {-webkit-transform: scale3d(1.1, 1.1, 1.1);transform: scale3d(1.1, 1.1, 1.1);}
    .demo-gallery .justified-gallery > a:hover .demo-gallery-poster > img {opacity: 1;}
    .demo-gallery .justified-gallery > a .demo-gallery-poster {background-color: rgba(0, 0, 0, 0.1);bottom: 0;left: 0;position: absolute;right: 0;top: 0;-webkit-transition: background-color 0.15s ease 0s;
            -o-transition: background-color 0.15s ease 0s;transition: background-color 0.15s ease 0s;}
    .demo-gallery .justified-gallery > a .demo-gallery-poster > img {left: 50%;margin-left: -10px;margin-top: -10px;opacity: 0;position: absolute;top: 50%;-webkit-transition: opacity 0.3s ease 0s;-o-transition: opacity 0.3s ease 0s;transition: opacity 0.3s ease 0s;}
    .demo-gallery .justified-gallery > a:hover .demo-gallery-poster {background-color: rgba(0, 0, 0, 0.5);}
    .demo-gallery .video .demo-gallery-poster img {height: 48px;margin-left: -24px;margin-top: -24px;opacity: 0.8;width: 48px;}
    .demo-gallery.dark > ul > li a {border: 3px solid #04070a;}
    .home .demo-gallery {padding-bottom: 80px;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>        
<script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready(function ($) {
                $('#lightgallery').lightGallery();
        });
</script>
<script src="<?= SITE_PATH ?>/uploads/dist/js/picturefill.min.js"></script>
<script src="<?= SITE_PATH ?>/uploads/dist/js/lightgallery-all.min.js"></script>
<script src="<?= SITE_PATH ?>/uploads/dist/js/jquery.mousewheel.min.js"></script>