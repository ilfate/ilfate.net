<?= Csrf::createInput() ?>
<?= $this->render('menu.tpl') ?>

<?= $content ?>

<?= $this->render('included_templates/main.tpl') ?>
<?= Js::getHtml() ?>