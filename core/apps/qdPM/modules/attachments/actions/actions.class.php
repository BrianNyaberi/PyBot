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
 * attachments actions.
 *
 * @package    sf_sandbox
 * @subpackage attachments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class attachmentsActions extends sfActions
{
  public function executeUpload(sfWebRequest $request)
  {            
    $file = $request->getFiles();
    $filename = mt_rand(111111,999999)  . '-' . $file['Filedata']['name'];
    if(move_uploaded_file($file['Filedata']['tmp_name'], sfConfig::get('sf_upload_dir') . '/attachments/'  . $filename))
    {               
      $bind_id = $request->getParameter('bind_id');
      
      if((int)$bind_id==0)
      {
        $bind_id = -$this->getUser()->getAttribute('id');
      }
       
      $a = new Attachments();
      $a->setFile($filename);    
      $a->setBindType($request->getParameter('bind_type'));            
      $a->setBindId($bind_id);      
      $a->save();    
    }
                
    exit();
  }
  
  public function executePreview(sfWebRequest $request)
  {
     $q = Doctrine_Core::getTable('Attachments')
                  ->createQuery()                  
                  ->addWhere('bind_type=?',$request->getParameter('bind_type'))
                  ->orderBy('id');
                  
    if($request->getParameter('bind_id')>0)
     {
       $q->addWhere("bind_id='" . $request->getParameter('bind_id') . "' or (bind_id='-" . $this->getUser()->getAttribute('id') . "')");
     }
     else
     {
       $q->addWhere("bind_id='-" . $this->getUser()->getAttribute('id') . "'");
     }              
                                                          
    $this->attachments = $q->execute();        
  }
  
  public function executeSaveInfo(sfWebRequest $request)
  {
    if($request->hasParameter('attachments_info'))
    {   
      foreach($request->getParameter('attachments_info') as $id=>$v)
      {    
        if($a = Doctrine_Core::getTable('Attachments')->find($id))
        {            
          $a->setInfo($v);          
          $a->save();
        }      
      }
    } 
      
    exit();
  }
  
  public function executeDownload(sfWebRequest $request)
  {
    $this->forward404Unless($attachments = Doctrine_Core::getTable('Attachments')->find($request->getParameter('id')), sprintf('Object attachments does not exist (%s).', $request->getParameter('id')));
    
    $file_path = sfConfig::get('sf_upload_dir') . '/attachments/' . $attachments->getFile();
                    
    if(is_file($file_path))
    {
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
      header("Cache-Control: no-cache, must-revalidate");
      header("Pragma: no-cache");
      header("Content-Type: Application/octet-stream");
      header("Content-disposition: attachment; filename=" . substr(str_replace(' ','_',$attachments->getFile()),7));
  
      readfile($file_path);
    }
    else
    {
      echo 'File "' . $attachments->getFile() . '" not found';
    }
  
    exit();
  }
  
  public function executeView(sfWebRequest $request)
  {
    $this->forward404Unless($attachments = Doctrine_Core::getTable('Attachments')->find($request->getParameter('id')), sprintf('Object attachments does not exist (%s).', $request->getParameter('id')));
    
    $file_path = sfConfig::get('sf_upload_dir') . '/attachments/' . $attachments->getFile();
    
    if(!is_file($file_path))
    {
      echo 'File "' . $attachments->getFile() . '" not found';
    }
    
    if($size = getimagesize($file_path))
    {
      header("Content-type: {$size['mime']}");
      
      readfile($file_path);
    }
    else
    {
       $this->redirect('attachments/download?id=' . $attachments->getId());
    }
  
    exit();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    if($a = Doctrine_Core::getTable('Attachments')->find($request->getParameter('id')))
    {
      if(is_file($file_path = sfConfig::get('sf_upload_dir') . '/attachments/' . $a->getFile()))
      {
        unlink($file_path);
      }
      
      $a->delete();
    }
    
    exit();
  }
}
