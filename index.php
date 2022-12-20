<?php
/**

*/
?>
<?php

require_once(dirname(__FILE__).'/check.php');

require_once(dirname(__FILE__).'/core/lib/htmlpurifier/4.12.0/library/HTMLPurifier.auto.php');

require_once(dirname(__FILE__).'/core/config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('qdPM', 'prod', true);
sfContext::createInstance($configuration)->dispatch();
