<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\catalog\models\Price;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;
?>
<div class="col-xs-3 rows_product_home">
	
	<?php if($items->phantram_km!=0){?>
	<div style="position: absolute; top: 10px; right: 10px;">
		<img style="width: 50px; height: 50px; border-radius: 5px;" src="<?=SITE_PATH?>/uploads/saleoff2.png" />
		<p style="color: #fff; font-size: 16px; font-weight: 600; margin-top: -35px; margin-left: -10px;">
			<span style="letter-spacing: 2px;">-</span><?=$items->phantram_km?>
		</p>
	</div>
	<?php }?>
	
    <a href="<?=SITE_PATH?>/<?=$items->slug?>.html"><img src="<?= SITE_PATH.$items->image?>" /></a>
    <p class="product_title" style="margin-bottom: 3px;"><a href="<?=SITE_PATH?>/<?=$items->slug?>.html"><?= $items->title?></a></p>
	<p style="margin-bottom: 3px; color: #a3a3a3;"><i><span>
				<?php if($items->status_product==0){ echo Yii::t('app','còn hàng');}else{ echo Yii::t('app','hết hàng');}?>
				</span></i></p>
    <p class="product_trademark"><?=Yii::t('app','Thương hiệu')?>: <?php if($items->thuonghieu==""){ echo Category::getNamecategory($items->category_id);}else{echo $items->thuonghieu;}?></p>
    <div class="col-xs-12 paddingleftright">
        <?php
        $price= Price::getPriceProductShow($items->id);
		$item_info=Item::findOne($items->id);
        ?>
        <?php if($item_info['price']==0){?>
            <div class="col-xs-12 paddingleftright price_no_km"><?=$price?> VND</div>
        <?php }else{?>
            <div class="col-xs-6 paddingleftright price">&nbsp;<?=$price?> VND&nbsp;</div>
            <div class="col-xs-6 paddingleftright pricekm"><?=number_format($item_info['price'], 0, ',', '.')?> VND</div>
        <?php }?>
        <div class="clearfix"></div>
    </div>
</div>