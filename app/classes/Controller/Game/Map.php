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
class Controller_Game_Map extends Controller {
  //put your code here
  
  
  /**
   * 
   * @return type 
   */
  public function index() 
  {
    $map = new Game_Map(1);

    //Logger::dump($map->getCells(array(array(5, 5),array(4, 4))));

//    Logger::dump($map->test());
    $list = [];
    $startX = 1;
    $startY = 1;
    $rangeX = 38;
    $rangeY = 38;
    for ($i = $startY; $i < $rangeY; $i++) {
      for ($i2 = $startX; $i2 < $rangeX; $i2++) {
        $list[] = [$i2, $i];
      }
    }
//    $grid = $map->getCells($list);
    $grid = $map->getChunks($list);


    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'map' => $grid,
      'startX' => $startX,
      'startY' => $startY,
      'rangeX' => $rangeX,
      'rangeY' => $rangeY,
      'tpl' => 'Game/bigMap.tpl'
    );
  }


  public function map()
  {
    $unit = $this->getUserUnit();

    $data = $unit->getDataForView();


    $grid = [];
    //Js::add(Js::C_ONLOAD, 'IL.MapData.set(\''..'\')');
    Js::add(Js::C_ONLOAD, 'CanvasActions.init('.json_encode($data).')');
    return array(
      'layout' => array('html.tpl', 'Game/head.tpl', 'layout.tpl'),
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Game/map.tpl'
    );
  }




  public function action()
  {
    $post = Service::getRequest()->getPost();
    $unit = $this->getUserUnit();
    if (!$unit) {
      return array();
    }
    if (!$post['action']) {
      return array('error' => 'no action');
    }
    if (empty($post['unit']) || empty($post['unit']['x']) || empty($post['unit']['y'])) {
      return array('error' => 'missing unit data');
    }
    if (!$this->checkSincronization($unit, $post['unit'])) {
      return array('error' => 'sinc error');
    }
    if (!$unit->isActionPossible($post['action'])) {
      return array('error' => 'action is not possible');
    }

  }






  /**
   * @return Game_Unit_Base
   */
  private function getUserUnit()
  {
    $id_user = FrontController_Auth::get('id');
    $unitData = Model_Unit::getActiveUnit($id_user);
    if (!$unitData) {
      // TODO: refactor this shit
      $id_unit = Model_Unit::createUnit();
      Model_Unit::setActivity($id_unit);
      Helper::redirect('Game_Test', 'map');
    }

    // get unit class depends on unit type
    $unitClass = Service::getConfig()->get('all', 'units')['units'][$unitData->id_type]['class'];
    $map = new Game_Map(1);

    /**
     * @val Game_Unit_Base $unit
     */
    $unit = new $unitClass($unitData, $map);
    return $unit;
  }


  private function checkSincronization($unit, $postUnit)
  {
    if ($unit->x != $postUnit['x'] || $unit->y != $postUnit['y']) {
      return false;
    }
    return true;
  }

}
