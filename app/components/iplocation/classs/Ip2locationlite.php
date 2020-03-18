<?php

namespace app\components\iplocation\classs;

class Ip2locationlite {

    protected $errors = array();

    public function __construct() {
        
    }

    public function __destruct() {
        
    }

    public function getError() {
        return implode("\n", $this->errors);
    }

    public function getCountry($host) {
        return $this->getResult($host, 'country');
    }

    public function getCountryCode($host) {
       
        return $this->getResult($host, 'countryCode');
    }

    public function getCity($host) {
        return $this->getResult($host, 'city');
    }

    public function getZip($host) {
        return $this->getResult($host, 'zip');
    }

    public function getResult($host, $name) {
        $ip = @gethostbyname($host);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {

            //$url = 'http://ip-api.com/json/' . $ip;
			$url = 'http://freegeoip.net/json/' . $ip;			
            $rCURL = curl_init();
            curl_setopt($rCURL, CURLOPT_URL, $url);
            curl_setopt($rCURL, CURLOPT_HEADER, 0);
            curl_setopt($rCURL, CURLOPT_RETURNTRANSFER, 1);
            $aData = curl_exec($rCURL);
            curl_close($rCURL);
            $resurl = json_decode($aData);
            if ($name) {
                return $resurl->$name;
            }
				
            return $resurl;
        }

        $this->errors[] = '"' . $host . '" is not a valid IP address or hostname.';
        return;
    }

}

?>