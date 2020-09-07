<?php
namespace App\Util;

class Aes
{
    private $iv = '8532147569321405';
    private $encryptKey = 'e45cf7da21f10574d0c2877766d4ee46';
    /**
     *
     * @return Aes
     */
    private static $instance;
    /**
     *
     * @return Aes
     */
    public static function &instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new Aes();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function encrypt($encryptStr) {
        return base64_encode(openssl_encrypt($encryptStr, 'aes-256-cbc', $this->encryptKey, true, $this->iv));
    }

    public function decrypt($decryptStr) {
        return openssl_decrypt(base64_decode($decryptStr), 'aes-256-cbc', $this->encryptKey, true, $this->iv);
    }
}

