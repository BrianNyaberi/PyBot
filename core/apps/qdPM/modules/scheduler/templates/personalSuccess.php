<?php
/**
* WORK SMART
*/
?>

<h3 class="page-title"><?php echo __('Personal Scheduler') ?></h3>

<?php include_component('scheduler','viewScheduler', array('scheduler_time'=>'personal_scheduler_current_time','users_id'=>$sf_user->getAttribute('id'),'month'=>$sf_request->getParameter('month'))) ?>

<?php include_partial('global/jsTips'); ?>
