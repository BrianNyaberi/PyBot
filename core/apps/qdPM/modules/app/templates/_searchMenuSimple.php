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

  $search = $sf_request->getParameter('search');
  
  $html_extra = get_component('app','extraFieldsInSearch',array('type'=>$sf_context->getModuleName()));
  
  $hmtl = '
    <form action="' . url_for($sf_context->getModuleName().'/index' . ($sf_request->hasParameter('projects_id') ? '?projects_id=' . $sf_request->getParameter('projects_id'):'')) . '" method="post">
    <table style="margin: 5px 0;" class="contentTable">
      <tr>        
        <td>' . input_tag('search[keywords]',(isset($search['keywords']) ? $search['keywords']:''),array('class'=>'form-control')). '</td>
        <td valign="top"><input type="submit" class="btn btn-default"  value="' . __('Search') . '"></td>
      </tr>
      ' . (strlen($html_extra)>0 ? '
      <tr>        
        <td>        
        ' . $html_extra  . '
        </td>        
      </tr>  
      ':'') . '
    </table>
    </form>
  ';

  $s = array();  
  $s[] = array('title'=>$hmtl);

  $m = array();
  $m[] = array('title'=>'<i class="fa fa-search"></i> ' . __('Search'),'submenu'=>$s);
    
?>
 
<table><tr><td><?php echo renderYuiMenu('search_menu', $m) ?></td></tr></table>
     
    
<script type="text/javascript">
    YAHOO.util.Event.onContentReady("search_menu", function () 
    {
        var oMenuBar = new YAHOO.widget.MenuBar("search_menu", {
                                                autosubmenudisplay: true,
                                                hidedelay: 4000,
                                                submenuhidedelay: 0,
                                                showdelay: 150,
                                                keepopen: true,                                                
                                                lazyload: true });
        oMenuBar.render();
    });
</script>
