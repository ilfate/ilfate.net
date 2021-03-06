  <?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2013
 */


class Game_MapGenerator implements InterfaceGame_MapGenerator
{

  const DEFAULT_MULTIPLY_COEFFICIENT = 2;
  /**
   * @var Game_Map
   */
  protected $map;

  public function __construct(Game_Map $map)
  {
    $this->map = $map;
  }

  public function getChunk($x, $y)
  {
    $chunk = ['x' => $x, 'y' => $y];
    $neibours = $this->map->getChunksNeiboursIfExists($x, $y);
    $list = [];
    foreach ($neibours as $yList) {
      foreach ($yList as $neibour) {
        $list[] = $neibour;
      }
    }
    $chunk['biom'] = $this->getBiomByNeibours($list);
    $chunk['cells'] = $this->generateCells($chunk);
    $chunk['id'] = $this->map->getIdByCoords($chunk['x'], $chunk['y']);
    Model_Map::createChunk($chunk);
    return $chunk;
  }

  protected function getBiomByNeibours($neibours)
  {
    $chanses = [];
    $bioms = $this->map->getConfig()['bioms_list'];
    foreach ($neibours as $neibour) {
      if ($neibour) {
        if (!isset($chanses[$neibour['biom']])) {
          $chanses[$neibour['biom']] = 0;
        }
        $chanses[$neibour['biom']] += 10;
      }
    }
    if(count($chanses) > 1) {
      $newBiom = Math::customChance($chanses);
    }
    if(!isset($newBiom) || $newBiom === false) {

      $newBiom = Math::customChance($bioms);
    }
    return $newBiom;
  }

  protected function generateCells(&$chunk)
  {
    $addX = ($chunk['x'] - 1) * Game_Map::CHUNK_SIZE;
    $addY = ($chunk['y'] - 1) * Game_Map::CHUNK_SIZE;
    for ($i = 1; $i <= Game_Map::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= Game_Map::CHUNK_SIZE; $i2++) {
        $x = $i2 + $addX;
        $y = $i  + $addY;
        $cell = $this->randomCell($x, $y, $chunk['biom']);
        //$this->map->addCell($x, $y, $cell);
        $chunk['cellsParsed'][$x][$y] = $cell;
      }
    }
    // recheck cells
    for ($i = 1; $i <= Game_Map::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= Game_Map::CHUNK_SIZE; $i2++) {
        $x = $i2 + $addX;
        $y = $i  + $addY;

        $list = $this->map->getDirectNeiboursArray($x, $y);

        $neighboursCounted = [];
        $neighbours = $this->map->getCellsIfExists($list);
        foreach ($neighbours as $yList) {
          foreach ($yList as $neighbour) {
            if ($neighbour != '00') {
              if (!isset($neighboursCounted[$neighbour])) {
                $neighboursCounted[$neighbour] = 0;
              }
              $neighboursCounted[$neighbour]++;
            }
          }
        }
        arsort($neighboursCounted);
        if(reset($neighboursCounted) >= 3) {
          $chunk['cellsParsed'][$x][$y] = key($neighboursCounted);
        }
        $this->map->addCell($x, $y, $chunk['cellsParsed'][$x][$y]);
      }
    }
    return $this->map->implodeChunk($chunk['x'], $chunk['y']);
  }

  protected function randomCell($x, $y, $biom)
  {
    $neibours = $this->map->geCellsNeighboursIfExists($x, $y);
    $chanses = $this->getBiomChances($biom);
    $neighbourCells = [];

    foreach ($neibours as $yList) {
      foreach ($yList as $neighbour) {
        if ($neighbour !== '00') {
          if (!isset($neighbourCells[$neighbour])) {
            $neighbourCells[$neighbour] = 0;
          }
          $neighbourCells[$neighbour]++;
        }
      }
    }
    $conf = $this->getBiomConfig($biom, 'cell_multiply_coefficients');
    foreach ($neighbourCells as $cell => $value) {

      $k = isset($conf[$cell]) ? $conf[$cell] : self::DEFAULT_MULTIPLY_COEFFICIENT;
      $chanses[$cell] += pow($value, $k);
//      $chanses[$cell] += $value * 10;
    }
    return Math::customChance($chanses);
  }

  protected function getBiomChances($biom)
  {
    $default = $this->map->getConfig()['default_biom'];
    $ret = $this->getBiomConfig($default, 'cells_chances');
    if ($biom == $default) {
      return $ret;
    }
    $conf = $this->getBiomConfig($biom, 'cells_chances', true);
    if ($conf) {
      foreach ($conf as $key => $val) {
        $ret[$key] = $val;
      }
    }
    return $ret;
  }

  protected function getBiomConfig($biom, $type, $exact = false)
  {
    $conf = $this->map->getConfig();
    $default = $conf['default_biom'];
    if (isset($conf['bioms'][$biom]) && isset($conf['bioms'][$biom][$type])) {
      return $conf['bioms'][$biom][$type];
    } elseif($exact) {
      return false;
    }
    return $conf['bioms'][$default][$type];
  }
}