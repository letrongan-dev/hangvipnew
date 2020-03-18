<?php

namespace app\controllers;

use yii\easyii\modules\article\api\Article;
use yii\easyii\helpers\Globals;

class ArticlesController extends \yii\web\Controller
{
     public function init() {
        $location = Yii::$app->geoip->lookupLocation();
        $currency = Globals::GetNotation($location->countryCode);
//       var_dump($location->countryCode);
//       die();             
        if($location->countryCode!="VN"){
                      
            Yii::$app->language = 'en';
        }
        else{
          
            Yii::$app->language ='vn';  
        }
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCat($slug, $tag = null)
    {
        $cat = Article::cat($slug);
        if(!$cat){
            throw new \yii\web\NotFoundHttpException('Article category not found.');
        }
        return $this->render('cat', [
            'cat' => $cat,
            'items' => $cat->items(['tags' => $tag, 'pagination' => ['pageSize' => 2]])
        ]);
    }

    public function actionView($slug)
    {
        
        $str2 = Globals::getCurrentPageURL();
		 
        $article = Article::get($slug);
        if (!$article) {
            if (strlen(strstr($str2, "%E2%80%8B")) > 0) {
                $slug = preg_replace('/\p{C}+/u', "", $slug);
                return $this->redirect(SITE_PATH . "/detail-news/" . $slug);
            }
            if (strlen(strstr($str2, "%25E2%2580%258B")) > 0) {
                $slug = preg_replace('/\p{C}+/u', "", $slug);
                return $this->redirect(SITE_PATH . "/detail-news/" . $slug);
            }
			if (strlen(strstr($str2, "%E2%80%8")) > 0) {
                $slug = preg_replace('/\p{C}+/u', "", $slug);
                return $this->redirect(SITE_PATH . "/detail-news/" . $slug);
            }			
        }		               
        			
		if (!Yii::$app->session->get('notation')) {
            $location = Yii::$app->geoip->lookupLocation();
            $currency = Globals::GetNotation($location->countryCode);
            Yii::$app->session->set('notation', $currency);
        }			
		if(Yii::$app->session->get('notation') == 'VND' && $article->id_khuyenmai==2){			
		throw new \yii\web\NotFoundHttpException('Article not found.');		}
        if(!$article){
            throw new \yii\web\NotFoundHttpException('Article not found.');
        }
		$seotext = SeoText::find()->where('item_id ='.$article->id.' and class like "%article%"')->one();
		if($seotext){
			\Yii::$app->view->registerMetaTag([
				'name' => 'description',
				'content' => $seotext['description']
			]);
			\Yii::$app->view->registerMetaTag([
				'name' => 'keywords',
				'content' => $seotext['keywords']
			]);  
		}
        return $this->render('view', [
            'article' => $article
        ]);
    }

}
