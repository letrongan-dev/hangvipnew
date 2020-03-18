<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ipaddress".
 *
 * @property string $ipAddress
 * @property string $data
 */
class Ipaddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ipaddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ipAddress', 'data'], 'required'],
            [['data'], 'string'],
            [['ipAddress'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ipAddress' => 'Ip Address',
            'data' => 'Data',
        ];
    }
}
