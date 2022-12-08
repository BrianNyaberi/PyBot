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
<?php
 
if($count_users==0)
{ 
    echo ajax_modal_template(__('Delete?'),'<div class="modal-body">' .__('Are you sure you want to delete Group?') . ' <b>' . $users_groups->getName() . '</b> ?<br>'. '</div>');
    
    echo '<form action="' . url_for('usersGroups/delete?id=' . $users_groups->getId()) . '" method="post"><input type="hidden" name="sf_method" value="put" />' . ajax_modal_template_footer(__('Delete')) . '</form>';
}
else
{
  echo ajax_modal_template(__('Delete?'),'<div class="modal-body">' .__("You can't delete this Group because there are Users assigned to this Group.")  . '<br>' . __('You have to delete users first.'). '</div>') . ajax_modal_template_footer_simple();   
}    
?>
