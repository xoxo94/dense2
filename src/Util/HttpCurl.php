<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25 0025
 * Time: 下午 20:12
 */

namespace App\Util;


class HttpCurl
{
    public $connectTtl = 0;
    public $readTtl = 0;
    public $readTimeout=120000;
    public $connectTimeout=120000;
    /**
     *
     * @return HttpCurl
     */
    private static $instance;

    /**
     *
     * @return HttpCurl
     */
    public static function &instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new HttpCurl();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }


    public function curl($url,$post=false,$postdata=[]){
        $url=$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTtl) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTtl) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        //https 请求
        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);
        }
        try {
            $reponse = curl_exec($ch);
        }catch (\Exception $e){
            return $e->getMessage();
        }
        if (curl_errno($ch))
        {
            return curl_error($ch);
        }
        else
        {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode)
            {
                return $reponse;
            }
        }
        curl_close($ch);
        return $reponse;
    }
}