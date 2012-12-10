<?= Csrf::createInput() ?>
<?= $this->render('menu.tpl') ?>



<div class="container main">
  <div class="row">
    <div class="span12">
      <div class="main-content-well well well-small ">
      <?= $content ?>
      </div>
    </div>

  </div>
</div>

<?= $this->render('included_templates/main.tpl') ?>
<?= Js::getHtml() ?>
