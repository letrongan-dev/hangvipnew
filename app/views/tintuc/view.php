<?php

use yii\easyii\modules\article\api\Article;
use yii\helpers\Url;
use yii\helpers\Html;

$asset = app\assets\AppAsset::register($this);
?>
<div class="breadcrumbs">
	<a href="<?= SITE_PATH ?>"><?=Yii::t('app','Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
	<a href="<?= SITE_PATH . '/tin-tuc.html' ?>"><?=Yii::t('app','Tin tức')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
	<a href="<?= SITE_PATH . '/detail-news/'. $article->slug?>"><?=$article->title?></a>
</div>
<div class="col-xs-12 paddingleftright">
    <div class="news-detail">
        <h1 style="margin: 0px; padding-bottom: 20px; padding-top: 20px; text-align: center; font-size:22px; font-weight:bold;">
            <?php
            echo $article->seo('h1', $article->title);
            $this->title = $title;
            ?>
        </h1>
        <div class="news-detail-content">
            <?php echo $article->text ?>
        </div>
    </div>
    <?php
    $item = \yii\easyii\modules\article\models\Item::find()->where('slug = "' . $article->slug . '"')->one();
    $id_cate = $article->category_id;
    $articlecategory = Article::ItemOrther($id_cate, $item->item_id);
    if (count($articlecategory) > 0) {
        ?>
        <div>
            <h4 class="tinlienquanarticles" ><?=Yii::t('app','Tin liên quan')?></h4>
            <ul class="orther">
                <?php foreach ($articlecategory as $orther) { ?>
					<li> 
						<a href="<?php echo SITE_PATH ?>/detail-news/<?php echo $orther->slug ?>" title="<?php echo $orther->title; ?>">
							<?php echo $orther->title; ?>
						</a>
					</li>
                <?php } ?>
            </ul>        
        </div>
    <?php } ?>
    <div class="clearfix"></div>
</div>