<?php
/**
* WORK SMART
*/
?>
<script type="text/javascript">
  $(document).ready(function(){ 
    $('a.jt').cluetip({
      width:'550px',    
      cluetipClass: 'jtip',
      arrows: true,
      dropShadow: true,
      hoverIntent: false,
      sticky: true,
      mouseOutClose: true,
      closePosition: 'title',
      closeText: '<img src="'+sf_public_path+'/images/cross.png" alt="close" />',
      onActivate:  function(ct, ci){ $('#cluetip').hide().removeClass()},
      hoverIntent: {
        sensitivity:  1,
        interval:     50,
        timeout:      50
      }
    }); 
  });  
  
  $(document).bind('click', function() {
    $('#cluetip').hide().removeClass();
   });   
</script>
