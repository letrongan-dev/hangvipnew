
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$model = Yii::$app->getModule("user")->model("LoginForm");
$asset = app\assets\AppAsset::register($this);
?>
<?php
use amnah\yii2\user\models\User;
use amnah\yii2\user\models\Addresses;
use amnah\yii2\user\models\Countries;
use yii\easyii\helpers\Globals;
$clientId = '613484688769-4tu0sa1cdog7cgeu8l9ksnbdtj3avvmb.apps.googleusercontent.com';
$clientSecret = 'TLKRyJ-D_McfOLOOxkuB0tkP'; 
$redirectURL = SITE_PATH; 
$gClient = new Google_Client();
$gClient->setApplicationName('Hangvip.vn');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);
$google_oauthV2 = new Google_Oauth2Service($gClient);


if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['tokenlgin'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['tokenlgin'])) {
	$gClient->setAccessToken($_SESSION['tokenlgin']);
}

if ($gClient->getAccessToken()) {
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	if($gpUserProfile['email']!=""){
		$randompass = Globals::generateRandomString(); 
		if ($user = User::findByUsername($gpUserProfile['email'])) {
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
					header("Location: https://hangvip.vn/cap-nhat-tai-khoan.html");
				}
			}
		}
		$user = new User();
		$user->username = $gpUserProfile['email'];
		$user->email = $gpUserProfile['email'];
		$user->user_url = $gpUserProfile['link'];
		$user->setPassword($randompass);
		$user->generateAuthKey();
		$user->status = 1;
		if ($user->save()) {
			$getaddress = Addresses::findOne(['uid' => $user->id]);
			$address = $getaddress ? $getaddress : new Addresses;
			if (!$getaddress) {                                
				$country = Yii::$app->geoip->lookupLocation();
				$countryname = Countries::find()->where('country_iso_code_2 = "' . $country->countryCode . '"')->one();
				$address->city = $country->city;
				$address->zipCode = $country->postalCode;
				$address->country = $countryname['country_id'];
				$address->country_name = $countryname['country_name'];
				$address->country_code = $country->countryCode;
				$address->email = $user->email;
				$address->uid = $user->id;
				$address->created = time();
				$address->save(false);
			}
			if (Yii::$app->getUser()->login($user)) {
				header("Location: http//localhost:8080/hangvip/");
			}
		}
		
	}else{
		echo "Không lấy được Email từ google";
	}
}
//$authUrl = $gClient->createAuthUrl();
		//echo $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'">gggg</a>';
?>

<style>
    
</style>

<div class="col-xs-12 paddingleftright header1">
	<div class="col-lg-4 col-md-4 col-sm-5 hidden-xs header_support">
		<!-- BEGIN TAG CODE - DO NOT EDIT! --><div><div id="proactivechatcontainerxi6sv6pplr"></div><table border="0" cellspacing="2" cellpadding="2"><tr><td align="center" id="swifttagcontainerxi6sv6pplr"><div style="display: inline;" id="swifttagdatacontainerxi6sv6pplr"></div></td> </tr><tr><td align="center"><div style="MARGIN-TOP: 2px; WIDTH: 100%; TEXT-ALIGN: center;"><span style="FONT-SIZE: 9px; FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif;"><span style="COLOR: #000000">  </span></span></div></td></tr></table></div> <script type="text/javascript">var swiftscriptelemxi6sv6pplr=document.createElement("script");swiftscriptelemxi6sv6pplr.type="text/javascript";var swiftrandom = Math.floor(Math.random()*1001); var swiftuniqueid = "xi6sv6pplr"; var swifttagurlxi6sv6pplr="https://demandvi.com/visitor/index.php?/Hangvip/LiveChat/HTML/HTMLButton/cHJvbXB0dHlwZT1jaGF0JnVuaXF1ZWlkPXhpNnN2NnBwbHImdmVyc2lvbj00LjYyLjAuNDM5NCZwcm9kdWN0PUZ1c2lvbiZmaWx0ZXJkZXBhcnRtZW50aWQ9NDAmcm91dGVjaGF0c2tpbGxpZD0yJmN1c3RvbW9ubGluZT1odHRwcyUzQSUyRiUyRmhhbmd2aXAudm4lMkZ1cGxvYWRzJTJGb25saW5lMS5wbmcmY3VzdG9tb2ZmbGluZT1odHRwcyUzQSUyRiUyRmhhbmd2aXAudm4lMkZ1cGxvYWRzJTJGb2ZmbGluZS5wbmcmY3VzdG9tYXdheT1odHRwcyUzQSUyRiUyRmhhbmd2aXAudm4lMkZ1cGxvYWRzJTJGYXdheS5wbmcmY3VzdG9tYmFja3Nob3J0bHk9aHR0cHMlM0ElMkYlMkZoYW5ndmlwLnZuJTJGdXBsb2FkcyUyRmF3YXkucG5nCmY5YzFmN2ExZTVkZWFlMmExMTgxMjk2MTM1NzM4MmJkM2IwNTk1ZTQ=";setTimeout("swiftscriptelemxi6sv6pplr.src=swifttagurlxi6sv6pplr;document.getElementById('swifttagcontainerxi6sv6pplr').appendChild(swiftscriptelemxi6sv6pplr);",1);</script><!-- END TAG CODE - DO NOT EDIT! -->
	</div>
	<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 header">
		<a href="<?= SITE_PATH ?>/gioi-thieu.html"><?=Yii::t('app', 'Giới thiệu')?></a>
		<?php if (!isset(Yii::$app->users->id)) { ?>
		<a href="<?= SITE_PATH ?>/dang-ky.html"><?=Yii::t('app', 'Đăng ký')?></a>
		<a data-toggle="modal" data-target="#myModalLogin" href="">Login</a>
		<?php echo nodge\eauth\Widget::widget(array('action' => '/site/login')); ?>
		<a href="https://accounts.google.com/o/oauth2/auth?response_type=code&amp;redirect_uri=https%3A%2F%2Fhangvip.vn&amp;client_id=613484688769-4tu0sa1cdog7cgeu8l9ksnbdtj3avvmb.apps.googleusercontent.com&amp;scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&amp;access_type=offline&amp;approval_prompt=force">Login google</a>
		<?php }else{?>
		<a href="<?= SITE_PATH ?>/don-hang.html"><?=Yii::t('app', 'Đơn hàng')?></a>
		<a href="<?= SITE_PATH ?>/cap-nhat-tai-khoan.html"><?=Yii::t('app', 'Cập nhật tài khoản')?></a>
		<a href="<?= SITE_PATH ?>/logout.html">Logout</a>
		<?php }?>
		<a href="<?= SITE_PATH ?>/thanh-toan.html">Shopping cart</a>
<!--                <a class="hovao" >Chuyển ngôn ngữ
                    <ul style="background: #3b504a;
                        position: absolute;
                        z-index: 10;
                        right: 17px;">
                        <li>HTML</li>
                        <li>CSS</li>
                    </ul>
                </a>-->
             
                
	</div><div class="clearfix"></div>
</div>



<!--<div class="navigation-menu clearfix header_mobi">
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse-right">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="hidden-lg hidden-md hidden-sm navbar-brand">       
                    <div style="color:#fff;">Menu</div>
                </div>
            </div>
            <div class="navbar-collapse navbar-collapse-right collapse">
                <ul class="nav navbar-nav">
                    <li class="level0">
                        <a href="<?=SITE_PATH?>/gioi-thieu.html" title="Giới thiệu">
                            <span>Giới thiệu</span>
                        </a>
                    </li>
					<?php if (!isset(Yii::$app->users->id)) { ?>
                    <li class="level0">
                        <a href="<?php echo SITE_PATH ?>/dang-ky.html" title="Đăng ký">
                            <span>Đăng ký</span>
                        </a>
                    </li>
                    <li class="level0">
                        <a data-toggle="modal" data-target="#myModalLogin" href="" title="Login">
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="level0">
                        <?php echo nodge\eauth\Widget::widget(array('action' => '/site/login')); ?>
                    </li>
					<li class="level0">
                        <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&amp;redirect_uri=https%3A%2F%2Fhangvip.vn&amp;client_id=613484688769-4tu0sa1cdog7cgeu8l9ksnbdtj3avvmb.apps.googleusercontent.com&amp;scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&amp;access_type=offline&amp;approval_prompt=force">Login google</a>
                    </li>
					<?php }else{?>
					<li class="level0">
                        <a href="<?= SITE_PATH ?>/don-hang.html">Đơn hàng</a>
                    </li>
					<li class="level0">
                        <a href="<?= SITE_PATH ?>/cap-nhat-tai-khoan.html">Cập nhật tài khoản</a>
                    </li>
					<li class="level0">
                        <a href="<?= SITE_PATH ?>/logout.html">Logout</a>
                    </li>	
					<?php }?>
					<li class="level0">
                        <a href="<?= SITE_PATH ?>/thanh-toan.html">Shopping cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>-->

<div class="modal fade" id="myModalLogin" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">            
                        <div class="modal-header" style="margin-bottom: 10px; background: #efefef; border-radius: 5px 5px 0px 0px;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="text-transform: uppercase; text-align: center; font-weight: bold;"><?=Yii::t('app','Đăng nhập')?></h4>
                        </div>  
                        <div class="modal-body" style="padding-top:0px;">

                            <div class="tab-content tab-content1">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 defaultview">                                    
                                    <p style="text-align: center; color: red;"><?=Yii::t('app','Điền thông tin bên dưới để đăng nhập')?></p>
                                    <?php
                                    $form = ActiveForm::begin([
                                                'id' => 'loginuser-form',
                                                'action' => SITE_PATH . '/dang-nhap.html',
                                                'options' => ['class' => 'form-horizontal'],
                                                'fieldConfig' => [
                                                    'template' => "{label}\n<div style='margin:5px 0px;' class=\"col-lg-9 col-md-8 col-sm-8 col-xs-12\">{input}</div>\n<div style='padding-left: 15px !important;' class=\"col-lg-offset-3 col-md-offset-4 col-sm-offset-4 col-lg-9 col-md-8 col-sm-8 col-xs-12 paddingleftright\">{error}</div>",
                                                    'labelOptions' => ['class' => 'col-lg-3 col-md-4 col-sm-4 col-xs-12 paddingleftright control-label loginlablelogin'],
                                                ],
                                    ]);
                                    ?>
                                    <div>
                                        <?= $form->field($model, 'username') ?>
                                    </div>
                                    <div>
                                        <?= $form->field($model, 'password')->passwordInput() ?>
                                    </div>
                                    <div>
                                        <?=
                                        $form->field($model, 'rememberMe', [
                                            'template' => "{label}<div class=\"col-lg-offset-3 col-md-offset-4 col-sm-offset-4 col-lg-9 col-md-8 col-sm-8 col-xs-12\">{input}</div>\n<div class=\"col-xs-12\">{error}</div>",
                                        ])->checkbox()
                                        ?>
                                    </div>	
                                    <div class="form-group">
                                        <div class="col-lg-offset-3 col-md-offset-4 col-sm-offset-4 col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                            <?= Html::submitButton(Yii::t('app', 'Đăng nhập'), ['class' => 'btn btn-primary']) ?>
                                            <br/><br/>
                                    <?= Html::a(Yii::t("user", Yii::t('app', 'Đăng ký')), ["/dang-ky.html"]) ?> /
                                    <?= Html::a(Yii::t("user", Yii::t('app', 'Quên mật khẩu')) . "?", ["/user/forgot"]) ?>
                                        </div>
                                    </div>
                                    <style>
                                        label.loginlablelogin{padding-top: 13px !important;}
                                        .field-loginform-username,.field-loginform-password,.field-loginform-rememberme{margin-bottom: 0px;}
                                    </style>
                                    <?php ActiveForm::end(); ?>    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" style="background: #337ab7;color: #fff;" class="btn btn-secondary" data-dismiss="modal"><?=Yii::t('app', 'Close')?></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end Modal DM -->