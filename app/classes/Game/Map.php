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

  const DIRECTION_TOP_RIGHT     = 1;
  const DIRECTION_BOTTOM_RIGHT  = 2;
  const DIRECTION_BOTTOM_LEFT   = 3;
  const DIRECTION_TOP_LEFT      = 4;

  const DEFAULT_PLANET = 1;

  protected $chunks;
  protected $needGenerationChunks = array();
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

  public function getMap($x, $y)
  {
    $size = $this->getConfig()['view']['screen_size'];
    $lineLength = ($size * 2) + 1;
    $startX = $x - $size; $endX = $x + $size;
    $startY = $y - $size; $endY = $y + $size;
    $list = [];
    for ($iy = $startY; $iy <= $endY; $iy++) {
      for ($ix = $startX; $ix <= $endX; $ix++) {
        $list[] = [$ix, $iy];
      }
    }
    return $this->getCells($list);
  }

  public function getInfo()
  {
    $conf = $this->getConfig();
    $planet = $conf['planets'][$this->planet];
    return [
      'planet_size_x' => $planet['x'] * self::CHUNK_SIZE,
      'planet_size_y' => $planet['y'] * self::CHUNK_SIZE,
      'screen_size'   => $conf['view']['screen_size'],
      'cell_size'     => $conf['view']['cell_size'],
    ];
  }


  public function getCell($x, $y)
  {
    return $this->getCells([[$x, $y]])[$x][$y];
  }

  public function getCells($list = array())
  {
    $return = [];
    $cells = $this->getCellsIfExists($list, true);
    if ($this->checkAndGenerate()) {
      $cells = $this->getCellsIfExists($list);
    }
    return $cells;
  }

  public function getChunks($list = array())
  {
    $chunks = $this->getChunksIfExists($list, true);
    if ($this->checkAndGenerate()) {
      $chunks = $this->getChunksIfExists($list);
    }
    return $chunks;
  }

  protected function checkAndGenerate()
  {
    if(!$this->needGenerationChunks) {
      return false;
    }
    foreach ($this->needGenerationChunks as $pair) {
      $this->chunks[$pair[0]][$pair[1]] = $this->getGenerator()->getChunk($pair[0], $pair[1]);
    }
    $this->needGenerationChunks = [];
    return true;
  }

  /**
   * Returns chunks near the target chunk if they are exists
   * @param $x
   * @param $y
   * @return array
   */
  public function getChunksNeiboursIfExists($x, $y)
  {
    return $this->getChunksIfExists($this->getNeiboursArray($x, $y));
  }

  public function getChunksIfExists($list, $isMarkToGenerate = false)
  {
    $return = [];
    $forLoad = array();
    foreach ($list as $pair) {
      $this->prepareChunkCoordinats($pair[0], $pair[1]);
      if (isset($this->chunks[$pair[0]]) && isset($this->chunks[$pair[0]][$pair[1]])) {
        $return[$pair[0]][$pair[1]] = $this->chunks[$pair[0]][$pair[1]];
      } else {
        if (!in_array($pair, $forLoad)) {
          $forLoad[] = $pair;
        }
      }
    }
    if ($forLoad) {
      $this->loadChunks($forLoad, $isMarkToGenerate);
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

  public function addCell($x, $y, $cell)
  {
    if ($this->cells[$x][$y] === '00') {
      $this->cells[$x][$y] = $cell;
    } else {
      Logger::dump('here somehow we trying to create existing wrong cell');
    }
  }

  /**
   * Returns cells near neibours if they are exists
   * @param $x
   * @param $y
   * @return array
   */
  public function geCellsNeighboursIfExists($x, $y)
  {
    $list = $this->getNeiboursArray($x, $y);
    return $this->getCellsIfExists($list);
  }

  /**
   * @param $list array
   * @return array
   */
  public function getCellsIfExists($list, $isMarkToGenerate = false)
  {
    $return = [];
    $forLoad = array();
    foreach ($list as &$pair) {
      $this->prepareCellCoordinats($pair[0], $pair[1]);
      if (!isset($this->cells[$pair[0]]) || !isset($this->cells[$pair[0]][$pair[1]])) {
        $chunkPair = $this->cellToChunk($pair[0], $pair[1]);
        if (!in_array($chunkPair, $forLoad)) {
          $forLoad[] = $chunkPair;
        }
      }
    }
    if ($forLoad) {
      $this->loadChunks($forLoad, $isMarkToGenerate);
    }
    foreach ($list as $pair) {
      if (isset($this->cells[$pair[0]]) && isset($this->cells[$pair[0]][$pair[1]])) {
        $return[$pair[0]][$pair[1]] = $this->cells[$pair[0]][$pair[1]];
      } else {
        $return[$pair[0]][$pair[1]] = '00';
      }
    }
    return $return;
  }



  public function loadChunks($list, $isMarkToGenerate = false)
  {
    $ids = [];
    foreach ($list as $pair) {
      $ids[] = $this->getIdByCoords($pair[0], $pair[1]);
    }
    sort($ids);
    $result = Model_Map::getChunksById($ids);
    if ($result) {
      foreach ($result as $row) {
        $this->chunks[$row->x][$row->y] = $row->toArray();
        $this->explodeChunk($row->toArray(), true);
      }
    }
    foreach ($list as $pair) {
      if (!isset($this->chunks[$pair[0]]) || !isset($this->chunks[$pair[0]][$pair[1]])) {
        $this->chunks[$pair[0]][$pair[1]] = array();
        if($isMarkToGenerate) {
          $this->needGenerationChunks[] = $pair;
        }
        $x = ($pair[0] - 1) * self::CHUNK_SIZE;
        $y = ($pair[1] - 1) * self::CHUNK_SIZE;
        for ($i = 1; $i <= self::CHUNK_SIZE; $i++) {
          for ($i2 = 1; $i2 <= self::CHUNK_SIZE; $i2++) {
            $this->cells[$x + $i2][$y + $i] = '00';
          }
        }
      }
    }
  }

  /**
   * @param $chunk
   * @param bool $save
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
          $this->chunks[$chunk['x']][$chunk['y']]['cellsParsed'][$x + $i2][$y + $i] = $return[$x + $i2][$y + $i];
        }
      }
    }
    return $return;
  }

  /**
   * @param $x
   * @param $y
   * @return array
   */
  public function cellToChunk($x, $y)
  {
    $chunkX = ceil($x / self::CHUNK_SIZE);
    $chunkY = ceil($y / self::CHUNK_SIZE);
    return [$chunkX, $chunkY];
  }



  public function getConfig()
  {
    if (empty($this->config)) {
      $this->config = Service::getConfig()->get('all', 'map');
    }
    return $this->config;
  }



  /**
   * @param $x
   * @param $y
   * @param null $target
   * @return string
   */
  public function implodeChunk($x, $y)
  {
    $string = '';
    $addX = ($x - 1) * self::CHUNK_SIZE;
    $addY = ($y - 1) * self::CHUNK_SIZE;
    for ($i = 1; $i <= self::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= self::CHUNK_SIZE; $i2++) {
        $string .= $this->cells[$addX + $i2][$addY + $i];
      }
    }
    return $string;
  }

  /**
   * Returns ID by chunk coords
   * @param $x
   * @param $y
   * @return number
   */
  public function getIdByCoords($x, $y)
  {
    if ($x > $y) {
      return $this->centerIdFormula($x) + $x - $y;
    }
    return $this->centerIdFormula($y) + $x - $y;
  }
  private function centerIdFormula($n)
  {
    return pow($n, 2) - abs($n) + 1;
  }

  protected function getNeiboursArray($x, $y)
  {
    return [
      [$x+1, $y+1],
      [$x+1, $y  ],
      [$x+1, $y-1],
      [$x,   $y-1],
      [$x-1, $y-1],
      [$x-1, $y  ],
      [$x-1, $y+1],
      [$x,   $y+1],
    ];
  }
  public function getDirectNeiboursArray($x, $y)
  {
    return [
      [$x+1, $y  ],
      [$x,   $y-1],
      [$x-1, $y  ],
      [$x,   $y+1],
    ];
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

  protected function prepareChunkCoordinats(&$x, &$y)
  {
    $conf = $this->getConfig()['planets'][$this->planet];
    if ($x <= 0) {
      $x = $conf['x'] + $x;
    } elseif ($x > $conf['x']) {
      $x = $x % $conf['x'];
    }
    if ($y <= 0) {
      $y = $conf['y'] + $y;
    } elseif ($y > $conf['y']) {
      $y = $y % $conf['y'];
    }
  }
  protected function prepareCellCoordinats(&$x, &$y)
  {
    $conf = $this->getConfig()['planets'][$this->planet];
    $cellsX = $conf['x'] * self::CHUNK_SIZE;
    $cellsY = $conf['y'] * self::CHUNK_SIZE;
    if ($x <= 0) {
      $x = $cellsX + $x;
    } elseif ($x > $cellsX) {
      $x = $x % $cellsX;
    }
    if ($y <= 0) {
      $y = $cellsY + $y;
    } elseif ($y > $cellsY) {
      $y = $y % $cellsY;
    }
  }

}