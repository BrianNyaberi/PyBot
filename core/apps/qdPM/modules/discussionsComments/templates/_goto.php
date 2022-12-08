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
<?php echo input_hidden_tag('previous_item_id',$previous_item_id) . input_hidden_tag('next_item_id',$next_item_id)?>
