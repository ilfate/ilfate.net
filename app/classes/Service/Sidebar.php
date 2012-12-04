<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Service_Auth
 * 
 *
 * @author ilfate
 */
class Service_Sidebar extends CoreService
{
  const PRIORITY = 66;
  
  
  private static $side_bars = array();
  
  public static function preExecute() 
  {
    if(Request::getExecutingMode() == Request::EXECUTE_MODE_HTTP)
    {
      
    }
  } 
  
  public static function getSideBar()
  {
    $return = '';
    if(self::$side_bars)
    {
      foreach (self::$side_bars as $route)
      {
        $return .= Helper::exe($route[0], $route[1]);
      }
    }
    return $return;
  }
  
  public static function addSideBar($class, $method)
  {
    self::$side_bars[] = array($class, $method);
  }
  
  public static function postExecute() 
  {
    
  }

  
  
}


?>
