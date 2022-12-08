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
