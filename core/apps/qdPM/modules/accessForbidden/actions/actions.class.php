<?php
/**

*/
?>
<?php

/**
 * accessForbidden actions.
 *
 * @package    sf_sandbox
 * @subpackage accessForbidden
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class accessForbiddenActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Access Forbidden',$this->getResponse());
  }
}
