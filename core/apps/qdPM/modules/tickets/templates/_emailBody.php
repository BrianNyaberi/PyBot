<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
