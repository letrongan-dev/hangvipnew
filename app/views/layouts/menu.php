<?php
use yii\easyii\modules\catalog\models\Category;
?>
<div class="col-sm-12 hidden-xs paddingleftright menu">
	<div class="col-xs-12 paddingleftright">
		<div class="col-lg-2 col-md-1 hidden-sm hidden-xs menu_l">&nbsp;</div>
		<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12 menu_c">			
			<?php
				$category= Category::find()->where("status=1 and depth=0")->orderBy("order_num DESC")->all();
				if(count($category)>0){
					foreach ($category as $cate){
			?>
				<div class="col-xs-2 paddingleftright menu_cat1" style="position: relative;">
					<a href="<?=SITE_PATH?>/<?=$cate->slug?>.htm"><?=$cate->title?></a>
					<?php
					$category_sub= Category::find()->where("status=1 and depth=1 and tree=".$cate->category_id)->orderBy("lft ASC")->all();
					if(count($category_sub)>0){
					?>
					<span style="position: absolute; top: 16px; left: 50%; color: #242424;" class="glyphicon glyphicon-triangle-top">&nbsp;</span>
					<ul class="menu_cat_sub">
						<?php foreach ($category_sub as $cate_sub){?>
						<li><a href="<?=SITE_PATH?>/<?=$cate_sub->slug?>.htm"><?=$cate_sub->title?></a></li>
						<?php }?>
					</ul>
					<?php }?>
				</div>
				<?php }?>
			<?php }?>
			<div class="col-xs-2 paddingleftright menu_cat1" style="position: relative;">
				<a href="<?=SITE_PATH?>/tin-tuc.html">Tin tức</a>
			</div>
		</div>
		<div class="col-lg-2 col-md-1 hidden-sm hidden-xs menu_r">&nbsp;</div>
	</div>
</div><div class="clearfix"></div>
<div class="navigation-menu clearfix">
	<div class="navbar navbar-inverse navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse-right2">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="hidden-lg hidden-md hidden-sm navbar-brand">       
					<div style="color: #fff; font-weight: bold;">Danh mục sản phẩm</div>
				</div>
			</div>
			<div class="navbar-collapse navbar-collapse-right2 collapse">
				<ul class="nav navbar-nav">
					<?php
					if(count($category)>0){
						foreach ($category as $cate){
					?>
					<li class="level0">
						<a href="<?=SITE_PATH?>/<?=$cate->slug?>.htm" title="<?=$cate->title?>">
							<?=$cate->title?>
						</a>
						<?php 
						$category_sub= Category::find()->where("status=1 and depth=1 and tree=".$cate->category_id)->orderBy("lft ASC")->all();
						if(count($category_sub)>0){ 
						?>
						<ul class="menu_cat_sub_mobi">
							<?php foreach ($category_sub as $cate_sub){?>
							<li><a href="<?=SITE_PATH?>/<?=$cate_sub->slug?>.htm"><?=$cate_sub->title?></a></li>
							<?php }?>
						</ul>
						<?php }?>
					</li>
					<?php }}?>
					<li class="level0">
						<a href="<?=SITE_PATH?>/gioi-thieu.html" title="Giới thiệu">
							Giới thiệu
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>