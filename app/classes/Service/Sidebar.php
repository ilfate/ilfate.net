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
  const DEFAULT_SIDEBAR_CLASS  = 'Main';
  const DEFAULT_SIDEBAR_METHOD = 'randomBanner';
  
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
		if(!$route['is_ajax'])
		{
	      $return .= Helper::exe($route['class'], $route['method']);
		} else {
		  $return .= Helper::exeAjax($route['class'], $route['method']);
		}
      }
    } else {
      $return .= Helper::exeAjax(self::DEFAULT_SIDEBAR_CLASS, self::DEFAULT_SIDEBAR_METHOD);
	}
    return $return;
  }
  
  public static function addSideBar($class, $method, $is_ajax = false)
  {
    self::$side_bars[] = array('class' => $class, 'method' => $method, 'is_ajax' => $is_ajax);
  }
  
  public static function postExecute() 
  {
    
  }

  
  
}


?>
