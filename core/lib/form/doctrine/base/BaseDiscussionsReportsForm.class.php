<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * DiscussionsReports form base class.
 *
 * @method DiscussionsReports getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDiscussionsReportsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'users_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'name'                  => new sfWidgetFormInputText(),
      'display_on_home'       => new sfWidgetFormInputText(),
      'projects_id'           => new sfWidgetFormTextarea(),
      'projects_type_id'      => new sfWidgetFormTextarea(),
      'projects_status_id'    => new sfWidgetFormTextarea(),
      'discussions_status_id' => new sfWidgetFormTextarea(),
      'sort_order'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'users_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'display_on_home'       => new sfValidatorInteger(array('required' => false)),
      'projects_id'           => new sfValidatorString(array('required' => false)),
      'projects_type_id'      => new sfValidatorString(array('required' => false)),
      'projects_status_id'    => new sfValidatorString(array('required' => false)),
      'discussions_status_id' => new sfValidatorString(array('required' => false)),
      'sort_order'            => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('discussions_reports[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DiscussionsReports';
  }

}
