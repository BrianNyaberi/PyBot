<?php
/**
* WORK SMART
*/
?>

<?php echo renderYuiMenu('order_by_menu', $m) ?>

<script type="text/javascript">
    YAHOO.util.Event.onContentReady("order_by_menu", function () 
    {
        var oMenuBar = new YAHOO.widget.MenuBar("order_by_menu", {
                                                autosubmenudisplay: true,
                                                hidedelay: 750,
                                                submenuhidedelay: 0,
                                                showdelay: 150,                                                                                                
                                                lazyload: true });
        oMenuBar.render();
    });
</script>
    
