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
class Service_IEdetector extends CoreService
{
  const PRIORITY = 12;
  
  
  
  
  public static function preExecute() 
  {
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false && Routing::getMethod() != 'ie') 
	{
      Helper::redirect('Error', 'ie');
	}
  } 
  
  
  
  public static function postExecute() 
  {
    
  }

  
  
}


?>
