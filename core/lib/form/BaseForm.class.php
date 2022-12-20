<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Base project form.
 * 
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  public function setFieldValue($name, $value)
  {
    if(is_array($value))
    {
      $value = implode(',',$value);
    }
    
    $this->values[$name] = $value ;        
  }
  
  public function protectFieldsValue()
  {     
     foreach($this->values as $k=>$v)
     {
         if(is_string($v))
         {
             switch($k)
             {
                 case 'description':
                 case 'details':
                     $this->values[$k] = app::db_prepare_html_input($v);
                     break;
                 default:
                    $this->values[$k] = htmlspecialchars($v);
                     break;
             }
         }
     }          
  }
  
    
}
