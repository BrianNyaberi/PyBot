<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('Delete Selected?') ?></h4>
</div>

<?php
  $html = '';
  switch($sf_request->getParameter('table'))
  {
    case 'projects':
        $html = __('Are you sure you want to delete selected projects?') . '<br>' . __('Note: all Projects Tasks, Tickets and Discussions will be deleted as well.');
      break;
    default:
        $html = __('Are you sure you want to delete selected items?');
      break;
  } 
   
?>
<form action="<?php echo url_for('app/doMultipleDelete') ?>" method="post">
<div class="modal-body">
  <div><?php echo $html ?></div>
  <?php echo input_hidden_tag('selected_items') ?>
  <?php echo input_hidden_tag('table',$sf_request->getParameter('table')) ?>
  <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
</div>

<?php echo ajax_modal_template_footer(__('Delete')) ?>  
</form>

<script>
  set_selected_items();
</script>
