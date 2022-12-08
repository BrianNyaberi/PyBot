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
