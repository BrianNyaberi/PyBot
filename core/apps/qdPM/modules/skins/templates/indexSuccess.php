<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('Skins') ?></h4>
</div>

<div class="modal-body">



<div class="skinsList">
  <ul>
  <?php foreach($skins_list as $skin): ?>
    <li><?php echo $skin; ?><div style="border: 1px solid #b9b9b9; margin: 5px; width: 80px; height: 80px; cursor: pointer; background: white;" onClick="location='<?php echo url_for('skins/index?setSkin=' . $skin);?>'"><?php echo image_tag('/css/skins/' . $skin . '/' . $skin . '.png'); ?></div></li>
  <?php endforeach ?>
  </ul>
</div>

<?php echo ajax_modal_template_footer_simple(); ?>
