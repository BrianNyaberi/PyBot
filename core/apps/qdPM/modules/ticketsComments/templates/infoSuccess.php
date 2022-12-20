<?php
/**
* WORK SMART
*/
?>
<?php echo $c->getDescription() ?>
<div><?php include_component('attachments','attachmentsList',array('bind_type'=>'ticketsComments','bind_id'=>$c->getId())) ?></div>
<div><?php include_component('ticketsComments','info',array('c'=>$c)) ?></div>
