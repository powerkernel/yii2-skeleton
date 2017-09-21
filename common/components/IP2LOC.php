<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\components;


use yii\httpclient\Client;

class IP2LOC
{
    public $provider='ipinfo.io'; // ipapi.co; ipinfo.io


    /**
     * get IP info
     * @param $ip
     * @return bool|mixed
     */
    public function getInfo($ip){
        $default=[
            'ip'=>null,
            'city'=>null,
            'region'=>null,
            'country'=>null,
            'postal'=>null,
            'latitude'=>null,
            'longitude'=>null,
            'timezone'=>null,
            'asn'=>null,
            'org'=>null,
        ];
        $result=null;

        /* select provider */
        if($this->provider=='ipapi.co'){
            $result=$this->ipapiGetInfo($ip);
        }
        if($this->provider=='ipinfo.io'){
            $result=$this->ipinfoGetInfo($ip);
        }

        /* result */
        if($result!=false){
            return array_merge($default, $result);
        }
        return false;
    }

    /**
     * ipinfo Get Info
     * @param $ip
     * @return bool|mixed
     */
    protected function ipinfoGetInfo($ip){
        $token='';
        $url="https://ipinfo.io/{$ip}";
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->setData(['token'=>$token])
            ->send();
        if ($response->isOk) {
            $info=$response->data;
            if(isset($info['ip'])){
                return $info;
            }
            return false;
        }
        return false;
    }

    /**
     * get IP information
     * @param $ip
     * @return bool|mixed
     */
    protected function ipapiGetInfo($ip){
        $url="https://ipapi.co/{$ip}/json/";
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->send();
        if ($response->isOk) {
            $info=$response->data;
            if(isset($info['ip'])){
                return $info;
            }
            return false;
        }
        return false;
    }
}