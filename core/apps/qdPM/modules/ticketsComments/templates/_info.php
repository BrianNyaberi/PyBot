<?php
/**
* WORK SMART
*/
?>
<ul class="listInfo">
<?php if($c['tickets_status_id']>0): ?>
  <li><?php echo '<span>' . __('Status') . ':</span> ' . app::getNameByTableId('TicketsStatus',$c['tickets_status_id']) ?></li>
<?php endif ?>  
</ul>
