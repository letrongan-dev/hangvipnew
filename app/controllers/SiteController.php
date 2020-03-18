<?php

namespace app\controllers;

use amnah\yii2\user\models\User;
use amnah\yii2\user\models\UserCopy;
use Yii;
use yii\easyii\modules\page\models\Page;
use yii\web\Controller;
use app\models\Currencyapi;
use yii\easyii\helpers\Globals;
use amnah\yii2\user\models\Addresses;
use amnah\yii2\user\models\AddressesCopy;
use yii\easyii\modules\usermoney\models\UserHistoryMoney;
use yii\easyii\modules\usermoney\models\UserMoney;
use yii\easyii\modules\gxcuserchecklist\models\Gxcuserchecklist;
use yii\easyii\modules\gxcuserchecklistcount\models\Gxcuserchecklistcount;
use yii\easyii\modules\gxcuserdoiqua\models\Gxcuserdoiqua;
use yii\base\Security;

class SiteController extends Controller {

    public function init() {
        /*if (!Yii::$app->session->get('notation')) {
            $location = Yii::$app->geoip->lookupLocation();
            $currency = Globals::GetNotation($location->countryCode);
            Yii::$app->session->set('notation', $currency);
        }*/
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
               // Yii::$app->language = 'en';
                $currency="VND";
		Yii::$app->session->set('notation', $currency);
        }
        public function behaviors() {
        return array(
            'eauth' => array(
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => array('login'),
            ),
        );
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Giỏ xách hàng hiệu chính hãng, Authentic bags Louis Vuitton , chanel, Gucci, Michael Kors, Coach, furla. Mỹ phẩm, nước hoa. Kem dưỡng da. Sữa bột Xach tay trực tiếp từ úc'
        ]);
        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Giỏ xách hàng hiệu chính hãng, Authentic bags Louis Vuitton , chanel, Gucci, Michael Kors, Coach, furla. Mỹ phẩm, nước hoa,kem dưỡng da,sữa bột Xach tay trực tiếp từ úc'
        ]);
        return $this->render('index', ['addToCartForm' => new \app\models\AddToCartForm()]);
    }

    public function actionTest() {
        $security = new Security();
        $string = Yii::$app->request->post('string');
        $stringHash = '';
        if (!is_null($string)) {
            $stringHash = $security->generatePasswordHash($string);
        }
        return $this->render('test', [
                    'stringHash' => $stringHash,
        ]);
    }

    public function actionLogin() {
        //them doan nay 
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
                    if ($user = User::findByUsername($eauth->getAttribute('email'))) {
                        if (Yii::$app->getUser()->login($user)) {
                            $getaddress = Addresses::findOne(['uid' => Yii::$app->user->id]);
                            if ($getaddress) {
                                Yii::$app->session->destroySession('notation');
                                $notation = Globals::GetNotation($getaddress->country_code);
                                Yii::$app->session->set('notation', $notation);
                            } else {
                                $location = Yii::$app->geoip->lookupLocation();
                                $currency = Globals::GetNotation($location->countryCode);
                                Yii::$app->session->set('notation', $currency);
                            }

                            if (empty($user->password)) {
                                $eauth->redirect(SITE_PATH . '/user/account');
                            }
                            //return $this->goHome();
                            $eauth->redirect();
                        }
                    }

                    $user = new UserCopy();
                    $user->fbuid = $eauth->getAttribute('id');
                    $user->display_name = $eauth->getAttribute('name');
                    $user->username = $eauth->getAttribute('email');
                    $user->email = $eauth->getAttribute('email');

                    $user->setPassword($eauth->id);

                    $user->generateAuthKey();
//                    $user->role_id = 2;
                    $user->status = 1;
                    if ($user->save()) {
                        $getaddress = Addresses::findOne(['uid' => $user->id]);
                        $address = $getaddress ? $getaddress : new AddressesCopy();
                        if (!$getaddress) {
                            $session = Yii::$app->session;
                            $country = $session->get('country');
                            $country->countryName = Yii::$app->geoip->lookupLocation()->countryName;
                            $address->city = $country->city;
                            if (!empty($address->city)) {
                                $address->city = 'unknow';
                            }
                            $address->country_name = $country->countryName;
                            $address->country_code = $country->countryCode;
                            $address->email = $user->email;
                            $address->uid = $user->id;
                            $address->created = time();
                            $address->modified = time();
                            $address->fullname = $user->display_name;
                            $address->zone = $country->timezone;
                            $address->save();
                        }
                        if (Yii::$app->getUser()->login($user, 876777)) {
                            //return $this->goHome();
                            $eauth->redirect();
                        }
                    }
                    $eauth->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {

                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
                //close popup window and redirect to cancelUrl
                $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
        //end
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (Yii::$app->request->get('returnUrl')) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->get('returnUrl'));
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {

        Yii::$app->users->logout();

        return $this->goHome();
    }

    public function actionGetsalecurrency() {
        echo "a";
    }

//    public function actionDiemdanhclick() {
//        if (!isset(Yii::$app->users->id)) {
//            $result = 'false:0';
//        } else {
//            $code = $_POST['code'];
//            if ($code != Yii::$app->users->id) {
//                $result = 'false1:0';
//            } else {
//                $stringdate = date('Y') . date('m') . date('d');
//                $uscheck = Gxcuserchecklist::CheckExitCheck(Yii::$app->users->id, $stringdate);
//                if ($uscheck) {
//                    $result = 'false2:0';
//                } else {
//                    $info = User::find()->where('id=' . Yii::$app->users->id)->one();
//                    $gxcuserchecklist = new Gxcuserchecklist();
//                    $gxcuserchecklist->user_id = Yii::$app->users->id;
//                    $gxcuserchecklist->stringdate = $stringdate;
//                    $gxcuserchecklist->email = $info['email'];
//                    $gxcuserchecklist->date_check = date('Y-m-d');
//                    $gxcuserchecklist->date_created = time();
//                    $gxcuserchecklist->numbercheck = 1;
//                    if ($gxcuserchecklist->save()) {
//                        $checkgxcuserchecklistcount = Gxcuserchecklistcount::CheckExitCheck(Yii::$app->users->id);
//                        if ($checkgxcuserchecklistcount) {
//                            $gxcuserchecklistcount = Gxcuserchecklistcount::find()->where('user_id=' . Yii::$app->users->id)->one();
//                            $total = $gxcuserchecklistcount['number_count'] + 1;
//                            $gxcuserchecklistcount->number_count = $total;
//                            $gxcuserchecklistcount->save();
//                        } else {
//                            $gxcuserchecklistcount = new Gxcuserchecklistcount();
//                            $gxcuserchecklistcount->user_id = Yii::$app->users->id;
//                            $gxcuserchecklistcount->email = $info['email'];
//                            $total = 1;
//                            $gxcuserchecklistcount->number_count = $total;
//                            $gxcuserchecklistcount->save();
//                        }
//                        $namethang = date('Y') . date('m');
//                        $getcountmonth = Gxcuserchecklist::Getcountmonth(Yii::$app->users->id, $namethang);
//                        $result = 'true:' . $total . ":" . $getcountmonth;
//                        // tăng lượt điểm danh
//                    } else {
//                        $result = 'false3:0';
//                    }
//                }
//            }
//        }
//        echo json_encode($result);
//        Yii::$app->end();
//    }

// end xu ly binh 

    public function actionDoiquaclick() {
        if (!isset(Yii::$app->users->id)) {
            $result = 'false:0';
        } else {
            $code = $_POST['code'];
            if ($code != Yii::$app->users->id) {
                $result = 'false1:0';
            } else {
                $checkuser = Gxcuserchecklistcount::CheckExitCheck(Yii::$app->users->id);
                if ($checkuser) {
                    $getdiem = Gxcuserchecklistcount::GetCountDiemDanh(Yii::$app->users->id);
                    if ($getdiem < 7) {
                        $result = 'false3:0';
                    } else {
                        if (6 < $getdiem && $getdiem < 15) {
                            $diemconlai = $getdiem - 7;
                            $quatang = 1; // 7 ngày (7 điểm): sẽ đổi dc 1 thẻ 20k và 1 code quay số tương ứng 1  lần quay
                        }
                        if (14 < $getdiem && $getdiem < 22) {
                            $diemconlai = $getdiem - 15;
                            $quatang = 2; // 15 ngày(15 điểm): sẽ đổi dc 1 thẻ 50k và 1 code quay số tương ứng 2 lần quay
                        }
                        if (21 < $getdiem && $getdiem < 30) {
                            $diemconlai = $getdiem - 22;
                            $quatang = 3; //22 ngày(22 điểm): sẽ đổi dc 1 thẻ 80k và 1 code quay số tương ứng 3  lần quay
                        }
                        if (29 < $getdiem && $getdiem < 500) {
                            $diemconlai = $getdiem - 30;
                            $quatang = 4; //30 ngày(30 điểm): sẽ đổi dc 1 thẻ 150k và 1 code quay số tương ứng 4 lần quay
                        }
                        $info = User::find()->where('id=' . Yii::$app->users->id)->one();
                        $doiqua = new Gxcuserdoiqua();
                        $doiqua->user_id = Yii::$app->users->id;
                        $doiqua->email = $info['email'];
                        $doiqua->quatang = $quatang;
                        $doiqua->date_doi = date('Y-m-d');
                        $doiqua->date_created = time();
                        $doiqua->status = 0;
                        if ($doiqua->save()) {
                            $userchecklistcount = Gxcuserchecklistcount::find()->where('user_id=' . Yii::$app->users->id)->one();
                            $userchecklistcount->number_count = $diemconlai;
                            if ($userchecklistcount->save()) {
                                $result = 'true:' . $diemconlai;
                            } else {
                                $result = 'false5:0';
                            }
                        } else {
                            $result = 'false4:0';
                        }
                    }
                } else {
                    $result = 'false2:0';
                }
            }
        }
        echo json_encode($result);
        Yii::$app->end();
    }

// end xu ly  
}
