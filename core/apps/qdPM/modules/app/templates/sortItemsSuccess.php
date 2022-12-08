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
	<h4 class="modal-title"><?php echo __('Sort Items') ?></h4>
</div>
<div class="modal-body">
	 
<?php echo __('Just move an item up or down.') ?>

<div class="dd" id="nestable_list_1">
  <ol class="dd-list" id="sorted_items" >
    <?php
      foreach($itmes as $v)
      {
        echo '<li class="dd-item" data-id="' . $v->getId() . '"><div class="dd-handle">' . $v->getName() . '</div></li>';
      }
    ?>
  </ol>
</div>

</div>

<form action="<?php echo url_for($sf_request->getParameter('t') . '/index' . ($sf_request->hasParameter('bind_type')?'?bind_type=' . $sf_request->getParameter('bind_type'):'')) ?>" method="post">
<div class="modal-footer">
  <button type="submit" class="btn btn-primary"><?php echo __('Save') ?></button>
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
</div>
</form>

<?php //echo button_to_tag(__('Save'),$sf_request->getParameter('t') . '/index' . ($sf_request->hasParameter('bind_type')?'?bind_type=' . $sf_request->getParameter('bind_type'):'')) ?>

<script>

$(function() {

  $('#nestable_list_1').nestable({maxDepth:1}).on('change', function(){
    droppableOnUpdate('nestable_list_1','<?php echo url_for("app/SortItemsProcess?t=" . $sf_request->getParameter("t"))?>')            
  });

  
});

</script>
