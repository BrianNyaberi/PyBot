<?php
/**
* WORK SMART
*/
?>
<?php

class languagesController
{
  static public function getLanguageCodes()
  {
    $dir = dir(sfConfig::get('sf_app_i18n_dir'));
    $codes = array('en');
    while ($file = $dir->read()) 
    {
      if (is_dir(sfConfig::get('sf_app_i18n_dir') . '/' . $file) and !strstr($file,'.')) 
      {
        $codes[] = $file;        
      }
    }                
    return  $codes;
  }
     
}
