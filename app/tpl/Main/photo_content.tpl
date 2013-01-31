
<? /*
<?= Helper::exe('Main', 'Menu', array('awd')); ?>
<?= Helper::exe('Logger', 'index'); ?>
*/?>


<div class="page-header">
  <h1>Photos <small>Some photos of me</small></h1>
</div>
<div class="loader"></div>
<div class="row photo-gallery">
  <? foreach ($images_gallery as $image) { ?>
  <div class="photo" >
    <? /*<a onclick="Photo.openPhoto(this)" >*/?>
      <img src="<?=$image['img']?>" <?=(isset($image['down-shift'])?'data-down-shift="'.$image['down-shift'].'"':'')?> />
    <? /*</a>*/?>
  </div>
  <? } ?>
</div>

<!-- Modal -->
<div id="photoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
  <div class="modal-body">
  <p>Content is loading…</p>
  </div>
  <div class="modal-footer">
      <button class="btn" onclick="Photo.prevPhoto()">Prev</button>
      <button class="btn" onclick="Photo.nextPhoto()">Next</button>
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>



