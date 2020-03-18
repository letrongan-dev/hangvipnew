<?php

namespace app\controllers;
use Yii;
use yii\easyii\helpers\Globals;

class GioithieuController extends \yii\web\Controller
{
    public function init() {
        $location = Yii::$app->geoip->lookupLocation();
        $currency = Globals::GetNotation($location->countryCode);
//       var_dump($location->countryCode);
//       die();   
//            
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
}
