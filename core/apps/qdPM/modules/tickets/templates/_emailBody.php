<?php
/**
* WORK SMART
*/
?>

<?php

$template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateTwoColumns.html');


if($tickets->getProjectsId()>0)
{
  $title =  link_to($tickets->getProjects()->getName(),'projectsComments/index?projects_id=' . $tickets->getProjectsId(),array('absolute'=>true)) . ': ' .  link_to($tickets->getName(),'ticketsComments/index?projects_id=' . $tickets->getProjectsId() . '&tickets_id=' . $tickets->getId(),array('absolute'=>true));
}
else
{
  $title =  link_to($tickets->getName(),'ticketsComments/index?tickets_id=' . $tickets->getId(),array('absolute'=>true)) ;
}   
  

$column1 = '<h3>' . $title  . '</h3><p>' . 
          replaceTextToLinks($tickets->getDescription()) . '</p>' .
          '<p>' . ExtraFieldsList::renderDescriptionFileds('tickets',$tickets,$sf_user) . '<p>' . 
          get_component('attachments','attachmentsList',array('bind_type'=>'tickets','bind_id'=>$tickets->getId()));
          
$column2 = get_component('tickets','details',array('tickets'=>$tickets));

$column1 = app::setCssForEmailContent($column1) ; 
$column2 = app::setCssForEmailContent($column2);

echo str_replace(array('[COLUMN1]','[COLUMN2]'),array($column1,$column2),$template);          

?>
