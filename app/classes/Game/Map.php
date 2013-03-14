<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2013
 */

/**
 * This class represents an instance of global Map
 */
class Game_Map {

  const CHUNK_SIZE = 4;
  const MAX_CHUNKS_X = 20;
  const MAX_CHUNKS_Y = 10;

  const DIRECTION_TOP_RIGHT     = 1;
  const DIRECTION_BOTTOM_RIGHT  = 2;
  const DIRECTION_BOTTOM_LEFT   = 3;
  const DIRECTION_TOP_LEFT      = 4;

  const DEFAULT_PLANET = 1;

  protected $chunks;
  protected $cells;

  /**
   * @var Game_MapGeneratorInterface
   */
  protected $generator;
  protected $config;

  protected $planet;

  public function __construct($planet)
  {
    $this->planet = $planet;
    Model_Map::setPlanet($planet);
  }


  public function getCell($x, $y)
  {
    return $this->getCells([[$x, $y]])[$x][$y];
  }

  public function getCells($list = array())
  {
    $forLoad = [];
    $return = [];
    foreach ($list as $key => $pair) {
      $chunkX = ceil($pair[0] / self::CHUNK_SIZE);
      $chunkY = ceil($pair[1] / self::CHUNK_SIZE);

      if (!isset($this->chunks[$chunkX]) || !isset($this->chunks[$chunkX][$chunkY])) {
        $forLoad[] = [$chunkX, $chunkY];
      } else {
        $return[$pair[0]][$pair[1]] = $this->cells[$pair[0]][$pair[1]];
        unset($list[$key]);
      }
    }
    if ($forLoad) {
      $this->loadChunks($forLoad);
    }
    foreach ($list as $pair) {
      $return[$pair                                                                                                                                 [0]][$pair[1]] = $this->cells[$pair[0]][$pair[1]];
    }
    return $return;
  }

  /**
   * @param $x
   * @param $y
   * @return array
   */
  public function getChunksNeiboursIfExists($x, $y)
  {
    $return = [];
    $list = [
      [$x+1, $y+1],
      [$x+1, $y  ],
      [$x+1, $y-1],
      [$x,   $y-1],
      [$x-1, $y-1],
      [$x-1, $y  ],
      [$x-1, $y+1],
      [$x,   $y+1],
    ];
    $forLoad = array();
    foreach ($list as $pair) {
      if (isset($this->chunks[$pair[0]]) && isset($this->chunks[$pair[0]][$pair[1]])) {
        $return[$pair[0]][$pair[1]] = $this->chunks[$pair[0]][$pair[1]];
      } else {
        $forLoad[] = $pair;
      }
    }
    if ($forLoad) {
      $this->loadChunks($forLoad);
      foreach ($forLoad as $pair) {
        if (isset($this->chunks[$pair[0]]) && isset($this->chunks[$pair[0]][$pair[1]])) {
          $return[$pair[0]][$pair[1]] = $this->chunks[$pair[0]][$pair[1]];
        } else {
          $return[$pair[0]][$pair[1]] = null;
        }
      }
    }
    return $return;
  }

  public function loadChunks($list)
  {
    $ids = [];
    foreach ($list as $pair) {
      $ids[] = $this->getIdByCoords($pair[0], $pair[1]);
    }
    $result = Model_Map::getChunksById($ids);
    if ($result) {
      foreach ($result as $row) {
        $this->chunks[$row['x']][$row['y']] = $row;
        $this->explodeChunk($row, true);
      }
    }
  }

  public function test() {
    return $this->getGenerator()->getChunk(2, 2);

  }


  /**
   * @return Game_MapGeneratorInterface
   */
  protected function getGenerator()
  {
    if (empty($this->generator)) {
      $config = $this->getConfig();
      if (!isset($config['generator'][$this->planet])) {
        $generator = $config['generator'][self::DEFAULT_PLANET];
      } else {
        $generator = $config['generator'][$this->planet];
      }
      $this->generator = new $generator($this);
    }
    return $this->generator;
  }

  public function getConfig()
  {
    if (empty($this->config)) {
      $this->config = Service::getConfig()->get('all', 'map');
    }
    return $this->config;
  }

  /**
   * @param $chunk
   * @return array
   */
  public function explodeChunk($chunk, $save = true)
  {
    $return = [];
    $cells = str_split($chunk['cells'], 2);
    $x = ($chunk['x'] - 1) * self::CHUNK_SIZE;
    $y = ($chunk['y'] - 1) * self::CHUNK_SIZE;
    for ($i = 1; $i <= self::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= self::CHUNK_SIZE; $i2++) {
        $return[$x + $i2][$y + $i] = array_shift($cells);
        if ($save) {
          $this->cells[$x + $i2][$y + $i] = $return[$x + $i2][$y + $i];
        }
      }
    }
    return $return;
  }

  /**
   * @param $x
   * @param $y
   * @param null $target
   * @return string
   */
  public function implodeChunk($x, $y, $target = null)
  {
    if ($target === null) {
      $target = $this->cells;
    }
    $string = '';
    $addX = ($x - 1) * self::CHUNK_SIZE;
    $addY = ($y - 1) * self::CHUNK_SIZE;
    for ($i = 1; $i <= self::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= self::CHUNK_SIZE; $i2++) {
        $string .= $target[$addX + $i2][$addY + $i];
      }
    }
    return $string;
  }

//  protected function loadChunks($list)
//  {
//    foreach ($list as $pair) {
//      $this->chunks[$pair[0]][$pair[1]] = array('chuunk');
//      for ($i = 0; $i < self::CHUNK_SIZE; $i++) {
//        for ($i2 = 0; $i2 < self::CHUNK_SIZE; $i2++) {
//          $this->cells
//            [( $pair[0] - 1 ) * self::CHUNK_SIZE + $i]
//            [( $pair[1] - 1 ) * self::CHUNK_SIZE + $i2]
//              = array();
//        }
//      }
//    }
//  }




  public function getIdByCoords($x, $y)
  {
    if ($x <= 0) {
      $x = self::MAX_CHUNKS_X + $x;
    }
    if ($y <= 0) {
      $y = self::MAX_CHUNKS_Y + $y;
    }
    if ($x > $y) {
      return $this->centerIdFormula($x) + $x - $y;
    }
    return $this->centerIdFormula($y) + $x - $y;
  }
  private function centerIdFormula($n)
  {
    return pow($n, 2) - abs($n) + 1;
  }

}