<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('Update Selected?') ?></h4>
</div>
<div class="modal-body">
	 
<div><?php echo __('Just select value if you need to change it') ?></div><br>

<form class="form-horizontal" role="form"  action="<?php echo url_for('extraFields/multipleEdit?bind_type=' . $sf_request->getParameter('bind_type')) ?>" method="post">

<div class="form-group">
	<label class="col-md-3 control-label"><?php echo __('In Listing?') ?></label>
	<div class="col-md-9">
	<?php echo select_tag('in_listing','',array('choices'=>array(''=>'','1'=>__('Yes'),'0'=>__('No'))),array('class'=>'form-control input-small')) ?>	
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label"><?php echo __('Active?') ?></label>
	<div class="col-md-9">
	<?php echo select_tag('active','',array('choices'=>array(''=>'','1'=>__('Yes'),'0'=>__('No'))),array('class'=>'form-control input-small')) ?>	
	</div>
</div>

<?php echo ajax_modal_template_footer(__('Update')) ?>

<?php echo input_hidden_tag('selected_items') ?>
</form>

</div>

<script>
  set_selected_items();
</script>
