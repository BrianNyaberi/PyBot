<?php
/**
* WORK SMART
*/
?>
<?php

class LoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'email'   => new sfWidgetFormInput(),
      'password'    => new sfWidgetFormInputPassword(),

    ));
    
    $this->widgetSchema['email']->setAttributes(array('size'=>'40','class'=>'required email'));
    $this->widgetSchema['password']->setAttributes(array('size'=>'40','class'=>'required'));
    
    $this->widgetSchema->setNameFormat('login[%s]');

    $this->setValidators(array(
      'password'    => new sfValidatorString(array('required' => true)),
      'email'   => new sfValidatorEmail(array('required' => true)),
    ));

  }
}

?>
