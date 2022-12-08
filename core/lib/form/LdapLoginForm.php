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

class LdapLoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'user'   => new sfWidgetFormInput(),
      'password'    => new sfWidgetFormInputPassword(),

    ));
    
    $this->widgetSchema['user']->setAttribute('size','40');
    $this->widgetSchema['password']->setAttribute('size','40');
    
    $this->widgetSchema->setNameFormat('ldaplogin[%s]');

    $this->setValidators(array(
      'password'    => new sfValidatorString(array('required' => true)),
      'user'   => new sfValidatorString(array('required' => true)),
    ));

  }
}

?>
