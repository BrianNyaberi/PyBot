<?php
/**
* WORK SMART
*/
?>
<?php

$template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateTwoColumns.html');

$column1 = '<h3>' . link_to($discussions->getProjects()->getName(),'projectsComments/index?projects_id=' . $discussions->getProjectsId(),array('absolute'=>true)) . ': ' .  link_to($discussions->getName(),'discussionsComments/index?projects_id=' . $discussions->getProjectsId() . '&discussions_id=' . $discussions->getId(),array('absolute'=>true)) . '</h3><p>' . 
          replaceTextToLinks($discussions->getDescription()) . '</p>' . 
          get_component('attachments','attachmentsList',array('bind_type'=>'discussions','bind_id'=>$discussions->getId()));
          
$column2 = get_component('discussions','details',array('discussions'=>$discussions));

$column1 = app::setCssForEmailContent($column1) ; 
$column2 = app::setCssForEmailContent($column2);

echo str_replace(array('[COLUMN1]','[COLUMN2]'),array($column1,$column2),$template);          

?>
