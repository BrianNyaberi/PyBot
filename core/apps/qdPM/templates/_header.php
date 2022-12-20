<?php
/**
* WORK SMART
*/
?>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->
				
    <?php             
      echo '<a class="navbar-brand" href="' . url_for("dashboard/index") . '">' . sfConfig::get('app_app_name') . '</a>';              
    ?>
    
		
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">		
    <?php echo image_tag('/template/img/menu-toggler.png') ?>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		
    <?php if($sf_user->isAuthenticated()): ?>
    <!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"  data-close-others="true">
				
        <?php if($sf_user->getAttribute('id')>0){ ?>
        <?php echo renderUserPhoto($sf_user->getAttribute('user')->getPhoto()) ?>
				<span class="username">
					 <?php echo $sf_user->getAttribute('user')->getName() ?>
				</span>
        <?php 
          }
          else
          {
            echo '<span class="username">' . sfConfig::get('app_administrator_email') . '</span>';
          }
        ?>
        
        
				<i class="fa fa-angle-down"></i>
				</a>
                
        <?php $m = new menuController($sf_user,$sf_request); echo renderDropDownMenu($m->buildUserMenu()) ?>
        
  		</li>
  		<!-- END USER LOGIN DROPDOWN -->
  	</ul>
  	<!-- END TOP NAVIGATION MENU -->
    <?php endif ?>
  
</div>
<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
