<?php
/**
* WORK SMART
*/
?>
<ul class="listInfo">
<?php if($c['discussions_status_id']>0): ?>
  <li><?php echo '<span>' . __('Status') . ':</span> ' . app::getNameByTableId('DiscussionsStatus',$c['discussions_status_id']) ?></li>
<?php endif ?>
</ul>
