<?php
use yii\easyii\modules\page\api\Page;

$page = Page::get('gioi-thieu');
$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<div class="breadcrumbs">
    <a href="<?php echo SITE_PATH;?>"><?=Yii::t('app', 'Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <a href="<?php echo SITE_PATH;?>/gioi-thieu.html"><?=Yii::t('app', 'Giới thiệu')?></a>    
</div>
<h3 class="col-xs-12 paddingleftright" style="margin:0px; padding:20px 0px;"><a style="text-decoration:none;color:#000;" href="<?=SITE_PATH?>/gioi-thieu.htm"><?=Yii::t('app', 'Giới thiệu')?></a></h3>
<div class="col-xs-12 paddingleftright">
	<?=$page->text?>
</div>