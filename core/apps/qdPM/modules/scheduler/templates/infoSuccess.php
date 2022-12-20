<?php
/**
* WORK SMART
*/
?>

<table >
  <?php echo str_replace('</th>',':&nbsp;</th>',ExtraFieldsList::renderInfoFileds('events',$events,$sf_user)) ?>
  
</table>

<?php echo $events->getDetails() ?>

<div><?php include_component('attachments','attachmentsList',array('bind_type'=>'events','bind_id'=>$events->getEventId())) ?></div>

