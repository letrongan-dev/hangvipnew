<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\modules\carousel\models\Carousel;

$carousel= Carousel::find()->where("status=1")->orderBy("order_num DESC")->all();
if(count($carousel)>0){
?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<?php 
		$i=0;
		foreach($carousel as $car){
		?>
		<li data-target="#myCarousel" data-slide-to="<?=$i?>" class="<?php if($i==0){echo "active";}?>"></li>
		<?php $i++;}?>
	</ol>
	<!-- Wrapper for slides -->
	<div class="carousel-inner">
		<?php 
		$i=0;
		foreach($carousel as $car){
		?>
		<div class="item <?php if($i==0){echo "active";}?>">
			<a href="<?=$car->link;?>"><img src="<?= SITE_PATH.$car->image;?>" alt="<?=$car->title?>"></a>
		</div>
		<?php $i++;}?>
	</div>
	<!-- Left and right controls -->
	<a class="left carousel-control" style="text-align: left;" href="#myCarousel" data-slide="prev">
		<span class="glyphicon glyphicon-arrow-left"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#myCarousel" data-slide="next">
		<span class="glyphicon glyphicon-arrow-right"></span>
		<span class="sr-only">Next</span>
	</a>
</div><div class="clearfix"></div>
<?php }?>