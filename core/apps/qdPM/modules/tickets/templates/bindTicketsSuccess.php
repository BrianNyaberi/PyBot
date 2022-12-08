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
<h1><?php echo __('Link Tickets') ?></h1>

<?php
  $is_filter = array();    
  $is_filter['status'] = app::countItemsByTable('TicketsStatus');
  $is_filter['type'] = app::countItemsByTable('TicketsTypes');
?>

<form action="<?php echo url_for('tickets/bindTickets') ?>" method="post" onSubmit="return hasCheckedInContainer('tableListing<?php echo $tlId ?>')">
<?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
<?php echo input_hidden_tag('related_tasks_id',$sf_request->getParameter('related_tasks_id')) ?>
<?php echo input_hidden_tag('related_discussions_id',$sf_request->getParameter('related_discussions_id')) ?>


<table>
  <tr>
    <td><?php echo __('Search') ?>&nbsp;</td>
    <td><input name="filter" id="filter-box<?php echo $tlId ?>" value="" maxlength="20" size="20" type="text">&nbsp;</td>
    <td><?php echo image_tag('icons/reset.png',array('id'=>'filter-clear-button'. $tlId,'title'=>__('Reset'),'class'=>'pointer'))?>&nbsp;</td>                              
  </tr>
</table>


<table class="tableListing" id="tableListing<?php echo $tlId ?>" style="display:none; min-width: 900px;">
  <thead>
    <th width="10" class="{sorter: false}"><div></div></th>
    <th><div><?php echo __('Id') ?></div></th>
    

    
    <?php if($is_filter['status']): ?>
    <th><div><?php echo __('Status') ?></div></th>
    <?php endif; ?>
    
    <?php if($is_filter['type']): ?>
    <th><div><?php echo __('Type') ?></div></th>
    <?php endif; ?>
          
    <th width="100%"><div><?php echo __('Name') ?></div></th>
    <th><div><?php echo __('Department') ?></div></th>
    
     
    
    <th><div><?php echo __('Created By') ?></div></th>
  </thead>    
  <tbody>
<?php foreach($tickets_list as $tickets): ?>
    <tr>
      <td><?php echo input_checkbox_tag('tickets[]',$tickets['id']) ?></td>
      <td><?php echo $tickets['id'] ?></td>
          
      <?php if($is_filter['status']): ?>
      <td><?php echo app::getArrayNameWithBg($tickets,'TicketsStatus') ?></td>
      <?php endif; ?>
      
      <?php if($is_filter['type']): ?>
      <td><?php echo app::getArrayNameWithBg($tickets, 'TicketsTypes') ?></td>
      <?php endif; ?>
                  
      <td>
        <?php echo link_to($tickets['name'],'ticketsComments/index?tickets_id=' . $tickets['id'] . ($tickets['projects_id']>0?'&projects_id=' . $tickets['projects_id']:'')) ?>        
      </td>
                  
      <td><?php echo app::getArrayName($tickets, 'Departments') ?></td>
                      
      <td><?php if(strlen($tickets['user_name'])>0){echo $tickets['user_name']; }else{ echo $tickets['Users']['name']; } ?></td>    
    </tr>  
<?php endforeach ?>
  
  <?php if(sizeof($tickets_list)==0) echo '<tr><td colspan="20">' . __('No Records Found') . '</td></tr>';?>

  </tbody>
</table>
  <?php echo submit_tag(__('Bind'))?>
</form>

<?php if(count($tickets_list)>0){ ?>
<script type="text/javascript">
  $(document).ready(function(){   
    $("#tableListing<?php echo $tlId ?>").css('display','table');    
    $("#tableListing<?php echo $tlId ?>").tablesorter({widgets: ['zebra']}).tablesorterFilter({filterContainer: "#filter-box<?php echo $tlId ?>",filterClearContainer: "#filter-clear-button<?php echo $tlId ?>"});                                      
  });     
</script>
<?php }else{ ?>
<script type="text/javascript">
  $(document).ready(function(){
    $("#tableListing<?php echo $tlId ?>").css('display','table');
    $('table.tableListing<?php echo $tlId ?> tbody tr:odd').addClass('odd')
  });
</script>
<?php } ?>
