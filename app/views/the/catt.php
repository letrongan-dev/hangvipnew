<?php

use yii\helpers\Html;
use yii\easyii\helpers\Image;
use yii\easyii\modules\catalog\api\Catalog;
use yii\widgets\LinkPager;

$this->title = $cat->seo('title', $cat->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = $cat->model->title;
$parent_cat = Catalog::cat($cat->tree);
$html_breadcrumb = '';

if ($cat->model->category_id <> $cat->tree and $cat->depth <> 0) {
    $html_breadcrumb = '<a href="' . SITE_PATH . '/' . $parent_cat->model->slug . '.html">' . $parent_cat->title . '</a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp';
}
?>
<div class="col-xs-12 margin-top20">
    <div class="breadcrumbs-card">
        <a href="<?= SITE_PATH ?>">Trang chủ</a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
        <?= $html_breadcrumb ?>
        <a href="<?= SITE_PATH . '/' . $cat->slug . '.html' ?>"><?= $cat->title ?></a>
    </div>
</div>
<div class="col-xs-12"><h1 class="title-card"><a href="#"><span class="glyphicon glyphicon-play"></span><span class="glyphicon glyphicon-play"></span><?= $cat->title ?></a></h1></div>
<div class="col-xs-12" style="padding:20px 0 0 0;"> 
    <?php
    $i = 1;
    if (count($items)) {
        echo '<div class="col-xs-12 slider-row">';
        foreach ($items as $item) {
            echo $this->render("_item_trant", ['item' => $item, 'i' => $i, 'tong' => count($items), 'addToCartForm' => $addToCartForm]);
            $i++;
        }
        echo '</div>';
    } else {
        
    }
    ?>
    <?php echo LinkPager::widget(['pagination' => $pages]); ?>
</div>