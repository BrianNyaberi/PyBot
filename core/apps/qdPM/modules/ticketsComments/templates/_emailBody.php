<?php
/**
* WORK SMART
*/
?>
<?php

$template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateTwoColumns.html');

$comments_html  = ' <table style="width:100%">';
    
$count = 0; 
foreach ($comments_history as $c){ 

  if($count==1)
  {
    $comments_html .= '<tr><td colspan="2"><br><h3>' . __('Previous Comments') . '</h3></td></tr>';
  }
  
  $count++;
   
  $comments_html .=  
    '<tr>
      <td style="font-family: Arial; font-size: 13px; color: black; border-bottom:1px dashed LightGray; padding:2px;">' . replaceTextToLinks($c->getDescription()) . '
        <div>' . get_component('attachments','attachmentsList',array('bind_type'=>'ticketsComments','bind_id'=>$c->getId())) . '</div>
        <div>' . get_component('ticketsComments','info',array('c'=>$c)) . '</div>            
      </td>
      <td style="width:25%; font-family: Arial; font-size: 13px; color: black; border-bottom:1px dashed LightGray; padding:2px;">' .  app::dateTimeFormat($c->getCreatedAt()) . '<br>' . $c->getUsers()->getName() . '<br>' .renderUserPhoto($c->getUsers()->getPhoto()) . '</td>      
    </tr>';      
}

$comments_html .= '</table>';

$column1 = '<h3>' . link_to($tickets->getProjects()->getName(),'projectsComments/index?projects_id=' . $tickets->getProjectsId(),array('absolute'=>true)) . ': '.  link_to($tickets->getName(),'ticketsComments/index?projects_id=' . $tickets->getProjectsId() . '&tickets_id=' . $tickets->getId(),array('absolute'=>true)) . '</h3><p>' . $comments_html . '</p>';
          
$column2 = get_component('tickets','details',array('tickets'=>$tickets));

$column1 = app::setCssForEmailContent($column1) ; 
$column2 = app::setCssForEmailContent($column2);

echo str_replace(array('[COLUMN1]','[COLUMN2]'),array($column1,$column2),$template);          

?>

