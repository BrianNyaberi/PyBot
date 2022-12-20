<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ExtraFieldsList form base class.
 *
 * @method ExtraFieldsList getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseExtraFieldsListForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'extra_fields_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ExtraFields'), 'add_empty' => false)),
      'bind_id'         => new sfWidgetFormInputText(),
      'value'           => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'extra_fields_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ExtraFields'), 'required' => false)),
      'bind_id'         => new sfValidatorInteger(array('required' => false)),
      'value'           => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('extra_fields_list[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ExtraFieldsList';
  }

}
