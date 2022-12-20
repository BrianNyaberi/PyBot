<?php
/**
* WORK SMART
*/
?>
<?php

$template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateTwoColumns.html');

$column1 = '<h3>' . link_to($projects->getName(),'projectsComments/index?projects_id=' . $projects->getId(),array('absolute'=>true)) . '</h3><p>' . 
          replaceTextToLinks($projects->getDescription()) . '</p>' . 
          get_component('attachments','attachmentsList',array('bind_type'=>'projects','bind_id'=>$projects->getId()));
          
$column2 = get_component('projects','details',array('projects'=>$projects));

$column1 = app::setCssForEmailContent($column1) ; 
$column2 = app::setCssForEmailContent($column2);

echo str_replace(array('[COLUMN1]','[COLUMN2]'),array($column1,$column2),$template);          

?>
