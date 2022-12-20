<?php
/**
* WORK SMART
*/
?>
<?php

class t
{
  public static function __($v)
  {
    return sfContext::getInstance()->getI18N()->__($v);
  }
  
  public static function renderJsDictionary()
  {
    $messages = array();
    $messages[] = "Loading...";
    $messages[] = "Are you sure?";
    $messages[] = "Please Select Items";    
    $messages[] = "Format";
    $messages[] = "Day";
    $messages[] = "Week";
    $messages[] = "Month";
    $messages[] = "Quarter";
    $messages[] = "Status";
    $messages[] = "Duration";
    $messages[] = "Progress";
    $messages[] = "Start Date";
    $messages[] = "Due Date";
    
    $messages[] = "Bold";
    $messages[] = "Italic";
    $messages[] = "Underline";
    $messages[] = "Left Align";
    $messages[] = "Numbered List";
    $messages[] = "Bulleted List";
    $messages[] = "Indent More";
    $messages[] = "Indent Less";
    $messages[] = "Strike";
    $messages[] = "Remove Formatting";
    $messages[] = "Horizontal Rule";
    $messages[] = "Image";
    $messages[] = "Text Color";
    $messages[] = "Background Color";
    $messages[] = "Source";
    $messages[] = "Code";    
    $messages[] = "Quote";
    $messages[] = "Paragraph";
        
    $content = '
    <script type="text/javascript">
      var I18NJSText = new Array()';
    foreach($messages as $value)
    {
      $content .= "
      I18NJSText['" . addslashes($value) . "']='" . addslashes(t::__($value)) . "';\n";
    }
    
    $content .= "</script>\n";
    
    return $content;
  }
}
  
