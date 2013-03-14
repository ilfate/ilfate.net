<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2013
 */


class Math {

  public static function getChance($chance)
  {
    if($chance >= 100) return true;
    if($chance <= 0) return false;
    if( $chance >= mt_rand(0, 10000)/100 )
    {
      return true;
    }
  }


  public static function chanses($chanses = array())
  {
    $rand = mt_rand(0, 10000)/100;
    $current = 0;
    foreach ($chanses as $key => $chance) {
      $current += $chance;
      if($current >= $rand) {
        return $key;
      }
    }
    return false;

  }
}