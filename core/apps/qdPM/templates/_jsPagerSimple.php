<?php
/**
* WORK SMART
*/
?>
<script type="text/javascript">
  $(document).ready(function(){
  
    var table = $('#<?php echo $table_id ?>').dataTable({
      "iDisplayLength": <?php echo sfConfig::get('app_rows_per_page') ?>,
      "sPaginationType": "bootstrap",
      "bSort": false,
      "bFilter":false,
      "bLengthChange":false,      
      "oLanguage": {                    
                        "oPaginate": {
                            "sPrevious": "<?php echo __('Previous Page')?>",
                            "sNext": "<?php echo __('Next Page')?>"
                        },
                        "sInfo": "<?php echo __('Displaying') ?> _START_ - _END_, <?php echo __('Total') ?>: _TOTAL_ "
                    }
      });
                                                                
  });
  
</script>  
