<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Projects form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectsForm extends BaseProjectsForm
{
  public function configure()
  {        
    $this->widgetSchema['projects_types_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('ProjectsTypes')),array('class'=>'form-control input-large'));
    $this->setDefault('projects_types_id', app::getDefaultValueByTable('ProjectsTypes'));
            
    $this->widgetSchema['projects_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('ProjectsStatus')),array('class'=>'form-control input-large'));
    $this->setDefault('projects_status_id', app::getDefaultValueByTable('ProjectsStatus'));
        
  //($this->getObject()->isNew() ? 'autofocus':'')      
    $this->widgetSchema['name']->setAttributes(array('class'=>'form-control input-xlarge required autofocus'));
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor'));
          
    $this->widgetSchema['created_by'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();
    $this->setDefault('created_at', date('Y-m-d H:i:s'));
        
    
    $this->widgetSchema->setLabels(array('projects_types_id'=>'Type',                                         
                                         'projects_status_id'=>'Status'));

  }
}

