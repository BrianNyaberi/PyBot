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
 * colorTheme actions.
 *
 * @package    sf_sandbox
 * @subpackage colorTheme
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class skinsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->skins_list = array();
            
    $skinsDir = sfConfig::get('sf_web_dir') . '/css/skins/';
    
    if ($handle = opendir($skinsDir)) 
    {             
      while (false !== ($file = readdir($handle))) 
      {
        if ($file != "." && $file != ".." && is_dir($skinsDir . $file)) 
        {                     
          $this->skins_list[] = $file;
        }  
      }  
    }
    
    if($request->hasParameter('setSkin'))
    {
      $setSkin= $request->getParameter('setSkin');
      
      if(is_file(sfConfig::get('sf_web_dir') . '/css/skins/' . $setSkin . '/' . $setSkin . '.css'))
      {      
        $this->getResponse()->setCookie('skin', $setSkin, time()+31536000,'','');
        
        $users = Doctrine_Core::getTable('Users')->find($this->getUser()->getAttribute('id'));
        $users->setSkin($setSkin);
        $users->save();
      }
      else
      {
        $this->getUser()->setFlash('userNotices', array('type'=>'error','text'=>t::__('Skin style is not found. Please check next path:') . '/css/skins/' . $setSkin . '/' . $setSkin . '.css'));
      }
      
      $this->redirect('dashboard/index');
    }
  }
}
