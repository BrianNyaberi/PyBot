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
    <meta name = "robots" content = "noindex,nofollow">   
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport"/> 
    <meta name="MobileOptimized" content="320">  
                        
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
    <?php echo stylesheet_tag('/template/css/pages/login.css') ?>     
    <!-- END THEME STYLES -->
    
    <?php echo stylesheet_tag('app.css') ?>
    <?php
      $skin = $sf_request->getCookie ('skin', sfConfig::get('app_default_skin','default')); 
      echo (is_file('css/skins/' . $skin . '/' . $skin. '.css') ? stylesheet_tag('skins/' . $skin . '/' . $skin . '.css') : stylesheet_tag('skins/default/default.css')) 
    ?>
    
    <?php echo javascript_include_tag('/template/plugins/jquery-1.10.2.min.js') ?>
    

    <link rel="shortcut icon" href="<?php echo public_path('favicon.ico') ?>" />
    <link rel="apple-touch-icon" href="<?php echo public_path('favicon.png') ?>" />
    
<?php if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_login_background'))): ?>
<style>
.login {
  background: url(<?php echo app::public_path('uploads/' . sfConfig::get('app_login_background')) ?>) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;  
}   

.login-fade-in{
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: #333;
  opacity: 0.2;
  z-index: -1; 
}

.copyright{
  color: white !important;
}

</style>
<?php endif ?>        
    
  </head>
  <body class="login">
  
  <div class="login-fade-in"></div>
    
  <div class="login-page-logo">
	 
   <?php 
      if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_app_logo')))
      {
        echo image_tag('/uploads/' . sfConfig::get('app_app_logo'), array('title'=>sfConfig::get('app_app_name'),'id'=>'navbar-brand-logo'));
      }
      else
      {
        echo sfConfig::get('app_app_name');
      }         
    ?>
    
  </div>  
  <!-- BEGIN LOGIN -->
  <div class="content">
    
    <?php echo $sf_content ?>
  </div>
  
  <div class="copyright">
	 <a href="http://qdpm.net" target="_blank">qdPM <?php echo sfConfig::get('app_qdpm_version') ?></a> <br> Copyright &copy; <?php echo  date('Y') ?> <a href="http://qdpm.net" target="_blank">qdpm.net</a>
  </div>
            
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<?php echo javascript_include_tag('/template/plugins/respond.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/excanvas.min.js') ?>
<![endif]-->

<?php echo javascript_include_tag('/template/plugins/jquery-migrate-1.2.1.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap/js/bootstrap.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery.blockui.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery.cokie.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/uniform/jquery.uniform.min.js') ?>
<!-- END CORE PLUGINS -->

<?php echo javascript_include_tag('/template/plugins/jquery-validation/dist/jquery.validate.min.js') ?>
<?php echo javascript_include_tag('/template/plugins/jquery-validation/dist/additional-methods.min.js') ?>

<?php echo javascript_include_tag('/template/scripts/app.js') ?>

<script>
jQuery(document).ready(function() {    
   App.init();
   
});

</script>

<?php echo javascript_include_tag('app.js') ?>
<!-- END JAVASCRIPTS -->    
           
  </body>
</html>
