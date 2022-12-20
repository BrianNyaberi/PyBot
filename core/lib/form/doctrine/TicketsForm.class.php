<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Tickets form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TicketsForm extends BaseTicketsForm
{
  public function configure()
  {
    $projects = $this->getOption('projects');
    $sf_user = $this->getOption('sf_user');
    
      
    $this->widgetSchema['departments_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('Departments')),array('class'=>'form-control input-large required'));
    
    $this->widgetSchema['tickets_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TicketsStatus')),array('class'=>'form-control input-large'));
    $this->setDefault('tickets_status_id', app::getDefaultValueByTable('TicketsStatus'));
    
    $this->widgetSchema['tickets_types_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TicketsTypes')),array('class'=>'form-control input-large'));
    $this->setDefault('tickets_types_id', app::getDefaultValueByTable('TicketsTypes'));
    
    
    if($projects)
    {
      if(Users::hasAccess('edit','projects',$sf_user,$projects->getId())  and $this->getObject()->isNew())
      {
        $this->widgetSchema['users_id'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array_merge(array($sf_user->getAttribute('id')),array_filter(explode(',',$projects->getTeam()))),'tickets_insert')),array('class'=>'form-control input-large'));
      }
      else
      {
        $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
      }
    }
    
    $this->widgetSchema['name']->setAttributes(array('class'=>'form-control input-xlarge required autofocus'));
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor'));
    
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();               
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();
    $this->setDefault('created_at', date('Y-m-d H:i:s'));
    
    
    $this->widgetSchema->setLabels(array(
                                         'tickets_types_id'=>'Type',                                                                                                                                                                    
                                         'tickets_status_id'=>'Status',
                                         'departments_id'=>'Department',
                                         'name'=>'Subject',
                                         'users_id'=>'Created By',                                        
                                         ));
        
  }
}
