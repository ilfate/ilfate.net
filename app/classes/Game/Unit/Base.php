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
    'move', 'rotateLeft', 'rotateRight'
  );
  protected $checkedActions = [];
  protected $lastError = '';

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
      'd' => (int)$this->unitData['direction'],
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
    // Is action is in available List
    if (!in_array($action, $this->actionsList)) {
      $result = false;
      $this->lastError = 'action is not in List';
    } else {

      if (!$this->{'check'.ucfirst($action)}()) {
        $result = false;
        $this->lastError = 'check'.ucfirst($action) . ' returned false';
      }

      if (!isset($result)) {
        $result = true;
      }
    }
    $this->checkedActions[$action] = $result;
    return $result;
  }

  public function getLastError()
  {
    return $this->lastError;
  }

  public function action($action, $params = [])
  {
    if (!$this->isActionPossible($action)) {
      return null;
    }
    return call_user_func([$this, $action], $params);
  }


  public function checkMove()
  {
    return true;
  }


  public function move()
  {
    list($this->unitData['x'], $this->unitData['y']) = $this->map->getNextCoords($this->unitData['x'], $this->unitData['y'], $this->unitData['direction']);
    Model_Unit::update($this->unitData['id'], ['x' => $this->unitData['x'], 'y' => $this->unitData['y']]);
  }

  public function checkrotateLeft()
  {
    return true;
  }
  public function rotateLeft()
  {
    if ($this->unitData['direction'] > 0) {
      $this->unitData['direction']--;
    } else {
      $this->unitData['direction'] = 3;
    }
    Model_Unit::update($this->unitData['id'], ['direction' => $this->unitData['direction']]);
  }
  public function checkrotateRight()
  {
    return true;
  }
  public function rotateRight()
  {
    if ($this->unitData['direction'] < 3) {
      $this->unitData['direction']++;
    } else {
      $this->unitData['direction'] = 0;
    }
    Model_Unit::update($this->unitData['id'], ['direction' => $this->unitData['direction']]);
  }



}