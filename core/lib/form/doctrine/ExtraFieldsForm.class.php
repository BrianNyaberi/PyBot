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
 * ExtraFields form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ExtraFieldsForm extends BaseExtraFieldsForm
{
  public function configure()
  {
    $this->widgetSchema['active'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('active',1);
    
    $this->widgetSchema['display_in_list'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('display_in_list',0);        
        
    $this->widgetSchema['type'] = new sfWidgetFormChoice(array('choices'=>ExtraFields::getTypesChoices()));
                                
    $this->widgetSchema['bind_type'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['name']->setAttribute('class','form-control  required');
    $this->widgetSchema['type']->setAttribute('class','form-control');
    $this->widgetSchema['display_in_list']->setAttribute('class','form-control');
    $this->widgetSchema['active']->setAttribute('class','form-control');
    $this->widgetSchema['sort_order']->setAttribute('class','form-control input-xsmall');    

    $this->widgetSchema->setLabels(array('display_in_list'=>'Display in Listing?',
                                         'sort_order'=>'Sort Order',                                                                                  
                                         'active'=>'Active?',                                                                                  
                                         ));
  
  }
}
