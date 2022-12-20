<?php
/**
* WORK SMART
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
