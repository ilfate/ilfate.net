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
    $rangeX = 11;
    $rangeY = 7;
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
    $map = new Game_Map(1);
    $unit = $this->getUserUnit($map);

    $units_all = $this->getUnitsInArea($unit->x, $unit->y, $map);
    $units = $units_all['units'];
    $data = $unit->getDataForView($units);

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
    if (!$post['action']) {
      return array('error' => 'no action');
    }
    if (empty($post['unit']) || empty($post['unit']['x']) || empty($post['unit']['y'])) {
      return array('error' => 'missing unit data');
    }
    $map = new Game_Map(1);
    $data = $this->getUnitsInArea($post['unit']['x'], $post['unit']['y'], $map);
    extract($data); // $unit and $units
    /** @var $unit Game_Unit_Base */

    if(empty($unit)) {
      return array('error' => 'unit load error');
    }
    if (!$this->checkSincronization($unit, $post['unit'])) {
      return array('error' => 'sinc error');
    }
    if (!$unit->isActionPossible($post['action'])) {
      return array('error' => $unit->getLastError());
    }

    $return = $unit->action($post['action']);

    $data = $unit->getDataForView($units);

    return array(
      'action' => $post['action'],
      'data' => $data
    );
  }






  /**
   * @return Game_Unit_Base
   */
  private function getUserUnit($map)
  {
    $id_user = FrontController_Auth::get('id');
    $unitData = Model_Unit::getActiveUnit($id_user);
    if (!$unitData) {
      // TODO: refactor this shit
      $id_unit = Model_Unit::createUnit();
      Model_Unit::setActivity($id_unit);
      Helper::redirect('Game_Test', 'map');
    }

    return $this->initUnit($unitData, $map);
  }

  /**
   * @param $unitData
   * @param $map
   * @return Game_Unit_Base
   */
  private function initUnit($unitData, $map)
  {
    // get unit class depends on unit type
    $unitClass = Service::getConfig()->get('all', 'units')['units'][$unitData->id_type]['class'];
    $unit = new $unitClass($unitData, $map);
    return $unit;
  }

  /**
   * @param $x
   * @param $y
   * @param $map Game_Map
   * @return array|bool
   */
  private function getUnitsInArea($x, $y, $map)
  {
    $units = Model_Unit::getAllUnits($map->getScreenChunks($x, $y));
    if (!$units) {
      return false;
    }
    $return = ['unit' => '', 'units' => []];
    foreach ($units as $unit) {
      if ($unit->id_user == FrontController_Auth::get('id') && $unit->x == $x && $unit->y == $y && $unit->active) {
        $return['unit'] = $this->initUnit($unit, $map);
      } else {
        $return['units'][] = $unit;
      }
    }
    return $return;
  }


  private function checkSincronization($unit, $postUnit)
  {
    if ($unit->x != $postUnit['x'] || $unit->y != $postUnit['y']) {
      return false;
    }
    return true;
  }

}
