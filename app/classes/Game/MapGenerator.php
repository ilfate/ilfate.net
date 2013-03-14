  <?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2013
 */


class Game_MapGenerator implements InterfaceGame_MapGenerator
{
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

  }

  protected function getBiomByNeibours($neibours)
  {
    $chanses = [];
    foreach ($neibours as $neibour) {
      if(!isset($chanses[$neibour['biom']])) {
        $chanses[$neibour['biom']] = 0;
      }
      $chanses[$neibour['biom']] += 12;
    }
    $newBiom = Math::chanses($chanses);
    if($newBiom === false) {
      $bioms = $this->map->getConfig()['bioms_list'];
      $newBiom = $bioms[array_rand($bioms)];
    }
    return $newBiom;
  }

  protected function generateCells($chunk)
  {
    $cells = [];
    for ($i = 1; $i <= Game_Map::CHUNK_SIZE; $i++) {
      for ($i2 = 1; $i2 <= Game_Map::CHUNK_SIZE; $i2++) {

      }
    }
  }
}