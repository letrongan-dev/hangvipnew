<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\catalog\models\Category;
$asset = app\assets\AppAsset::register($this);
?>
<div class="divtotop">
	<div id="toTop">
		<span>TOP</span><br/>
		<span class="glyphicon glyphicon-arrow-up"></span>
	</div>
</div>
<div class="container container_footer">
	<?php
		$category= Category::find()->where("status=1 and depth=0")->orderBy("order_num DESC")->all();
		if(count($category)>0){
			$i=1;
			foreach ($category as $cate){
	?>
	<div class="col-xs-2 paddingleftright footer<?=$i?>">
		<h4><a href="<?=SITE_PATH?>/<?=$cate->slug?>.htm"><?=$cate->title?></a></h4>
		<?php 
		$category_sub= Category::find()->where("status=1 and depth=1 and tree=".$cate->category_id)->orderBy("lft ASC")->all();
		if(count($category_sub)>0){ 
		?>
		<ul>
			<?php foreach ($category_sub as $cate_sub){?>
			<li><a href="<?=SITE_PATH?>/<?=$cate_sub->slug?>.htm"><?=$cate_sub->title?></a></li>
			<?php }?>
		</ul>
		<?php }?>
	</div>
		<?php $i++;}?>
	<?php }?>
	
	<div class="col-xs-2 paddingleftright footer6">
		<h4>Liên hệ</h4>
		<div style="color: #fff;">
			<p>Copyright: Viet Aus Ecommerce Pty.Lty</p> 
			<p><?=Yii::t('app','Địa chỉ')?>: 52 Newell st, Footscray, Vic 3011</p>
			<p>Vp VN: 334-336 Tân Sơn Nhì, Tân Phú, TP HCM</p>
			<p style="line-height: 21px;"><?=Yii::t('app','Số điện thoại')?>: (+61) 478 103 798 (Australia)<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0938 527 169 (Việt Nam)</p>
			<p>
				<a target="_blank" href="https://www.facebook.com/hangvip.vn/"><img src="<?=$asset->baseUrl?>/images/icon_face.png" alt="Hàng vip" /></a>
			</p>
		</div>
	</div>
</div>