<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of FrontController_Auth
 * 
 *
 * @author ilfate
 */
class FrontController_Layout implements CoreInterfaceFrontController
{
  const PRIORITY = 70;

  private static $menu = array(
    'about_me'    => array('class' => 'Cv',         'method' => 'aboutMe',  'text' => 'CV'),
    'game'	      => array('class' => 'Game_Main',  'method' => 'index',    'text' => 'Games'),
    'photos'	    => array('class' => 'Main',       'method' => 'photo',    'text' => 'Photos')
  );
  private static $menu_map = array(
    'Auth'      => 'main',
    'Main'      => array('aboutMe' => 'about_me', 'photo' => 'photos' ),
    'Cv'        => 'about_me',
    'Game_Main' => 'game',
    'RobotRock' => 'game',
  );
  
  private static $default_menu = 'main';
  
  private static $side_bars = array();
  
  public static function preExecute() 
  {
    if (Service::getRequest()->getExecutingMode() == Request::EXECUTE_MODE_HTTP)
    {
      $access_restricted = Service::getRequest()->getGet('access_restricted');
      CoreView_Http::setGlobal('page_title', 'Ilfate');
      CoreView_Http::setGlobal('access_restricted', $access_restricted);

      /**
       * Menu handler 
       */
      $class = Service::getRouting()->getClass();
      if (isset(self::$menu_map[$class])) {
        if (is_array(self::$menu_map[$class])) {
          $method = Service::getRouting()->getMethod();
          if (isset(self::$menu_map[$class][$method])) {
            $active_menu = self::$menu_map[$class][$method];
          }
        } else {
          $active_menu = self::$menu_map[$class];
        }
      }
      if (!isset($active_menu)) {
        $active_menu = self::$default_menu;
      }
      if(isset(self::$menu[$active_menu])) {
        self::$menu[$active_menu]['active'] = true;
      }
      CoreView_Http::setGlobal('ilfate_menu', self::$menu);

      /**
       * Messages handler 
       */
    
      $messages = Message::getMessages();
      if ($messages) {
        foreach ($messages as $message) {
          Js::add(Js::C_ONLOAD, "ilAlert('$message');");
        }
        Message::clear();
      }
    }
  } 
  
  public static function getSideBar()
  {
    $return = '';
    if (self::$side_bars) {
      foreach (self::$side_bars as $route) {
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
