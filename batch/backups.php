<?php
/**

*/
?>
<?php

require_once('config.php');
 
require_once(dirname(__FILE__).'/../core/config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('qdPMExtended', 'batch', true);
sfContext::createInstance($configuration);
 
// Remove the following lines if you don't use the database layer
$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();
 
// add code here
app::backup();

