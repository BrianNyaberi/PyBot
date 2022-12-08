<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
