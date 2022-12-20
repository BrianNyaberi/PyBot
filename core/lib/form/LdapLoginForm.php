<?php
/**
* WORK SMART
*/
?>
<?php

class LdapLoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'user'   => new sfWidgetFormInput(),
      'password'    => new sfWidgetFormInputPassword(),

    ));
    
    $this->widgetSchema['user']->setAttribute('size','40');
    $this->widgetSchema['password']->setAttribute('size','40');
    
    $this->widgetSchema->setNameFormat('ldaplogin[%s]');

    $this->setValidators(array(
      'password'    => new sfValidatorString(array('required' => true)),
      'user'   => new sfValidatorString(array('required' => true)),
    ));

  }
}

?>
