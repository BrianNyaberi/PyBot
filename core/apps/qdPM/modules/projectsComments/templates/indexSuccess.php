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
<?php include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<div class="row">
    <div class="col-md-9 project-info">
      
    <div class="panel panel-default item-description">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __('Description') ?></h3>
			</div>
			<div class="panel-body">
        <div class="itemDescription"><?php echo  replaceTextToLinks($projects->getDescription()) ?></div>
        <div id="extraFieldsInDescription"><?php echo ExtraFieldsList::renderDescriptionFileds('projects',$projects,$sf_user) ?></div>
        <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'projects','bind_id'=>$projects->getId())) ?></div>
      </div>
    </div>
    
    
  
<?php
$comments_access = Users::getAccessSchema('projectsComments',$sf_user,$projects->getId());
if($comments_access['view']):

$lc = new cfgListingController($sf_context->getModuleName(),'projects_id=' . $sf_request->getParameter('projects_id'));
?>


<?php  if($comments_access['insert'])echo $lc->insert_button(__('Add Comment')) ?>

<div <?php echo (count($projects_comments)==0 ? 'class="table-scrollable"':'')?> >
<table class="table table-striped table-bordered table-hover" id="comments-table">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      <th width="100%"><?php echo __('Comment') ?></th>
      <th><?php echo __('Created At') ?></th>          
    </tr>
  </thead>
  <tbody>
    <?php foreach ($projects_comments as $c): ?>
    <tr>
      <td>
        <?php 
          if($comments_access['edit'] and $comments_access['view_own'])
          {
            if($c['created_by']==$sf_user->getAttribute('id')) echo $lc->action_buttons($c['id']);
          }
          elseif($comments_access['edit'])
          {
            echo $lc->action_buttons($c['id']);
          }
           ?>
      </td>            
      <td style="white-space:normal">
        <?php echo replaceTextToLinks($c['description']) ?>
        <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'projectsComments','bind_id'=>$c['id'])) ?></div>        
      </td>
      <td><?php echo app::dateTimeFormat($c['created_at']) . '<br>' . $c['Users']['name'] . '<br>' .renderUserPhoto($c['Users']['photo']) ?></td>      
    </tr>
    <?php endforeach; ?>
    <?php if(count($projects_comments)==0) echo '<tr><td colspan="3">' . __('No Records Found') . '</td></tr>' ?>
  </tbody>
</table>
</div>
<?php if($comments_access['insert']) echo $lc->insert_button(__('Add Comment')); ?>

<?php if(count($projects_comments)>0) include_partial('global/jsPagerSimple',array('table_id'=>'comments-table')) ?>

<br> <br>
<?php endif ?>
       
      
    </div>
    
    <div class="col-md-3">

    <div class="panel panel-info item-details">
  		<div class="panel-heading">  			
  			 <h3 class="panel-title"><?php echo __('Details') ?></h3>  			
  		</div>
  		<div class="panel-body item-details">            
      <?php include_component('projects','details',array('projects'=>$projects)) ?>
      </div>
    </div>
    </div>
</div>    
 

