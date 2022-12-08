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
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
  <head>
    <?php include_title() ?>
    
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>  
    <meta name="MobileOptimized" content="320">  
    
    <?php echo stylesheet_tag('/js/yui2.9.0/build/menu/css/menu.css') ?>    
    <?php echo javascript_include_tag('yui2.9.0/build/yahoo-dom-event/yahoo-dom-event.js') ?>
    <?php echo javascript_include_tag('yui2.9.0/build/container/container-min.js') ?>
    <?php echo javascript_include_tag('yui2.9.0/build/menu/menu-min.js') ?>
    
    <?php echo stylesheet_tag('/template/plugins/jquery-ui-1.11.4/jquery-ui.structure.min.css') ?>    
                        
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <?php echo stylesheet_tag('/template/plugins/font-awesome/css/font-awesome.min.css') ?>
    <?php echo stylesheet_tag('/template/plugins/bootstrap/css/bootstrap.min.css') ?>
    <?php echo stylesheet_tag('/template/plugins/uniform/css/uniform.default.css') ?>
    <!-- END GLOBAL MANDATORY STYLES -->
                
    <!-- BEGIN THEME STYLES -->
    <?php echo stylesheet_tag('/template/css/style-conquer.css') ?>
    <?php echo stylesheet_tag('/template/css/style.css') ?>
    <?php echo stylesheet_tag('/template/css/style-responsive.css') ?>
    <?php echo stylesheet_tag('/template/css/plugins.css') ?>            
    <!-- END THEME STYLES -->
    
    <?php echo stylesheet_tag('/template/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') ?>
    <?php echo stylesheet_tag('/template/plugins/bootstrap-modal/css/bootstrap-modal.css') ?>    
    <?php echo stylesheet_tag('/template/plugins/fullcalendar-2.3.0/fullcalendar.css') ?>    
    <?php echo stylesheet_tag('/template/plugins/jquery-nestable/jquery.nestable.css') ?>
                                                            
    <?php echo javascript_include_tag('/template/plugins/jquery-1.10.2.min.js') ?>
                
    <?php echo stylesheet_tag('/template/plugins/select2/select2_conquer.css') ?>
    <?php echo stylesheet_tag('/template/plugins/data-tables/DT_bootstrap.css') ?>
    <?php echo javascript_include_tag('/template/plugins/data-tables/jquery.dataTables.js') ?>
    <?php echo javascript_include_tag('/template/plugins/data-tables/DT_bootstrap.js') ?>
    <?php echo javascript_include_tag('/template/plugins/select2/select2.min.js') ?>                        
    <?php echo stylesheet_tag('/template/plugins/bootstrap-datepicker/css/datepicker.css') ?>           
    <?php echo stylesheet_tag('/template/plugins/bootstrap-datetimepicker/css/datetimepicker.css') ?>    
    <?php echo javascript_include_tag('uploadifive-v1.2.2/jquery.uploadifive.min.js') ?>
    <?php echo stylesheet_tag('/js/uploadifive-v1.2.2/uploadifive.css') ?>    
    <?php echo javascript_include_tag('/template/plugins/ckeditor/ckeditor.js') ?>    
    <?php echo javascript_include_tag('app.js') ?>
    <?php echo javascript_include_tag('cluetip1.2.5/jquery.cluetip.min.js') ?>
    <?php echo stylesheet_tag('/js/cluetip1.2.5/jquery.cluetip.css') ?>
    
    <script>
    
      var sf_public_path = '<?php echo public_path("/") ?>';
      var selected_items = new Array();      
      var CKEDITOR_holders = new Array();
      
      function keep_session()
      {
        $.ajax({url: '<?php echo url_for("login/keepSession") ?>'});
      }
      
      $(function(){
         setInterval("keep_session()",600000);                                                                      
      });  
                    
    </script>
            
    <?php echo stylesheet_tag('app.css') ?>
    <?php
      $skin = $sf_request->getCookie ('skin', sfConfig::get('app_default_skin','default')); 
      echo (is_file('css/skins/' . $skin . '/' . $skin. '.css') ? stylesheet_tag('skins/' . $skin . '/' . $skin . '.css') : stylesheet_tag('skins/default/default.css')) 
    ?> 
                                
    <?php echo t::renderJsDictionary() ?>

    <link rel="shortcut icon" href="<?php echo public_path('favicon.ico') ?>" />
    <link rel="apple-touch-icon" href="<?php echo public_path('favicon.png') ?>" />
  </head>
  <body class="page-scale-reduced page-header-fixed yui-skin-sam <?php echo ($sf_request->getCookie('sidebar_closed','0')==1 ? 'page-sidebar-closed':''); ?>" id="yahoo-com">
        
    <?php include_partial('global/header'); ?>
    
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
      <?php include_partial('global/sidebar'); ?>
      
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
      	<div class="page-content-wrapper">
      		<div class="page-content">
           <div id="ajax-modal" class="modal fade" tabindex="-1" data-replace="true" data-keyboard="false" data-backdrop="static" data-focus-on=".autofocus"></div>
                                 
           <?php if($sf_user->hasFlash('userNotices')) include_partial('global/userNotices', array('userNotices' => $sf_user->getFlash('userNotices'))); ?>
           <?php if(is_dir(sfConfig::get('sf_web_dir') . '/install')) include_partial('global/userNotices', array('userNotices' => array('text'=>__('Please remove \'install\' folder'),'type'=>'warning'))); ?>
            
           <?php //include_partial('global/styles'); ?>
          
           <?php echo $sf_content ?> 
          <!-- END PAGE CONTENT-->
      		</div>
      	</div>
      </div>
      <!-- END CONTENT -->
    
    </div>
    <!-- END CONTAINER -->
    
    <?php include_partial('global/footer'); ?>
    
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<?php echo javascript_include_tag('/template/plugins/respond.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/excanvas.min.js') ?>
<![endif]-->
<?php echo javascript_include_tag('/template/plugins/jquery-migrate-1.2.1.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-ui-1.11.4/jquery-ui.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap/js/bootstrap.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery.blockui.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery.cokie.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/uniform/jquery.uniform.min.js') ?>
<!-- END CORE PLUGINS -->

<?php echo javascript_include_tag('/template/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap-modal/js/bootstrap-modal.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-validation/dist/jquery.validate.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-validation/dist/additional-methods.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-nestable/jquery.nestable.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') ?>

<?php echo javascript_include_tag('/template/scripts/app.js') ?>

<script>
jQuery(document).ready(function() {    
   App.init();
              
   qdpm_app_init(); 
        
});
              
</script>

<!-- END JAVASCRIPTS -->    
           
  </body>
</html>
