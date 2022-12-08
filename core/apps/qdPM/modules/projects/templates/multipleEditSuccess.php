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
	<h4 class="modal-title"><?php echo __('Update Selected?') ?></h4>
</div>

<form class="form-horizontal" role="form"  action="<?php echo url_for('projects/multipleEdit') ?>" method="post">
<div class="modal-body">
  <div class="form-body">

<div><?php echo __('Just select value if you need to change it') ?></div><br>
  
<?php echo input_hidden_tag('redirect_to',$sf_request->getParameter('redirect_to')) ?>


<?php foreach($fields as $k=>$f): if(count($f['choices'])==0) continue; ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $f['title'] ?></label>
      	<div class="col-md-9">
      		<?php echo select_tag('fields[' . $k . ']','',array('choices'=>$f['choices'],'multiple'=>(isset($f['multiple'])?true:false),'expanded'=>(isset($f['expanded'])?true:false)),array('class'=>'form-control input-large')) ?>
      	</div>
      </div>   
<?php endforeach ?>

<?php echo input_hidden_tag('selected_items') ?>

  </div>
</div>

<?php echo ajax_modal_template_footer(__('Update')) ?>

</form>

<script>
  set_selected_items();
</script>
