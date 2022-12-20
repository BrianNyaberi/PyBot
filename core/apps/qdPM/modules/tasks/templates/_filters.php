<?php
/**
* WORK SMART
*/
?>
<div id="filtersMenuBox"><?php echo renderYuiMenu('filtersMenu',$m)?></div>

<script type="text/javascript">
    YAHOO.util.Event.onContentReady("filtersMenu", function () {
        var oMenuBar = new YAHOO.widget.MenuBar("filtersMenu", {
                                                autosubmenudisplay: true,
                                                hidedelay: 750,
                                                submenuhidedelay: 0,
                                                scrollincrement:10,
                                                showdelay: 150,
                                                keepopen: true, 
                                                lazyload: true });
        oMenuBar.render();
    });
</script>
