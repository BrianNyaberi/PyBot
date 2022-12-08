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
 * UsersGroups form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsersGroupsForm extends BaseUsersGroupsForm
{
  public function configure()
  {  
    $project_access_choices = array('1' => t::__('Full Access'),
                                  '2' => t::__('View Only'),
                                  '3' => t::__('View Own Only'),                                                                                                                        
                                  '4' => t::__('Manage Own Only'),
                                  );
                                                                                             
    $default_choices = array('' => t::__('None'),
                             '1' => t::__('Full Access'),
                             '2' => t::__('View Only'),         
                             '3' => t::__('View Own Only'),       
                             '4' => t::__('Manage Own Only'),                                                
                             ); 
                                                                                                               
                                                                                                                                                              
    $this->widgetSchema['allow_manage_projects'] = new sfWidgetFormChoice(array('choices' => $project_access_choices));
    $this->widgetSchema['allow_manage_tasks'] =  new sfWidgetFormChoice(array('choices' => $default_choices));
    $this->widgetSchema['allow_manage_tickets'] =  new sfWidgetFormChoice(array('choices' => $default_choices));
    $this->widgetSchema['allow_manage_discussions'] =  new sfWidgetFormChoice(array('choices' => $default_choices));    
    $this->widgetSchema['allow_manage_users'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->widgetSchema['allow_manage_configuration'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));    
    
                 
    $this->widgetSchema['name']->setAttribute('class','form-control required');
    $this->widgetSchema['allow_manage_projects']->setAttribute('class','form-control');
    $this->widgetSchema['allow_manage_tasks']->setAttribute('class','form-control');
    $this->widgetSchema['allow_manage_tickets']->setAttribute('class','form-control');
    $this->widgetSchema['allow_manage_discussions']->setAttribute('class','form-control');
    $this->widgetSchema['allow_manage_users']->setAttribute('class','form-control');
    $this->widgetSchema['allow_manage_configuration']->setAttribute('class','form-control');
    
    $this->widgetSchema->setLabels(array('allow_manage_projects'  => 'Projects',
                                         'allow_manage_tasks'  => 'Tasks',
                                         'allow_manage_tickets'  => 'Tickets',
                                         'allow_manage_discussions'  => 'Discussions',                              
                                         'allow_manage_configuration'  => 'Configuration',
                                         'allow_manage_users'  => 'Users',                                         
                                         
                                         ));
  }
}
