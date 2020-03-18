<?php

namespace app\controllers;

use app\models\GadgetsFilterForm;
use Yii;
use yii\easyii\helpers\Globals;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;
use yii\data\Pagination;
use yii\easyii\models\SeoText;
use yii\web\NotFoundHttpException;

class TheController extends \yii\web\Controller {
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

    public function actionIndex() {
        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Bán thẻ game online'
        ]);
        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'thẻ game,thẻ điện thoại'
        ]);
        return $this->render('index');
    }

    public function actionCat($slug) {

        $filterForm = new GadgetsFilterForm();
        $cat = Catalog::cat($slug);
        $catalog = Catalog::cats();
		
		$item_id=$cat->id;
		if($item_id!=""){
			$seotext = SeoText::find()->where('item_id ='.$item_id.' and class like "%catalog%" and class like "%Category%"')->one();
			\Yii::$app->view->registerMetaTag([
					'name' => 'description',
					'content' => $seotext['description']
			]);
			\Yii::$app->view->registerMetaTag([
					'name' => 'keywords',
					'content' => $seotext['keywords']
			]);
		}
        $itemchildren = null;
        foreach ($catalog as $itemcat) {
            if ($itemcat->category_id == $cat->id && $itemcat->children) {
                $itemchildren = $itemcat->children;
            }
        }
        if (!$cat) {
            throw new NotFoundHttpException('Shop category not found.');
        }
        $filters = null;
        if ($filterForm->load(Yii::$app->request->get()) && $filterForm->validate()) {
            $filters = $filterForm->parse();
        }
        if ($itemchildren) {
            return $this->render('cat', [
				'cat' => $cat,
				'items' => $cat->itemschild([
					'pagination' => ['pageSize' => 20],
					'filters' => $filters
						], $itemchildren),
				'filterForm' => $filterForm,
				'addToCartForm' => new \app\models\AddToCartForm(),
            ]);
        }

        return $this->render('cat', [
			'cat' => $cat,
			'items' => $cat->items([
				'pagination' => ['pageSize' => 20],
				'filters' => $filters
			]),
			'filterForm' => $filterForm,
			'addToCartForm' => new \app\models\AddToCartForm(),
        ]);
    }

    public function actionSearch($text) {
        $text = filter_var($text, FILTER_SANITIZE_STRING);

        return $this->render('search', [
                    'text' => $text,
                    'items' => Catalog::items([
                        'where' => ['or', ['like', 'title', $text], ['like', 'description', $text]],
                    ])
        ]);
    }

    public function actionChitiet($slug) {		
        $item = Catalog::get($slug);
        if (!$item) {
            throw new NotFoundHttpException('Item not found.');
        }
        $item_id = $item->item_id;
        $seotext = SeoText::find()->where('item_id =' . $item_id . ' and class like "%catalog%"')->one();
        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $seotext['description']
        ]);
        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $seotext['keywords']
        ]);
        $cat = Catalog::cat($item->category_id);                
		return $this->render('view', [                    
			'item' => $item,                    
			'cat' => $cat,                    
			'items' => $cat->itemsrandom([                        
				'pagination' => ['pageSize' => 8],                        
				//'filters' => $filters                    
				],$item->category_id,$item_id),                    
			//'filterForm' => $filterForm,                    
			'addToCartForm' => new \app\models\AddToCartForm()        
		]);
    }

}
