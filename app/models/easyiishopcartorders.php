<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "easyii_shopcart_orders".
 *
 * @property integer $order_id
 * @property string $order_code
 * @property integer $user_id
 * @property string $name
 * @property string $company
 * @property string $address
 * @property string $payment_method
 * @property string $data
 * @property string $store_currency
 * @property string $sale_currency
 * @property string $order_total
 * @property string $order_fee
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property string $access_token
 * @property string $ip
 * @property integer $time
 * @property string $status
 * @property integer $workflow
 */
class easyiishopcartorders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'easyii_shopcart_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_code', 'name', 'address', 'payment_method', 'store_currency', 'sale_currency', 'order_total', 'order_fee', 'phone', 'email', 'comment', 'access_token', 'ip', 'workflow'], 'required'],
            [['user_id', 'time', 'workflow'], 'integer'],
            [['data'], 'string'],
            [['order_total', 'order_fee'], 'number'],
            [['order_code'], 'string', 'max' => 20],
            [['name', 'phone'], 'string', 'max' => 64],
            [['company'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 255],
            [['payment_method'], 'string', 'max' => 100],
            [['store_currency', 'sale_currency'], 'string', 'max' => 3],
            [['email'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 1024],
            [['access_token'], 'string', 'max' => 32],
            [['ip'], 'string', 'max' => 16],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_code' => 'Order Code',
            'user_id' => 'User ID',
            'name' => 'Name',
            'company' => 'Company',
            'address' => 'Address',
            'payment_method' => 'Payment Method',
            'data' => 'Data',
            'store_currency' => 'Store Currency',
            'sale_currency' => 'Sale Currency',
            'order_total' => 'Order Total',
            'order_fee' => 'Order Fee',
            'phone' => 'Phone',
            'email' => 'Email',
            'comment' => 'Comment',
            'access_token' => 'Access Token',
            'ip' => 'Ip',
            'time' => 'Time',
            'status' => 'Status',
            'workflow' => 'Workflow',
        ];
    }
}
