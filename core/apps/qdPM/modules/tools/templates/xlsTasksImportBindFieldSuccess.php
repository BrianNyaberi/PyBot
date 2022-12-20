<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('Bind Field') ?></h4>
</div>

<form class="form-horizontal" role="form" id="bind_filed" action="<?php url_for('tools/xlsTasksImportBindField') ?>" method="post">
<div class="modal-body">
  <div class="form-body">

<?php 
  echo '<input name="field_id" type="radio" value="0" id="field_id_0" checked="checked"> <label for="field_id_0">' . __('None') . '</label><br>';

  echo '<div id="extport_fields">' . select_tag('field_id','',array('choices'=>$columns,'expanded'=>true,'multiple'=>false)) . '</div>';
  echo input_hidden_tag('col',$sf_request->getParameter('col'));
?>

<br>
<input type="button" class="btn btn-primary" value="<?php echo __('Bind Field') ?>" onClick="bindField(<?php echo $sf_request->getParameter('col') ?>)">

  </div>
</div>

</form>
