<?php
/**
* WORK SMART
*/
?>
<?php echo $c->getDescription() ?>
<div><?php include_component('attachments','attachmentsList',array('bind_type'=>'projectsComments','bind_id'=>$c->getId())) ?></div>
