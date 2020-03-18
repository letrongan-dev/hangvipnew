<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gxc_user".
 *
 * @property string $user_id
 * @property string $username
 * @property string $user_url
 * @property string $display_name
 * @property string $password
 * @property string $email
 * @property string $fbuid
 * @property integer $status
 * @property integer $created_time
 * @property integer $updated_time
 * @property integer $recent_login
 * @property string $user_activation_key
 * @property integer $confirmed
 * @property string $gender
 * @property string $location
 * @property string $bio
 * @property string $birthday_month
 * @property string $birthday_day
 * @property string $birthday_year
 * @property string $avatar
 * @property integer $email_site_news
 * @property integer $email_search_alert
 * @property string $email_recover_key
 * @property string $ban_time
 * @property string $ban_reason
 */
class GxcUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gxc_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'display_name', 'password', 'email', 'created_time', 'updated_time', 'recent_login'], 'required'],
            [['fbuid', 'status', 'created_time', 'updated_time', 'recent_login', 'confirmed', 'email_site_news', 'email_search_alert'], 'integer'],
            [['bio'], 'string'],
            [['ban_time'], 'safe'],
            [['username', 'user_url', 'password', 'email'], 'string', 'max' => 128],
            [['display_name', 'user_activation_key', 'avatar', 'email_recover_key', 'ban_reason'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 10],
            [['location'], 'string', 'max' => 100],
            [['birthday_month'], 'string', 'max' => 50],
            [['birthday_day'], 'string', 'max' => 2],
            [['birthday_year'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'user_url' => 'User Url',
            'display_name' => 'Display Name',
            'password' => 'Password',
            'email' => 'Email',
            'fbuid' => 'Fbuid',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'recent_login' => 'Recent Login',
            'user_activation_key' => 'User Activation Key',
            'confirmed' => 'Confirmed',
            'gender' => 'Gender',
            'location' => 'Location',
            'bio' => 'Bio',
            'birthday_month' => 'Birthday Month',
            'birthday_day' => 'Birthday Day',
            'birthday_year' => 'Birthday Year',
            'avatar' => 'Avatar',
            'email_site_news' => 'Email Site News',
            'email_search_alert' => 'Email Search Alert',
            'email_recover_key' => 'Email Recover Key',
            'ban_time' => 'Ban Time',
            'ban_reason' => 'Ban Reason',
        ];
    }
}
