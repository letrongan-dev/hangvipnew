<?php
use yii\easyii\modules\catalog\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\catalog\models\Price;
use yii\easyii\helpers\Globals;
$this->title = "Hàng hiệu xách tay - Hangvip.vn";
?>

<?php
    $catalog = Category::find()->where("status=1 and depth=0")->orderBy("order_num DESC")->all();
    if(count($catalog)>0){
        foreach ($catalog as $cate) {
            $array_cate=Category::getListcategorysub($cate->tree);        
            $list_items= Item::find()->where("status=1 and category_id in (".$array_cate.")")->orderBy("time DESC")->limit(8)->all();
			if(count($list_items)>0){
			?>
            <h3 class="col-xs-12 title_category">
				<a href="<?=SITE_PATH?>/<?=$cate->slug?>.htm"><?=$cate->title?></a>
				<?php
                $list_sub= Category::find()->where("category_id!=".$cate->category_id." and tree=".$cate->category_id)->orderBy("lft ASC")->all();
                $tong= count($list_sub);
                if($tong>0){
                ?>
                    <span style="font-size: 13px;">>></span>
                    <?php $i=1;?>
                    <?php foreach ($list_sub as $l_s){?>
                        <a style="font-size: 13px;" href="<?=SITE_PATH?>/<?=$l_s->slug?>.htm"><?=$l_s->title?></a> 
                        <?php if($i!=$tong){?><span style="font-size: 13px;">|</span><?php }?>
                    <?php $i++;}?>
                <?php }?>
			</h3>
            <?php            
            foreach ($list_items as $items){
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
                <p class="product_title" style="margin-bottom: 3px; "><a href="<?=SITE_PATH?>/<?=$items->slug?>.html"><?= $items->title?></a></p>
				<p style="margin-bottom: 3px; color: #a3a3a3;"><i><span>
				<?php if($items->status_product==0){ echo Yii::t('app', 'còn hàng');}else{ Yii::t('app', 'còn hàng');}?>
				</span></i></p>
                <p class="product_trademark">
                                <?= Yii::t('app','Thương hiệu')?>: <?php if($items->thuonghieu==""){ echo Category::getNamecategory($items->category_id);}else{echo $items->thuonghieu;}?>				
				</p>
                <div class="col-xs-12 paddingleftright">
                    <?php
                    $price= Price::getPriceProductShow($items->item_id);
					//echo $items->price;
                    ?>
                    <?php if($items->price==0){?>
                        <div class="col-xs-12 paddingleftright price_no_km"><?=$price?> VND</div>
                    <?php }else{?>
                        <div class="col-xs-6 paddingleftright price">&nbsp;<?=$price?> VND&nbsp;</div>
                        <div class="col-xs-6 paddingleftright pricekm"><?=number_format($items->price, 0, ',', '.')?> VND</div>
                    <?php }?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php }?>
            <div class="col-xs-12 viewmore">
                <a style="text-decoration: underline; color: #000; font-size: 14px; font-weight: bold;" href="<?=SITE_PATH?>/<?=$cate->slug?>.htm"><?=Yii::t('app', 'Xem thêm')?> <?=$cate->title?> ></a>
            </div><div class="clearfix"></div>
        <?php        
			}
        }
    }
?>






