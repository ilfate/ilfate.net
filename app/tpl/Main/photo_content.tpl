
<? /*
<?= Helper::exe('Main', 'Menu', array('awd')); ?>
<?= Helper::exe('Logger', 'index'); ?>
*/?>

<div class="hero-unit">
  <h1>Photo page is not ready yet!</h1>
</div>

<div class="row photo-gallery">
  <? foreach ($images_gallery as $image) { ?>
  <div class="photo">
      <img src="<?=$image?>" />
  </div>
  <? } ?>
  
</div>


