<?php
/**
* WORK SMART
*/
?>
<?php if(count($menu)>0): $menu = array(array('title'=>'<a href="#" class="btn btn-default btn-xs purple"><i class="fa fa-angle-down"></i></a>','submenu'=>$menu)); ?>
  <div id="goToMenuContainer"><?php echo renderYuiMenu('goto_menu', $menu) ?></div>
  
<script type="text/javascript">
    YAHOO.util.Event.onContentReady("goto_menu", function () 
    {
        var oMenuBar = new YAHOO.widget.MenuBar("goto_menu", {autosubmenudisplay: true,hidedelay: 750,submenuhidedelay: 0,scrollincrement:10,showdelay: 150,lazyload: true });
        oMenuBar.render();
    });
</script>  
  
<?php endif ?>
<?php echo input_hidden_tag('previous_tasks_id',$previous_tasks_id) . input_hidden_tag('next_tasks_id',$next_tasks_id)?>
