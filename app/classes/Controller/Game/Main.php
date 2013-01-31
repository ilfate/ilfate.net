<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Game
 *
 * @author ilfate
 */
class Controller_Game_Main extends Controller {
  //put your code here
  
  
  /**
   * 
   * @return type 
   */
  public function index() 
  {
      
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/index.tpl'
    );
  }
  
  public function gameBlank()
  {
    Service_Sidebar::addSideBar('Game_Main', 'gameBlankinfo');
    Js::add(Js::C_ONLOAD, 'CanvasActions.init()');
    return array(
      'layout' => array('html.tpl', 'Game/head.tpl', 'layout.tpl'),
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/gameBlank.tpl'
    );
  }
  
  public function gameBlankinfo()
  {
    return array(
      'tpl' => 'Game/gameBlankInfo.tpl'
    );
  }
  
  /**
   * 
   * @return type 
   */
  public function gameWindow() 
  {
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/gameWindow.tpl'
    );
  }
}

?>
