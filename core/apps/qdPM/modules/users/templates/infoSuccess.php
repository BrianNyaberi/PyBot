<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo $users->getName() ?></h4>
</div>

<div class="modal-body">
<table class="table table-bordered">
  <tr>
    <td><?php echo __('Group') ?>:</td>
    <td><?php echo $users->getUsersGroups()->getName() ?></td>
  </tr>
  <tr>
    <td><?php echo __('Email') ?>:</td>
    <td><?php echo $users->getEmail() ?></td>
  </tr>
  <tr>
    <td><?php echo __('Photo') ?>:</td>
    <td><?php echo renderUserPhoto($users->getPhoto()) ?></td>
  </tr>
  
  <?php echo ExtraFieldsList::renderInfoFileds('users',$users,$sf_user) ?>
  
</table>
</div>

<?php echo ajax_modal_template_footer_simple() ?>  
