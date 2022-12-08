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
  $lc = new listingController('discussions',$url_params,$sf_request,$sf_user);
  $extra_fields = ExtraFieldsList::getFieldsByType('discussions',$sf_user);
  $totals = array();
  
  $is_filter = array();    
  $is_filter['status'] = app::countItemsByTable('DiscussionsStatus');
  
  
  $has_comments_access = Users::hasAccess('view','discussionsComments',$sf_user);
  
?>

<table width="100%">
  <tr>
    <td>
      <table>
        <tr>
          <?php if($display_insert_button): ?>
          <td style="padding-right: 15px;"><?php  echo $lc->insert_button(__('Add Discussion')) ?></td>
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
          <td><?php include_component('app','orderByMenu',array('module'=>'discussions')) ?></td>          
        </tr>
      </table>
    </td>
<?php endif ?>      
  </tr>
</table>

<?php include_partial('app/searchResult') ?>

<div <?php echo  (count($discussions_list)==0 ? 'class="table-scrollable"':'')?> >
      
<table class="table table-striped table-bordered table-hover discussions-table" id="itmes_listing_<?php echo $tlId ?>" <?php echo (count($discussions_list)>0 ? 'style="display:none"':'')?> >
  <thead>
    <tr>
      <?php echo $lc->rednerMultipleSelectTh() ?>
      <?php echo $lc->rednerActionTh() ?> 
      
      <th data-bsType="numeric"><div><?php echo __('Id') ?></div></th>
      
      <?php if($is_filter['status']): ?>
      <th><div><?php echo __('Status') ?></div></th>
      <?php endif; ?>
                 
      <th style="width: 100%"><div><?php echo __('Name') ?></div></th>               
      
      <th><div><?php echo __('Created By') ?></div></th>            
                      
      <?php echo ExtraFieldsList::renderListingThead($extra_fields) ?>
      
      <?php if(!$sf_request->hasParameter('projects_id')): ?>
      <th><div><?php echo __('Project') ?></div></th>
      <?php endif; ?> 
    </tr>
  </thead>
  <tbody>
    <?php foreach ($discussions_list as $discussions): ?>
    <tr>
      <?php echo $lc->rednerMultipleSelectTd($discussions['id']) ?>
      <?php echo $lc->renderActionTd($discussions['id'],(!$sf_request->hasParameter('projects_id') ? $discussions['projects_id']:0),false,__('Are you sure you want to delete discussion') . '\n' . $discussions['name'] . '?') ?>
      
      <td><?php echo $discussions['id'] ?></td>
      
      <?php if($is_filter['status']): ?>
      <td><?php echo app::getArrayName($discussions,'DiscussionsStatus') ?></td>
      <?php endif; ?>
                  
      <td>
        <?php echo link_to($discussions['name'],'discussionsComments/index?discussions_id=' . $discussions['id'] . ($discussions['projects_id']>0?'&projects_id=' . $discussions['projects_id']:'')) ?>
        <?php if($has_comments_access) echo  ' '. app::getLastCommentByTable('DiscussionsComments','discussions_id',$discussions['id']) ?> 
      </td>
                  
      <td><?php echo $discussions['Users']['name'] ?></td>            
                                                
      <?php
        $v = ExtraFieldsList::getValuesList($extra_fields,$discussions['id']); 
        echo ExtraFieldsList::renderListingTbody($extra_fields,$v);
        $totals = ExtraFieldsList::getListingTotals($totals, $extra_fields,$v)  
      ?>
      
      <?php if(!$sf_request->hasParameter('projects_id')): ?>
      <td><?php echo link_to(app::getArrayName($discussions,'Projects'),'projectsComments/index?projects_id=' . $discussions['projects_id']) ?></td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>        
  </tbody>
  
  <?php if(count($discussions_list)>0 and count($totals)>0): ?>
    <tfoot>
    <tr>
      <td colspan="<?php echo $lc->count_columns($is_filter,4) ?>"></td>
      <?php echo ExtraFieldsList::renderListingTotals($totals,$extra_fields) ?>
      <?php if(!$sf_request->hasParameter('projects_id')) echo '<td></td>' ?>
    </tr>  
    </tfoot>
    <?php endif ?>
    
    <?php if(sizeof($discussions_list)==0) echo '<tr><td colspan="20">' . __('No Records Found') . '</td></tr>';?>
</table>
</div>


<?php include_partial('global/pager', array('total'=>count($discussions_list), 'pager'=>$pager,'url_params'=>($sf_request->getParameter('projects_id')>0 ? 'projects_id=' . $sf_request->getParameter('projects_id'):'') . ($reports_id>0 ? 'id=' . $reports_id:'') ,'page'=>$sf_request->getParameter('page',1),'reports_action'=>'discussionsReports','reports_id'=>$reports_id)) ?>

<?php include_partial('global/jsTips'); ?>

<?php 
  if(count($discussions_list)>0)
  { 
    include_partial('global/jsPager',array('table_id'=>'itmes_listing_' . $tlId)); 
  }
?>


