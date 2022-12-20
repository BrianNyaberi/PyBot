<?php
/**
* WORK SMART
*/
?>
<!-- BEGIN SIDEBAR -->

<div class="page-sidebar-wrapper">
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse"> 
               
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu">
				<li class="sidebar-toggler-wrapper">
        
<?php
if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_app_logo')))
{
  echo '<div class="logo"><a  href="' .url_for("dashboard/index") . '">' . image_tag('/uploads/' . sfConfig::get('app_app_logo'), array('title'=>sfConfig::get('app_app_name'),'id'=>'navbar-brand-logo')) . '</a></div>';
}
?>         
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<div class="clearfix">
					</div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				
        <?php $m = new menuController($sf_user,$sf_request); echo renderSidebarMenu($m->buildMenu(),'',0,array('moduleName'=>$sf_context->getModuleName(),'actionName'=>$sf_context->getActionName(),'sf_request'=>$sf_request)) ?>
                            
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
</div>
<!-- END SIDEBAR -->
