
<? /*
<?= Helper::exe('Main', 'Menu', array('awd')); ?>
<?= Helper::exe('Logger', 'index'); ?>
*/?>

<div class="hero-unit">
  <h1>Hello! My name is Ilya Rubinchik and this is my website!</h1>
</div>

<div class="row show-grid">
  <div class="span3 offset1 main-page-block">
    <a href="<?=Helper::url('Cv')?>" class="rounded_block_link">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'CV', 'background' => HTTP_ROOT.'images/ilfate.png')) ?>
      <? /*
      <div class="img-text" >
        <div class="text extra-big invert" >CV</div>
        <img src="<?=HTTP_ROOT ?>images/ilfate.png" width="300px" height="300px" class="img-rounded">
      </div> */?>
    </a>
  </div>
  <div class="span3 offset1 main-page-block">
	<a href="<?=Helper::url('Main', 'code')?>" class="rounded_block_link">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Code', 'background' => HTTP_ROOT.'images/php.jpg')) ?>
    </a>
	  <? /*
      <div class="img-text" >
        <div class="text invert" >Game</div>
        <img src="<?=HTTP_ROOT ?>images/game/tank1_s.jpg" width="300px" height="300px" class="img-rounded">
      </div> */ ?>
  </div>
</div>
<div class="row show-grid">
  <div class="span3 offset1 main-page-block">
    <a href="<?=Helper::url('Game_Main')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Game', 'background' => HTTP_ROOT.'images/game/tank1_s.jpg')) ?>
    </a>
  </div>
  <div class="span3 offset1 main-page-block" >
    <a href="<?=Helper::url('Main', 'photo')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Photo', 'background' => HTTP_ROOT.'images/photo2.jpg')) ?>
    </a>
  </div>
</div>



