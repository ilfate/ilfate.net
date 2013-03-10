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
class Controller_Game_Test extends Controller {
  //put your code here
  
  
  /**
   * 
   * @return type 
   */
  public function index() 
  {
    $map = new Game_Map();

    $result = $map->getIdByCoords(1, 1);

    Logger::dump($result);


    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/index.tpl'
    );
  }

}
