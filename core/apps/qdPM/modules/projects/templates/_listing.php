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
  $lc = new listingController('projects',$url_params,$sf_request,$sf_user);
  $extra_fields = ExtraFieldsList::getFieldsByType('projects',$sf_user);
  $totals = array();
  
  $is_filter = array();
  
  $is_filter['status'] = app::countItemsByTable('ProjectsStatus');
  $is_filter['type'] = app::countItemsByTable('ProjectsTypes');
  
  $has_comments_access = Users::hasAccess('view','projectsComments',$sf_user);      
?>   

<table width="100%">
  <tr>
    <td>
      <table>
        <tr>
          <?php if($display_insert_button): ?>
          <td style="padding-right: 15px;"><?php  echo $lc->insert_button(__('Add Project')) ?></td>
          <?php endif ?>
          <td style="padding-right: 15px;"><div id="tableListingMultipleActionsMenu"><?php echo $lc->rednerWithSelectedAction($tlId) ?></div></td>          
        </tr>
      </table>
    </td>
<?php if(!isset($is_dashboard) and !$reports_id): ?>    
    <td align="right">
      <table>
        <tr>
          <td><?php include_component('app','searchMenu') ?></td>
          <td><?php include_component('app','orderByMenu',array('module'=>'projects')) ?></td>          
        </tr>
      </table>
    </td>
<?php endif ?>    
  </tr>
</table>

<?php include_partial('app/searchResult') ?>

<div <?php echo  (count($projects_list)==0 ? 'class="table-scrollable"':'')?> >
      
<table class="table table-striped table-bordered table-hover projects-table" id="itmes_listing_<?php echo $tlId ?>"  <?php echo (count($projects_list)>0 ? 'style="display:none"':'')?> >
  <thead>
    <tr>
      <?php echo $lc->rednerMultipleSelectTh('itmes_listing_' . $tlId) ?>
      <?php echo $lc->rednerActionTh() ?>      
      <th data-bsType="numeric"><div><?php echo __('Id') ?></div></th>
      
      <?php if($is_filter['status']): ?>
      <th><div><?php echo __('Status') ?></div></th>  
      <?php endif; ?>
                            
      <th width="100%"><div><?php echo __('Name') ?></div></th>
      
      <?php if($is_filter['type']): ?>
      <th><div><?php echo __('Type') ?></div></th>
      <?php endif; ?>
                  
      <th><div><?php echo __('Created By') ?></div></th>
      <th><div><?php echo __('Created At') ?></div></th>
      
      <?php echo ExtraFieldsList::renderListingThead($extra_fields) ?>                  
    </tr>
  </thead>
  
  <tbody>  
    <?php foreach ($projects_list as $projects): ?>
    <tr>     
      <?php echo $lc->rednerMultipleSelectTd($projects['id']) ?>
      <?php echo $lc->renderActionTd($projects['id'],0,false,__('Are you sure you want to delete project') . '\n\n' . $projects['name'] . '?\n\n' . __('Note: all Projects Tasks, Tickets and Discussions will be deleted as well.')) ?>      
      <td><?php echo $projects['id'] ?></td>

      
      <?php if($is_filter['status']): ?>
      <td><?php echo app::getArrayName($projects,'ProjectsStatus') ?></td>
      <?php endif; ?>
                        
      <td>
        <?php echo link_to($projects['name'],'projects/open?projects_id=' . $projects['id'])  ?>
        <?php if($has_comments_access) echo  ' '. app::getLastCommentByTable('ProjectsComments','projects_id',$projects['id']) ?>
      </td>
      
      <?php if($is_filter['type']): ?>
      <td><?php echo app::getArrayName($projects,'ProjectsTypes') ?></td>
      <?php endif; ?>
            
      <td><?php echo app::getArrayName($projects,'Users') ?></td>
      <td><?php echo app::dateTimeFormat($projects['created_at'],0,true) ?></td>
      
      <?php
        $v = ExtraFieldsList::getValuesList($extra_fields,$projects['id']); 
        echo ExtraFieldsList::renderListingTbody($extra_fields,$v);
        $totals = ExtraFieldsList::getListingTotals($totals, $extra_fields,$v)  
      ?>
    </tr>
    <?php endforeach; ?>
    </tbody>
            
    <?php if(count($projects_list)>0 and count($totals)>0): ?>
    <tfoot>
    <tr>
      <td colspan="<?php echo $lc->count_columns($is_filter,5) ?>"></td>
      <?php echo ExtraFieldsList::renderListingTotals($totals,$extra_fields) ?>
    </tr>  
    </tfoot>
    <?php endif ?>
    
    <?php if(sizeof($projects_list)==0) echo '<tr><td colspan="20">' . __('No Records Found') . '</td></tr>';?>
  
</table>
  
</div>

<?php include_partial('global/pager', array('total'=>count($projects_list), 'pager'=>$pager,'url_params'=>($reports_id>0 ? 'id=' . $reports_id:''),'page'=>$sf_request->getParameter('page',1),'reports_action'=>'projectsReports','reports_id'=>$reports_id)) ?>

<?php include_partial('global/jsTips'); ?>

<?php 
  if(count($projects_list)>0)
  { 
    include_partial('global/jsPager',array('table_id'=>'itmes_listing_' . $tlId)); 
  }
?>
