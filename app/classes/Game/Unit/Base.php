<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2013
 */


abstract class Game_Unit_Base implements InterfaceGame_Unit {

  /**
   * @var array model_Unit
   */
  protected $unitData;
  /**
   * @var Game_Map
   */
  protected $map;

  protected $config;

  protected $actionsList = array(
    'move', 'rotate',
  );
  protected $checkedActions = [];

  public function __construct(Model_Unit $unitData, Game_Map $map)
  {
    $this->unitData = $unitData->toArray();
    $this->map = $map;
    $this->config = Service::getConfig()->get('all', 'units');
  }

  public function getDataForView()
  {
    $data = [];
    $data['unit'] = [
      'x' => (int)$this->unitData['x'],
      'y' => (int)$this->unitData['y'],
    ];
    $data['map'] = $this->map->getMap($this->unitData['x'], $this->unitData['y']);
    $data['info'] = $this->map->getInfo();
    return $data;
  }

  public function __get($param)
  {
    if(isset($this->unitData[$param])) {
      return $this->unitData[$param];
    }
    return null;
  }

  public function isActionPossible($action)
  {
    if (isset($this->checkedActions[$action])) {
      return $this->checkedActions[$action];
    }
    if (!in_array($action, $this->actionsList)) {
      $result = false;
    } else {

      $this->{'check'.ucfirst($action)}();

      if (!isset($result)) {
        $result = true;
      }
    }
    $this->checkedActions[$action] = $result;
    return $result;
  }

  public function checkMove()
  {

  }

  public function move()
  {

  }



}