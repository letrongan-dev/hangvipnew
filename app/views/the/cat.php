<?php

use yii\helpers\Html;
use yii\easyii\helpers\Image;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\catalog\models\Category;
$this->title = $cat->seo('title', $cat->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = $cat->model->title;
$parent_cat = Catalog::cat($cat->tree);
$html_breadcrumb = '';

if ($cat->model->category_id <> $cat->tree and $cat->depth <> 0) {
    $html_breadcrumb = '<a href="' . SITE_PATH . '/' . $parent_cat->model->slug . '.htm">' . $parent_cat->title . '</a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp';
}
?>
<div class="breadcrumbs">
	<a href="<?= SITE_PATH ?>">Trang chủ</a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
	<?= $html_breadcrumb ?>
	<a href="<?= SITE_PATH . '/' . $cat->slug . '.htm' ?>"><?= $cat->title ?></a>
</div>
<h3 class="title_category_cate">
	<a style="text-decoration:none;color:#000;" href="<?= SITE_PATH . '/' . $cat->slug . '.htm' ?>"><?= $cat->title ?></a>
	<?php
    $list_sub= Category::find()->where("category_id!=".$cat->id." and tree=".$cat->id)->orderBy("lft ASC")->all();
    $tong= count($list_sub);
    if($tong>0){
    ?>
        <span style="font-size: 13px;">>></span>
        <?php $i=1;?>
        <?php foreach ($list_sub as $l_s){?>
            <a style="font-size: 13px;color:#000;" href="<?=SITE_PATH?>/<?=$l_s->slug?>.htm"><?=$l_s->title?></a> 
            <?php if($i!=$tong){?><span style="font-size: 13px;">|</span><?php }?>
        <?php $i++;}?>
    <?php }?>
</h3>
<div class="col-xs-12 paddingleftright"> 
	<?php
	if (count($items)) {
		foreach ($items as $item) {		
			echo $this->render("_item_tran", ['items' => $item]);
		}
	}
	?>
</div>
<?= $cat->pages() ?>
