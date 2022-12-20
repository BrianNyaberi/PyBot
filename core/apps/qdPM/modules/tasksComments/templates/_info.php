<?php
/**
* WORK SMART
*/
?>
<ul class="comments-info-list">
<?php if($c['tasks_status_id']>0): ?>
  <li><?php echo '<span>' . __('Status') . ':</span> ' . app::getNameByTableId('TasksStatus',$c['tasks_status_id']) ?></li>
<?php endif ?>
<?php if($c['tasks_priority_id']>0): ?>
  <li><?php echo '<span>' . __('Priority') . ':</span> ' . app::getNameByTableId('TasksPriority',$c['tasks_priority_id']) ?></li>
<?php endif ?>
<?php if(strlen($c['due_date'])>0): ?>
  <li><?php echo '<span>' . __('Due Date') . ':</span> ' . app::dateTimeFormat($c['due_date']) ?></li>
<?php endif ?>
<?php if($c['worked_hours']>0): ?>
  <li><?php echo '<span>' . __('Work Hours') . ':</span> ' . $c['worked_hours'] ?></li>
<?php endif ?>
</ul>
