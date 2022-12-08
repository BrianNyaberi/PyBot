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

/**
 * TicketsReports form base class.
 *
 * @method TicketsReports getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTicketsReportsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'users_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'name'               => new sfWidgetFormInputText(),
      'display_on_home'    => new sfWidgetFormInputText(),
      'projects_id'        => new sfWidgetFormTextarea(),
      'projects_type_id'   => new sfWidgetFormTextarea(),
      'projects_status_id' => new sfWidgetFormTextarea(),
      'departments_id'     => new sfWidgetFormTextarea(),
      'tickets_types_id'   => new sfWidgetFormTextarea(),
      'tickets_status_id'  => new sfWidgetFormTextarea(),
      'sort_order'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'users_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'display_on_home'    => new sfValidatorInteger(array('required' => false)),
      'projects_id'        => new sfValidatorString(array('required' => false)),
      'projects_type_id'   => new sfValidatorString(array('required' => false)),
      'projects_status_id' => new sfValidatorString(array('required' => false)),
      'departments_id'     => new sfValidatorString(array('required' => false)),
      'tickets_types_id'   => new sfValidatorString(array('required' => false)),
      'tickets_status_id'  => new sfValidatorString(array('required' => false)),
      'sort_order'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tickets_reports[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TicketsReports';
  }

}
