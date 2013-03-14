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
    $map = new Game_Map(1);

    //Logger::dump($map->getCells(array(array(5, 5),array(4, 4))));

    //Logger::dump($map->test());

    for ($i = 0; $i < 100; $i++) {
      if ($var = Math::chanses(array('k' => 1, 'v' => 4))) {
        if (!isset($data[$var])) {
          $data[$var] = 0;
        }
        $data[$var]++;
      }
    }
    Logger::dump($data);
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/index.tpl'
    );
  }

}
