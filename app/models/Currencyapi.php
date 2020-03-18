<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currencyapi".
 *
 * @property string $currency_from
 * @property string $currency_to
 * @property double $rate
 * @property integer $timestamp
 */
class Currencyapi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public static function tableName()
    {
        return 'currencyapi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_from', 'currency_to'], 'required'],
            [['rate'], 'number'],
            [['timestamp'], 'integer'],
            [['currency_from', 'currency_to'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_from' => 'Currency From',
            'currency_to' => 'Currency To',
            'rate' => 'Rate',
            'timestamp' => 'Timestamp',
        ];
    }
}
