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
<h3 class="page-title"><?php echo __('Users') ?></h3>

<div><?php if(!$sf_request->hasParameter('search')) include_component('users','filtersPreview') ?></div>

<?php
$lc = new cfgListingController($sf_context->getModuleName());

    
$extra_fields = ExtraFieldsList::getFieldsByType('users',$sf_user,true);
?>


<table width="100%">
  <tr>
    <td><?php  echo $lc->insert_button(__('Add User')) . ' '.  button_tag_mmodalbox(__('Export Selected'),'users/export') ?></td>
    <td align="right"><?php include_component('app','searchMenuSimple') ?></td>
  </tr>
</table>


<?php include_partial('app/searchResult') ?>

<div <?php echo  (count($userss)==0 ? 'class="table-scrollable"':'')?> >
<table class="table table-striped table-bordered table-hover" id="table-users">
  <thead>
    <tr>  
      <th data-bSortable="false" style="width: 20px;"><input class="group-checkable" data-set="#table-users .checkboxes"  type="checkbox"></th>  
      <th data-bSortable="false"><div><?php echo __('Action') ?></div></th>
      <th><div><?php echo __('Id') ?></div></th>      
      <th><div><?php echo __('Group') ?></div></th>
      <th><div><?php echo __('Photo') ?></div></th>
      <th width="100%"><div><?php echo __('Name') ?></div></th>      
      <th><div><?php echo __('Email') ?></div></th>  
      
      <?php echo ExtraFieldsList::renderListingThead($extra_fields) ?>
          
      <th><div><?php echo __('Active?') ?></div></th>                      
    </tr>
  </thead>
  <tbody>
    <?php foreach ($userss as $users): ?>
    <tr>
      <td><input name="multiple_selected[]" id="multiple_selected_<?php echo $users->getId() ?>" type="checkbox" value="<?php echo $users->getId() ?>" class="checkboxes"</td>
      <td><?php echo ($sf_user->getAttribute('id')!= $users->getId() ? $lc->delete_button($users->getId()) . ' ':'') . $lc->edit_button($users->getId()) ?></td>
      <td><?php echo $users->getId() ?></td>
      <td><?php echo app::getObjectName($users->getUsersGroups())?></td>
      <td><?php echo renderUserPhoto($users->getPhoto()) ?></td>
      <td><?php echo link_to_modalbox($users->getName(),'users/info?id=' . $users->getId()) ?></td>      
      <td><?php echo $users->getEmail() ?></td>  
      
      <?php
        $v = ExtraFieldsList::getValuesList($extra_fields,$users->getId()); 
        echo ExtraFieldsList::renderListingTbody($extra_fields,$v); 
      ?>
                
      <td><?php echo renderBooleanValue($users->getActive()) ?></td>      
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($userss)==0) echo '<tr><td colspan="100">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>


<?php if(count($userss)>0)include_partial('global/jsPager',array('table_id'=>'table-users')) ?>

 
