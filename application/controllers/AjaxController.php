<?php
use models\engine\PiaoliuHK_Models_Engine_goShipEngine;
require_once 'Zend/Controller/Action.php';
require_once APPLICATION_PATH . '/models/engine/goShipEngine.php';

class AjaxController extends Zend_Controller_Action
{

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AjaxController::indexAction() default action
    }

    public function init ()
    {
        // $this->area = new PiaoliuHK_Models_Engine_Area();
    }

    public function goshipfriststepAction ()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $PackageIDString = $request->getQuery('PackageID');
            $PackageIDArray = array_filter(explode("|", $PackageIDString));
            $FareArray = PiaoliuHK_Models_Engine_goShipEngine::getFareArraybyPackageIDArray(
                    $PackageIDArray);
        }
        
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        $CustomerSession->TransitBillSelectedPackageIDArray = $PackageIDArray;
        
        $this->_helper->json->sendJson(
                array(
                        'Fare' => $FareArray,
                        'FareSum' => array_sum($FareArray)
                ));
    }

    public function goshipsecondstepAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->TransitBillSelectedPackageIDArray)) {
            $TransitBillSelectedPackageIDArray = $CustomerSession->TransitBillSelectedPackageIDArray;
            
            $FeeArray = PiaoliuHK_Models_Engine_goShipEngine::getFeeArraybyPackageIDArray(
                    $TransitBillSelectedPackageIDArray);
            $this->_helper->json->sendJson(
                    array(
                            'Fee' => $FeeArray
                    ));
        }
    }
}
?>