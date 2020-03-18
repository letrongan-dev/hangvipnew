<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * source: http://www.ipinfodb.com/ip_location_api_json.php
 */

namespace app\components\iplocation;

use app\components\iplocation\classs\Ip2locationlite;
use Yii;
use app\models\Ipaddress;
use yii\easyii\helpers\Globals;

class Iplocation {

    public $ipAddress = $_SERVER['REMOTE_ADDR'];//Globals::ip();

    public function init() {
        $this->ipAddress = Yii::$app->getRequest()->getUserIP();
    }

    public function getCountryCode($host) {
        $ipLite = new Ip2locationlite();
        $locations = $ipLite->getCountryCode($this->ipAddress);
        return $locations;
    }

    public function getInfo() {
                $ip=Yii::$app->getRequest()->getUserIP();
		$ipCheck = Ipaddress::findOne($ip);		
        if (isset($ipCheck)) {			
            $result = json_decode($ipCheck->data);
            return $result;
        } else {			
            $ipLite = new Ip2locationlite();
            $locations = $ipLite->getResult($ip, $name = 0);

            $errors = $ipLite->getError();
			/*
            if (isset($errors)) {
                $ip = new Ipaddress();
                $ip->ipAddress = $this->ipAddress;
                $ip->data = json_encode($locations);
                $ip->save();
                return $locations;
            } else {
                return $errors;
            }*/
        }
    }

}
