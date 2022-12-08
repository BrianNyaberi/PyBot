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

class configurationActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if($request->hasParameter('delete_logo'))
    {
      if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_app_logo')))
      {
        unlink(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_app_logo'));
      }
    }
    
    if($request->hasParameter('delete_login_background'))
    {      
      if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_login_background')))
      {
        unlink(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_login_background'));
      }
    }
  
    if($request->hasParameter('cfg'))
    {
      $cfg = $request->getParameter('cfg');
                          
      foreach($cfg as $key=>$value)
      {
        $configuration = Doctrine_Core::getTable('Configuration')
        ->createQuery('c')
        ->addWhere('c.key = ?', $key)
        ->fetchOne();
        
        if(is_array($value))
        {
          $value = implode(',',$value);
        }                        
        
        if($key=='app_app_logo')
        {                      
          $file = $request->getFiles();
                                                
          if(strlen($file['cfg_app_app_logo_file']['name'])>0)
          {                        
            if(getimagesize($file['cfg_app_app_logo_file']['tmp_name']))
            {
              move_uploaded_file($file['cfg_app_app_logo_file']['tmp_name'], sfConfig::get('sf_upload_dir')  . '/'  . $file['cfg_app_app_logo_file']['name']);
              $value = $file['cfg_app_app_logo_file']['name'];
            }
          }
        }
        
        if($key=='app_login_background')
        {                      
          $file = $request->getFiles();
                                                
          if(strlen($file['cfg_app_login_background_file']['name'])>0)
          {                        
            if(getimagesize($file['cfg_app_login_background_file']['tmp_name']))
            {
              move_uploaded_file($file['cfg_app_login_background_file']['tmp_name'], sfConfig::get('sf_upload_dir')  . '/'  . $file['cfg_app_login_background_file']['name']);
              $value = $file['cfg_app_login_background_file']['name'];
            }
          }
        }
        
        if($key=='app_administrator_password')
        {
          if(strlen($value)>0)
          {
            $hasher = new PasswordHash(11, false);
            
            $value = $hasher->HashPassword($value);
          }
          else
          {
            continue;
          }
        }

        if($configuration)
        {
          $configuration->setValue($value);
          $configuration->save();
                    
        }
        else
        {
        
          $sql = "INSERT INTO configuration (configuration.key, configuration.value) VALUES ('" . $key . "', '" . $value. "')";          
          $connection = Doctrine_Manager::connection();
          $connection->execute($sql);          

        }
      }
      
      $this->getUser()->setFlash('userNotices', t::__('Configuration has been successfully updated'));
      $this->redirect('configuration/index?type=' . $request->getParameter('type'));
    }
    
    
    
    
    
    app::setPageTitle('Configuration',$this->getResponse());            
  }  
}
