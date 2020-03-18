<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property integer $country_id
 * @property string $country_iso_code_2
 * @property string $country_name
 * @property string $country_full_name
 * @property string $country_iso_code_3
 * @property string $country_currency
 * @property string $currency_name
 * @property string $currrency_symbol
 * @property string $continent_code
 * @property string $flag
 * @property integer $version
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'country_iso_code_2', 'country_name', 'country_full_name', 'country_iso_code_3'], 'required'],
            [['country_id', 'version'], 'integer'],
            [['country_iso_code_2', 'continent_code'], 'string', 'max' => 2],
            [['country_name', 'country_full_name'], 'string', 'max' => 255],
            [['country_iso_code_3', 'country_currency', 'currrency_symbol'], 'string', 'max' => 3],
            [['currency_name'], 'string', 'max' => 32],
            [['flag'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_iso_code_2' => 'Country Iso Code 2',
            'country_name' => 'Country Name',
            'country_full_name' => 'Country Full Name',
            'country_iso_code_3' => 'Country Iso Code 3',
            'country_currency' => 'Country Currency',
            'currency_name' => 'Currency Name',
            'currrency_symbol' => 'Currrency Symbol',
            'continent_code' => 'Continent Code',
            'flag' => 'Flag',
            'version' => 'Version',
        ];
    }
}
