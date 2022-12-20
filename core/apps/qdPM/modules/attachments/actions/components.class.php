<?php
/**
* WORK SMART
*/
?>
<?php

class attachmentsComponents extends sfComponents
{      
  public function executeAttachments()
  {
     Attachments::clearTmpUploadedFiles($this->getUser());
  
     $q= Doctrine_Core::getTable('Attachments')
                  ->createQuery()
                  ->addWhere('bind_id=?',$this->bind_id)
                  ->addWhere('bind_type=?',$this->bind_type)
                  ->orderBy('id');
                  
     if($this->bind_id>0)
     {
       $q->addWhere("bind_id='" . $this->bind_id . "' or (bind_id='-" . $this->getUser()->getAttribute('id') . "')");
     }
     else
     {
       $q->addWhere("bind_id='-" . $this->getUser()->getAttribute('id') . "'");
     }             
                  
    $this->attachments = $q->execute();                                                               
  }
  
  public function executeAttachmentsList()
  {  
    $this->attachments = Doctrine_Core::getTable('Attachments')
                  ->createQuery()
                  ->addWhere('bind_id=?',$this->bind_id)
                  ->addWhere('bind_type=?',$this->bind_type)
                  ->orderBy('id')
                  ->fetchArray();                      
  }
}
