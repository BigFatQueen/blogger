<?php
namespace App\Helper;
class Crypt {
  public static function crypt()
  {
     $newEncrypter = new \Illuminate\Encryption\Encrypter( \Config::get('constant.key'), \Config::get( 'app.cipher' ) );
    return $newEncrypter;
  }
}