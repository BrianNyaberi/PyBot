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
<h3 class="page-title"><?php echo __('Extra fields') . ' (' . __($sf_request->getParameter('bind_type')) . ')' ?></h3>

<?php
$lc = new cfgListingController($sf_context->getModuleName(),'bind_type=' . $sf_request->getParameter('bind_type','projects'));
echo $lc->insert_button() . ' ' .  $lc->sort_button() . ' ' . button_tag_mmodalbox(__('Update Selected'),'extraFields/multipleEdit?bind_type=' . $sf_request->getParameter('bind_type'));
?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">

  <thead>
    <tr>     
      <th  style="width: 20px;"><input name="multiple_selected_all" id="multiple_selected_all" type="checkbox"></th> 
      <th><?php echo __('Action') ?></th>
      <th><?php echo __('Id') ?></th>
      <th><?php echo __('Type') ?></th>
      <th width="100%"><?php echo __('Name') ?></th>                        
      <th><?php echo __('In Listing?') ?></th>            
      <th><?php echo __('Active?') ?></th>      
      <th><?php echo __('Sort Order') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($extra_fieldss as $extra_fields): ?>
    <tr>    
      <td><?php echo input_checkbox_tag('multiple_selected[]',$extra_fields->getId(),array('class'=>'multiple_selected'))?></td>
      <td><?php echo $lc->action_buttons($extra_fields->getId()) ?></td>
      <td><?php echo $extra_fields->getId() ?></td>
      <td><?php echo ExtraFields::getTypeNameByKey($extra_fields->getType()) ?></td>
      <td><?php echo $extra_fields->getName() ?></td>                        
      <td><?php echo renderBooleanValue($extra_fields->getDisplayInList()) ?></td>      
      <td><?php echo renderBooleanValue($extra_fields->getActive()) ?></td>                  
      <td><?php echo $extra_fields->getSortOrder() ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(count($extra_fieldss)==0) echo '<tr><td colspan="10">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>

<script type="text/javascript">
  $(document).ready(function(){      
    
    $(".multiple_selected").click(function(){ get_selected_items() })
    
    $('#multiple_selected_all').click(function(){
    
      selected_items.length = 0;
      $( ".multiple_selected").each(function() {
             
        if($('#multiple_selected_all').attr('checked'))
        {                       
          set_checkbox_checked($(this).attr('id'),true);
          selected_items.push($(this).attr('value'));
        }
        else
        {
          set_checkbox_checked($(this).attr('id'),false);
          selected_items.length = 0;
        }
        
        
      });
    })
    
    function get_selected_items(){
      $( ".multiple_selected").each(function() { 
        if($(this).attr('checked'))
        {
          selected_items.push($(this).attr('value'));
        }
        else
        {
          selected_items = array_remove(selected_items,$(this).attr('value'))
        }
      });
    }
  
  });
</script>
