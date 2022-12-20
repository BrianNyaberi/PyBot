<?php
/**
* WORK SMART
*/
?>
<?php

$template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateTwoColumns.html');

$column1 = '<h3>' . link_to($tasks->getProjects()->getName(),'projectsComments/index?projects_id=' . $tasks->getProjectsId(),array('absolute'=>true)) . ': ' .  link_to($tasks->getName(),'tasksComments/index?projects_id=' . $tasks->getProjectsId() . '&tasks_id=' . $tasks->getId(),array('absolute'=>true))  . '</h3><p>' . 
          replaceTextToLinks($tasks->getDescription()) . '</p>' . 
          get_component('attachments','attachmentsList',array('bind_type'=>'tasks','bind_id'=>$tasks->getId()));
          
$column2 = get_component('tasks','details',array('tasks'=>$tasks));

$column1 = app::setCssForEmailContent($column1) ; 
$column2 = app::setCssForEmailContent($column2);

echo str_replace(array('[COLUMN1]','[COLUMN2]'),array($column1,$column2),$template);          

?>
