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
<?php if(isset($projects) )include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<div class="row">
    <div class="col-md-9 project-info">
      
    <div class="panel panel-default item-description">
			<div class="panel-heading">
				<h3 class="panel-title">
        
          <table>
            <tr>
              <td><?php if(isset($projects)) include_component('ticketsComments','goto',array('tickets'=>$tickets,'projects'=>$projects)) ?></td>
              <td><?php echo ($tickets->getTicketsTypesId()>0 ? $tickets->getTicketsTypes()->getName(). ': ':'') . $tickets->getName() . ($tickets->getTicketsStatusId()>0 ? ' [' . $tickets->getTicketsStatus()->getName(). '] ':'') ?></td>    
              <td><?php if(isset($projects)) include_partial('ticketsComments/gotoNext') ?></td>
            </tr>
          </table>        
        
        </h3>
			</div>
			<div class="panel-body">


<div class="item-description-panel"> 
  <table>
    <?php if(Users::hasAccess('insert','ticketsComments',$sf_user,(isset($projects) ? $projects->getId():false))): ?>
      <td style="padding-right: 15px;"><?php echo link_to_modalbox('<i class="fa fa-comment-o"></i> ' . __('Add Comment'),'ticketsComments/new?tickets_id=' . $tickets->getId() . '&redirect_to=ticketsComments' . (isset($projects) ? '&projects_id=' . $projects->getId():'')) ?></td>
    <?php endif ?>
    
    <?php if(Users::hasAccess('edit','tickets',$sf_user,(isset($projects) ? $projects->getId():false))): ?>
      <td style="padding-right: 15px;"><?php echo link_to_modalbox('<i class="fa fa-edit"></i> ' . __('Edit Details'),'tickets/edit?id=' . $tickets->getId() . '&redirect_to=ticketsComments' . (isset($projects) ? '&projects_id=' . $projects->getId():'')) ?></td>
    <?php endif ?>
    
    <?php if(count($more_actions)>0): ?>
      <td>
        <?php echo renderYuiMenu('more_actions',$more_actions) ?>
        <script type="text/javascript">
          YAHOO.util.Event.onContentReady("more_actions", function () 
          {
              var oMenuBar = new YAHOO.widget.MenuBar("more_actions", {autosubmenudisplay: true,hidedelay: 750,submenuhidedelay: 0,showdelay: 150,lazyload: true });
              oMenuBar.render();
          });
      </script>
      
      </td>
    <?php endif ?>
  </table>
</div>

        <div class="itemDescription"><?php echo  replaceTextToLinks($tickets->getDescription()) ?></div>
        <div id="extraFieldsInDescription"><?php echo ExtraFieldsList::renderDescriptionFileds('tickets',$tickets,$sf_user) ?></div>
        <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'tickets','bind_id'=>$tickets->getId())) ?></div>
      </div>
    </div>


<?php
echo input_hidden_tag('item_name',$tickets->getName()) . input_hidden_tag('item_description',$tickets->getDescription());

$comments_access = Users::getAccessSchema('ticketsComments',$sf_user,(isset($projects) ? $projects->getId():false));
if($comments_access['view']):

$lc = new cfgListingController($sf_context->getModuleName(),'tickets_id=' . $tickets->getId() . (isset($projects) ? '&projects_id=' . $projects->getId():''));
?>


<?php  if($comments_access['insert'])echo $lc->insert_button(__('Add Comment')) ?>

<div <?php echo (count($tickets_comments)==0 ? 'class="table-scrollable"':'')?> >
<table class="table table-striped table-bordered table-hover" id="comments-table">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      <th width="100%"><?php echo __('Comment') ?></th>
      <th><?php echo __('Created At') ?></th>          
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets_comments as $c): ?>
    <tr>
      <td>
        <?php 
          if($comments_access['edit'] and $comments_access['view_own'])
          {
            if($c['users_id']==$sf_user->getAttribute('id')) echo $lc->action_buttons($c['id']);
          }
          elseif($comments_access['edit'])
          {
            echo $lc->action_buttons($c['id']);
          }
           ?>
      </td>            
      <td style="white-space:normal">
        <?php echo replaceTextToLinks($c['description']) ?>
        <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'ticketsComments','bind_id'=>$c['id'])) ?></div>
        <div><?php include_component('ticketsComments','info',array('c'=>$c)) ?></div>
      </td>
      <td><?php echo app::dateTimeFormat($c['created_at']) . '<br>' . $c['Users']['name'] . '<br>' .renderUserPhoto($c['Users']['photo']) ?></td>      
    </tr>
    <?php endforeach; ?>
    <?php if(count($tickets_comments)==0) echo '<tr><td colspan="3">' . __('No Records Found') . '</td></tr>' ?>
  </tbody>
</table>
</div>
<?php if($comments_access['insert']) echo $lc->insert_button(__('Add Comment')); ?>
<br><br>

<?php if(count($tickets_comments)>0) include_partial('global/jsPagerSimple',array('table_id'=>'comments-table')) ?>

<?php endif ?>

      
    </div>
    
    <div class="col-md-3">

    <div class="panel panel-info item-details">
  		<div class="panel-heading">  			
  			 <h3 class="panel-title"><?php echo __('Details') ?></h3>  			
  		</div>
  		<div class="panel-body item-details">            
      <?php include_component('tickets','details',array('tickets'=>$tickets)) ?>
      </div>
    </div>
    </div>
</div>   
