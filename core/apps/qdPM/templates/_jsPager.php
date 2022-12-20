<?php
/**
* WORK SMART
*/
?>
<script type="text/javascript">
  $(document).ready(function(){
  
    appHandleUniformCheckboxes()
    
    var columnSort = new Array; 
    $(this).find('#<?php echo $table_id ?> thead tr th').each(function(){
    
        
        sType = 'html';
        
        attr = $(this).attr('data-bsType');
        if (typeof attr !== typeof undefined && attr !== false) {
          sType = attr;          
        } 
    
        if($(this).attr('data-bSortable') == 'false') {
            columnSort.push({ "bSortable": false});
        } else {
            columnSort.push({ "bSortable": true,"sType":sType });
        }
    });
    
    
    jQuery('#<?php echo $table_id ?> tbody tr .checkboxes').change(function(){
         if($(this).attr('checked'))
         {
           selected_items.push($(this).attr('value'));
         }
         else
         {
           selected_items = array_remove(selected_items,$(this).attr('value'))
         }
                          
         
         $(this).parents('tr').toggleClass("active");
    });
  
    var table = $('#<?php echo $table_id ?>').dataTable({
      "iDisplayLength": <?php echo sfConfig::get('app_rows_per_page') ?>,
      "sPaginationType": "bootstrap",
      "bSort": true,
      "bFilter":false,
      "bLengthChange":false,
      "aoColumns": columnSort,
      "fnInitComplete": function (oSettings, json) { $(this).css('display','') },
      "oLanguage": {                    
                        "oPaginate": {
                            "sPrevious": "<?php echo __('Previous Page')?>",
                            "sNext": "<?php echo __('Next Page')?>"
                        },
                        "sInfo": "<?php echo __('Displaying') ?> _START_ - _END_, <?php echo __('Total') ?>: _TOTAL_ "
                    }
      });
                      
      jQuery('#<?php echo $table_id ?> .group-checkable').change(function () {      
                
                var checked = jQuery(this).is(":checked");
                selected_items.length = 0;
                
                jQuery( "input", table.fnGetNodes() ).each(function(){                  
                     if(checked)
                     {                        
                        selected_items.push($(this).attr('value'));   
                        
                        $(this).attr("checked", true);                    
                        $(this).parents('span').addClass("checked");
                        $(this).parents('tr').addClass("active");                                                                        
                     }
                     else
                     {                                                                                               
                        $(this).attr("checked", false);
                        $(this).parents('span').removeClass("checked");
                        $(this).parents('tr').removeClass("active");
                     }
                })                
      });                         
      
        
  });
    
</script>  
