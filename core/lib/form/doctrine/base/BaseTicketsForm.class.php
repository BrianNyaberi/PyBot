<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Tickets form base class.
 *
 * @method Tickets getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTicketsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'departments_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Departments'), 'add_empty' => true)),
      'tickets_types_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TicketsTypes'), 'add_empty' => true)),
      'tickets_status_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TicketsStatus'), 'add_empty' => true)),
      'name'              => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'users_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'projects_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'add_empty' => false)),
      'created_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'departments_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Departments'), 'required' => false)),
      'tickets_types_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TicketsTypes'), 'required' => false)),
      'tickets_status_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TicketsStatus'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('required' => false)),
      'users_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'projects_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tickets[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tickets';
  }

}
