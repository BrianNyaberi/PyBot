<?php
/**
* WORK SMART
*/
?>
<?php if(count($tickets_list)>0): ?>

<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details-users">
 <thead> 
  <tr>
    <th><?php echo __('Related Tickets') ?></th>
  </tr>
</thead>
<tbody>
<?php 
$status = array();
foreach($tickets_list as $tickets): 
if($tickets['tickets_status_id']>0) $status[] = $tickets['tickets_status_id'];
?>
  <tr id="related_ticket_<?php echo $tickets['id'] ?>">
    <td><?php echo link_to((isset($tickets['TicketsTypes'])?$tickets['TicketsTypes']['name'] . ': ':'') . $tickets['name'] . (isset($tickets['TicketsStatus']) ? ' [' . $tickets['TicketsStatus']['name'] . ']':''), 'ticketsComments/index?tickets_id=' . $tickets['id'] . '&projects_id=' . $tickets['projects_id'],array('absolute'=>true)) ?></td>
    <td style="text-align: right;"><?php if(!$is_email) echo '<a href="javascript: removeRelated(\'related_ticket_' . $tickets['id'] . '\',\'' . url_for('app/removeRelatedTicketWithTask?tickets_id=' . $tickets['id'] . '&tasks_id=' . $sf_request->getParameter('tasks_id')) . '\')" class="btn btn-default btn-xs purple" title="' . __('Delete Related') . '"><i class="fa fa-trash-o"></i></a>' ?></td>
  </tr>  
      
<?php endforeach ?>
</tbody>
</table>
</div>
<?php endif ?>
