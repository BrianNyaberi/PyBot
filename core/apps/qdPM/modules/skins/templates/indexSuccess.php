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
