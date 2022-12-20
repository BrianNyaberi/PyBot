<?php
/**

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
