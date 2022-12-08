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
<?php

class myUser extends sfBasicSecurityUser
{
  public function __construct(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    $this->initialize($dispatcher, $storage, $options);

    if ($this->options['auto_shutdown'])
    {
      register_shutdown_function(array($this, 'shutdown'));
    }

    $this->setDefaultConfiguration();
  }
  
  public function setDefaultConfiguration()
  {
    $configuration_list = Doctrine_Core::getTable('Configuration')->createQuery()->execute();
    foreach($configuration_list as $configuration)
    {
      sfConfig::add(array($configuration->getKey()=>$configuration->getValue()));
    }
    
    if ($default_timezone = sfConfig::get('sf_default_timezone'))
    {
      date_default_timezone_set($default_timezone);
    }
  }
}
