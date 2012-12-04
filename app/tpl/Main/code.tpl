
<? /*
<?= Helper::exe('Main', 'Menu', array('awd')); ?>
<?= Helper::exe('Logger', 'index'); ?>
*/?>

<div class="hero-unit">
  <h1>Hello! My name is Ilya Rubinchik and this is my website!</h1>
</div>

<div class="row show-grid">
  <div class="span7 offset1 code-page-block" >
    <a href="<?=Helper::url('Game_Main')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Starred label', 'background' => HTTP_ROOT.'images/php.jpg')) ?>
    </a>
  </div>
</div>
<div class="row show-grid">
  <div class="span7 offset1 code-page-block" >
    <a href="<?=Helper::url('Game_Main')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Ilfate PHP framework', 'background' => HTTP_ROOT.'images/php.jpg')) ?>
    </a>
  </div>
</div>


