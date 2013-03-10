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

  const CHUNK_SIZE = 10;

  const DIRECTION_TOP_RIGHT     = 1;
  const DIRECTION_BOTTOM_RIGHT  = 2;
  const DIRECTION_BOTTOM_LEFT   = 3;
  const DIRECTION_TOP_LEFT      = 4;

  protected $chunks;


  public function conversion($x, $y)
  {
    $direction = $this->detectDirection($x, $y);
    $chunkX = ceil($x / self::CHUNK_SIZE);
    $chunkY = ceil($y / self::CHUNK_SIZE);
    $chunkId = $direction . $this->getIdByCoords($chunkX, $chunkY);

    $cellX = $x - (($chunkX - 1) * self::CHUNK_SIZE);
    $cellY = $y - (($chunkY - 1) * self::CHUNK_SIZE);


  }

  public function getIdByCoords($x, $y)
  {
    if ($x > $y) {
      return $this->centerIdFormula($x) + ($x - $y);
    }
    return $this->centerIdFormula($y) - ($y - $x);
  }

  private function centerIdFormula($n)
  {
    return pow($n, 2) - $n + 1;
  }

  /**
   * Here we detect chunk direction by cell`s X and Y
   * @param $x
   * @param $y
   * @return int
   */
  public function detectDirection($x, $y)
  {
    if ($x > 0) {
      if ($y > 0) return self::DIRECTION_TOP_RIGHT;
      return self::DIRECTION_BOTTOM_RIGHT;
    }
    if ($y > 0) return self::DIRECTION_TOP_LEFT;
    return self::DIRECTION_BOTTOM_LEFT;
  }
}