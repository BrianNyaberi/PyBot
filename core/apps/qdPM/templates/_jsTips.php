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
