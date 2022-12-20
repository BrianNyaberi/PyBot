<?php
/**
* WORK SMART
*/
?>
<?php 
  foreach($extr_fields as $v)
  {
    $checked = false;
    
    if($sf_request->hasParameter('search_by_extrafields'))
    {
      $checked = in_array($v->getId(),$sf_request->getParameter('search_by_extrafields')); 
    }
        
    echo input_checkbox_tag('search_by_extrafields[]',$v->getId(),array('checked'=>$checked,'class'=>'yuimenecheckbox')) . ' <label for="search_by_extrafields_' . $v->getId() . '">' . $v->getName() . '</label><br>';
  }
?>
