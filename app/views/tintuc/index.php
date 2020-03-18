<?php

use yii\helpers\Html;
use yii\helpers\Url;

$asset = app\assets\AppAsset::register($this);
$this->title = $title;
?>
<style>
.news-list{padding-bottom:10px;}
.news-list a{color: #e55c19;text-decoration: none;font-weight: bold;}
.news-list p.new_title{ margin-bottom:5px; height:23px; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;}
.news-list img{ width:110px; height:80px; float:left; margin-right:10px; margin-top:6px; border:1px solid #f2f2f2; border-radius:3px;}
.news-list{ border-bottom: 1px dotted #ccc; margin-bottom: 10px;}
.news-list p.short{ margin-bottom: 0px; padding:7px 0px 10px 0px; line-height: 21px; height:90px; overflow: hidden; margin-top:-3px; text-align:justify;}
.news-list a:hover{ text-decoration: underline;}
</style>
<div class="breadcrumbs">
	<a href="<?= SITE_PATH ?>"><?=Yii::t('app','Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
	<a href="<?= SITE_PATH . '/tin-tuc.html' ?>"><?=Yii::t('app','Tin tức')?></a>
</div>
<h3 class="title_category_cate">
	<a style="text-decoration:none;color:#000;" href="<?= SITE_PATH . '/tin-tuc.html' ?>"><?=Yii::t('app','Tin tức')?></a>
</h3>
<div class="col-xs-12 paddingleftright">
	<?php if (count($items)) : ?>
        <?php
        $i = 1;
        foreach ($items as $article) :
            ?> 
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 paddingleftright news-list" <?php if ($i % 2 != 0) { ?> style="margin-right:2%;" <?php } ?>>
                    <p class="new_title">
                        <a href="<?php echo SITE_PATH ?>/detail-news/<?= $article->slug ?>" title="<?php echo $article->title ?>">
                            <?php echo $article->title ?>
                        </a>
                    </p>		
					<a href="<?php echo SITE_PATH ?>/detail-news/<?= $article->slug ?>" title="<?php echo $article->title ?>">
						<?php if ($article->image == "") { ?>
							<img src="<?php echo $asset->baseUrl; ?>/images/logo.png" alt="<?php echo $article->title ?>" title="<?php echo $article->title ?>" />
						<?php } else { ?>
							<img src="<?php echo SITE_PATH . $article->image; ?>" alt="<?php echo $article->title ?>" title="<?php echo $article->title ?>" />
						<?php } ?>
					</a>
                    <p class="short"><?= $article->short ?></p>
                </div>
                <?php $i++; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Category is empty</p>
    <?php endif; ?>
    <div class="clearfix"></div>
    <?= $cat->pages() ?>
    <div class="clearfix"></div>
</div>