<?php
/**

*/
?>
<?php

/**
 * Attachments form base class.
 *
 * @method Attachments getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAttachmentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'bind_type' => new sfWidgetFormInputText(),
      'bind_id'   => new sfWidgetFormInputText(),
      'file'      => new sfWidgetFormInputText(),
      'info'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'bind_type' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'bind_id'   => new sfValidatorInteger(array('required' => false)),
      'file'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'info'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('attachments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attachments';
  }

}
