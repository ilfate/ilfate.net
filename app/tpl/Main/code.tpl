
<? /*
<?= Helper::exe('Main', 'Menu', array('awd')); ?>
<?= Helper::exe('Logger', 'index'); ?>
*/?>

<div class="hero-unit">
  <h1>Here you may find some interesting open-source code!</h1>
</div>

<div class="row show-grid">
  <div class="span7 offset1 code-page-block" >
    <a href="<?=Helper::url('Main', 'codeEngine')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Ilfate PHP framework', 'background' => HTTP_ROOT.'images/php2.jpg')) ?>
    </a>
  </div>
</div>
<div class="row show-grid">
  <div class="span7 offset1 code-page-block" >
    <a href="<?=Helper::url('Game_Main')?>" class="rounded_block_link" data-target=".main-content-well">
      <?= $this->inc('interface/rounded_block.tpl', array('text' => 'Starred label', 'background' => HTTP_ROOT.'images/js3.jpg')) ?>
    </a>
  </div>
</div>


