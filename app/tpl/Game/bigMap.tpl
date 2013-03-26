


<div class="page-header">
  <h1>Games <small>Some of my games</small></h1>
</div>

<div class="row show-grid">

  <div class="span8" >
<? /*
  <? for ($i = $square-1; $i >= $startX; $i--) { ?>
    <? for ($i2 = $startX; $i2 < $square; $i2++) { ?>

      <div class="cell cell-<?=$map[$i2][$i]?>"></div>
    <? } ?>
      <div class="clear" ></div>
  <? } ?>
*/?>

    <? for ($i = $rangeY-1; $i >= $startY; $i--) { ?>
      <? for ($i2 = $startX; $i2 < $rangeX; $i2++) { ?>

        <div class="biom biom-<?=$map[$i2][$i]['biom']?>">
          <? $chunkCellsX = ($i2 - 1) * Game_Map::CHUNK_SIZE ?>
          <? $chunkCellsY = ($i - 1) * Game_Map::CHUNK_SIZE ?>
          <? for ($y = Game_Map::CHUNK_SIZE; $y > 0; $y--) { ?>
            <? for ($x = 1; $x <= Game_Map::CHUNK_SIZE; $x++) { ?>
              <div class="cell cell-<?=$map[$i2][$i]['cellsParsed'][$chunkCellsX + $x][$chunkCellsY + $y]?>"></div >
            <? } ?>
          <? } ?>
        </div>
      <? } ?>
      <div class="clear" ></div>
    <? } ?>
  </div>





</div>