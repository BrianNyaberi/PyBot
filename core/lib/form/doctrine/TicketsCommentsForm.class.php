<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * TicketsComments form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TicketsCommentsForm extends BaseTicketsCommentsForm
{
  public function configure()
  {
    $tickets = $this->getOption('tickets');
    
    if($this->getObject()->isNew())
    {        
      $this->widgetSchema['tickets_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TicketsStatus',true)),array('class'=>'form-control input-large'));
    }
    else
    {
      $this->widgetSchema['tickets_status_id'] = new sfWidgetFormInputHidden();
    }
    
                    
    $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
        
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();    
    $this->setDefault('created_at', date('Y-m-d H:i:s'));
    
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor-auto-focus'));
    
    $this->widgetSchema['tickets_id'] = new sfWidgetFormInputHidden();    
    $this->setDefault('tickets_id', $tickets->getId());
    
    $this->widgetSchema->setLabels(array('tickets_priority_id'=>'Priority',
                                         'tickets_types_id'=>'Type',
                                         'tickets_groups_id'=>'Group',                                         
                                         'tickets_status_id'=>'Status',                                         
                                         ));      
  }
}
