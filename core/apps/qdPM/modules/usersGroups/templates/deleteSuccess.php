<?php
/**
* WORK SMART
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
