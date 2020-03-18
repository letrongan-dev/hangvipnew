<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sharefb".
 *
 * @property integer $id
 * @property string $email
 * @property integer $time
 * @property integer $status
 */
class Sharefb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sharefb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'time'], 'required'],
            [['time', 'status'], 'integer'],
            [['email'], 'string', 'max' => 225]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }
}
