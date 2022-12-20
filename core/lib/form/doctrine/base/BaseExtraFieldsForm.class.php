<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ExtraFields form base class.
 *
 * @method ExtraFields getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseExtraFieldsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'bind_type'       => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'sort_order'      => new sfWidgetFormInputText(),
      'active'          => new sfWidgetFormInputText(),
      'display_in_list' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'bind_type'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'type'            => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'sort_order'      => new sfValidatorInteger(array('required' => false)),
      'active'          => new sfValidatorInteger(array('required' => false)),
      'display_in_list' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('extra_fields[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ExtraFields';
  }

}
