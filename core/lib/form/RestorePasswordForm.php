<?php
/**
* WORK SMART
*/
?>
<?php

class RestorePasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'email'   => new sfWidgetFormInput(),
    ));
    
    $this->widgetSchema['email']->setAttributes(array('size'=>'40','class'=>'required'));
    
    $this->widgetSchema->setNameFormat('restorePassword[%s]');

    $this->setValidators(array(
      'email'   => new sfValidatorEmail(),
    ));

  }
}

?>
