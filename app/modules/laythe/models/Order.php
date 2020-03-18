<?php
namespace app\modules\laythe\models;
use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\Url;
class Order extends \yii\easyii\components\ActiveRecord  {
    const STATUS_BLANK = 'in_checkout';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';
    const STATUS_DECLINED = 'declined';
    const STATUS_SENT = 'sent';
    const STATUS_RETURNED = 'returned';
    const STATUS_ERROR = 'error';
    const STATUS_COMPLETED = 'completed';
    const SESSION_KEY = 'easyii_shopcart_at';    

    public static function tableName() {
        return 'easyii_shopcart_orders';
    }
    /*
    public function rules() {
        return [
            [['name', 'address'], 'required'],
        ];
    }
    */
    public function rules() {
        return [
            [['name', 'address','email','city','country','type_submit'], 'required'],            
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'max' => 32],
            [['country',  'address', 'status','zipcode'], 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            [['time', 'new','type_submit'], 'integer'],
            [['data'], 'string'],
            [['city'], 'string', 'max' => 100],            
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('easyii', 'Name'),
            'email' => Yii::t('easyii', 'E-mail'),
            'address' => Yii::t('easyii', 'Address'),
            'phone' => Yii::t('easyii', 'Phone'),            
            'city' => Yii::t('easyii', 'City'),
            'coutry' => Yii::t('easyii', 'Country'),
             'zipcode' => Yii::t('easyii', 'Zip Code'),
            'payment_method' => Yii::t('easyii', 'Payment Method'),
            'data' => Yii::t('easyii', 'Data'),
            'store_currency' => Yii::t('easyii', 'Store Currency'),
            'sale_currency' => Yii::t('easyii', 'Sale Currency'),
            'comment' => Yii::t('easyii', 'Comment'),
            'remark' => Yii::t('easyii', 'Admin remark'),
        ];
    }

    public function behaviors() {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function() {
                    return self::find()->where(['new' => 1])->count();
                }
            ]
        ];
    }

    public static function statusName($status) {
        $states = self::states();
        return !empty($states[$status]) ? $states[$status] : $status;
    }

    public static function states() {
        return [
            self::STATUS_BLANK => Yii::t('easyii/shopcart', 'Blank'),
            self::STATUS_PENDING => Yii::t('easyii/shopcart', 'Pending'),
            self::STATUS_PROCESSED => Yii::t('easyii/shopcart', 'Processed'),
            self::STATUS_DECLINED => Yii::t('easyii/shopcart', 'Declined'),
            self::STATUS_SENT => Yii::t('easyii/shopcart', 'Sent'),
            self::STATUS_RETURNED => Yii::t('easyii/shopcart', 'Returned'),
            self::STATUS_ERROR => Yii::t('easyii/shopcart', 'Error'),
            self::STATUS_COMPLETED => Yii::t('easyii/shopcart', 'Completed'),
        ];
    }

    public function getStatusName() {
        $states = self::states();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    public function getGoods() {
        
        return $this->hasMany(Good::className(), ['order_id' => 'order_id']);
    }

    public function getCost() {
        $total = 0;
        foreach ($this->goods as $good) {
            $total += $good->count * round($good->price * (1 - $good->discount / 100));
        }
        return $total;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
           
            if ($insert) {
//                $this->order_code =  date('YmdHis') . rand(1000, 9999);                
                $this->ip = Yii::$app->request->userIP;
                $this->access_token = Yii::$app->security->generateRandomString(32);
                $this->time = time();
                
            } else {               
                if ($this->oldAttributes['status'] == self::STATUS_BLANK && $this->status == self::STATUS_PENDING) {
                    $this->new = 1;
//                    $this->mailAdmin();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete() {
        parent::afterDelete();

        foreach ($this->getGoods()->all() as $good) {
            $good->delete();
        }
    }

    public function mailAdmin() {
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
        if (!$settings['mailAdminOnNewOrder']) {
            return false;
        }
        return Mail::send(
             Setting::get('admin_email'), $settings['subjectOnNewOrder'], $settings['templateOnNewOrder'], [
                    'order' => $this,
                    'link' => Url::to(['/admin/shopcart/a/view', 'id' => $this->primaryKey], true)
                ]
        );
    }

    public function notifyUser() {
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
        return Mail::send(
                $this->email, $settings['subjectNotifyUser'], $settings['templateNotifyUser'], [
                    'order' => $this,
                    'link' => Url::to([$settings['frontendShopcartRoute'], 'id' => $this->primaryKey, 'token' => $this->access_token], true)
                ]
        );
    }
    function checkcomplete($order_id, $created) {
        if ($order_id != null && $created != null) {
         
            $count = Order::find()->where(['order_id'=>$order_id, 'created'=>$created,'status'=>'in_checkout'])->count();
            if ($count == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function checkcomplete_paypal($order_id, $created) {
        if ($order_id != null && $created != null) {
          
            $count = Order::find()->where(['order_id'=>$order_id, 'created'=>$created,'status'=>'pending'])->count();
            if ($count == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function check_paypal_auto_complete($order_id, $created) {
        if ($order_id != null && $created != null) {
           
            $count = Order::find()->where(['order_id'=>$order_id, 'created'=>$created,'status'=>'paypal_pending'])->count();
            if ($count == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
