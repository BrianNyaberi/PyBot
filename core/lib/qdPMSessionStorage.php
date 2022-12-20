<?php
/**
* WORK SMART
*/
?>
<?php

class qdPMSessionStorage extends sfSessionStorage
{
  public function initialize($options = null)
  {
    $request = sfContext::getInstance()->getRequest();

    // work-around for uploadify
    if ($request->getParameter('uploadify') == "onUpload")
    {
      $sessionName = $options["session_name"];
      if($value = $request->getParameter($sessionName))
      {
        session_name($sessionName);
        session_id($value);
      }
    }

    parent::initialize($options);
  }
}
