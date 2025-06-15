<?php

namespace App\Helpers;

class EncryptHelper
{
    public static function encryptContent($content)
    {
        $key = self::getKey(); // 🔒 ambil key valid
        $iv = self::getIV();   // 🔒 ambil iv valid

        return openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
    }

    public static function decryptContent($content)
    {
        $key = self::getKey();
        $iv = self::getIV();

        return openssl_decrypt($content, 'AES-256-CBC', $key, 0, $iv);
    }

    private static function getKey()
    {
        $key = env('ENCRYPTION_KEY', 'defaultkey1234567890123456789012');
        return substr(hash('sha256', $key), 0, 32); // pastikan 32 byte
    }

    private static function getIV()
    {
        $iv = env('ENCRYPTION_IV', 'defaultiv12345678');
        return substr($iv, 0, 16); // pastikan 16 byte
    }
}
