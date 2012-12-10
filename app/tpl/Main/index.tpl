
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
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'CV', 'background' => '/images/my/ilfate.png')) ?>
    </a>
  </div>
  <div class="span3 offset1 main-page-block">
	<a href="<?=Helper::url('Main', 'code')?>" class="rounded_block_link">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Code', 'background' => '/images/php.jpg')) ?>
    </a>
  </div>
</div>
<div class="row show-grid">
  <div class="span3 offset1 main-page-block">
    <a href="<?=Helper::url('Game_Main')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Game', 'background' => '/images/game/tank1_s.jpg')) ?>
    </a>
  </div>
  <div class="span3 offset1 main-page-block" >
    <a href="<?=Helper::url('Main', 'photo')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Photo', 'background' => '/images/photo2.jpg')) ?>
    </a>
  </div>
</div>



