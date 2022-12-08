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

//check allowed type
    $type = $sf_request->getParameter('type');
    if(!in_array($type,['general','features','email_options','ldap','login','user','tasks_columns_list']))
    {       
       $type = 'general';
    }
    
    
  
  if($sf_request->hasParameter('type'))
  {
    $default_selector = array();
    $default_selector['off'] = __('No');
    $default_selector['on'] = __('Yes');
  
    echo form_tag('configuration/index',array('enctype'=>'multipart/form-data','class'=>'form-horizontal')) . input_hidden_tag('type',$type) . '<div class="form-body">';
    include_partial('configuration/' . $type,array('default_selector'=>$default_selector));
    echo '<br>' . submit_tag(__('Save')) . '</div></form>';
  }
  
  
  
