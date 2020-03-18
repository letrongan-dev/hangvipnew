<?php
namespace app\controllers;
use Yii;
use yii\easyii\modules\article\api\Article;
use yii\easyii\models\SeoText;
use yii\easyii\helpers\Globals;

class TintucController extends \yii\web\Controller
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
	public function actionIndex($tag = null)
    {
        //return $this->render('index');
        $slug="tin-tuc";
        $cat = Article::cat($slug);
        if(!$cat){
            throw new \yii\web\NotFoundHttpException('Article category not found.');
        }
		if(isset($_GET['page'])){  
			if($_GET['page']!=1){
				$trang=" - Trang ".$_GET['page'];   
			}else{
				$trang=" - Hangvip.vn";
			}			     
		}else{            
			$trang=" - Hangvip.vn";        
		}        
		$seotext = SeoText::find()->where('item_id ='.$cat->id)->andFilterWhere(['like', 'class', 'yii\easyii\modules\article\models\Category'])->one();        if($seotext){                \Yii::$app->view->registerMetaTag([                        'name' => 'description',                        'content' => $seotext['description'].$trang                ]);                \Yii::$app->view->registerMetaTag([                        'name' => 'keywords',                        'content' => $seotext['keywords'].$trang                ]);          }
        return $this->render('index', [						
			'title'=>$seotext['title'].$trang,			
            'cat' => $cat,
            'items' => $cat->items(['tags' => $tag, 'pagination' => ['pageSize' => 10]])
        ]);
        
    }
	public function actionView($slug) {
     
        $article = Article::get($slug); 
        if (!$article) {
            throw new \yii\web\NotFoundHttpException('Article not found.');
        }
        $seotext = SeoText::find()->where('item_id =' . $article->id . ' and class like "%article%"  and class like "%Item%"')->one();
        if ($seotext) {
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
                    'title' => $seotext['title'],
                    'article' => $article
        ]);
    }
}
