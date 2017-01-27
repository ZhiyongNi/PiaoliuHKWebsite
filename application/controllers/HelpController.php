<?php
use models\core\PiaoliuHK_Models_Core_Package;
use models\core\PiaoliuHK_Models_Core_PackageList;
use models\core\PiaoliuHK_Models_Core_Admin;
use models\core\PiaoliuHK_Models_Core_TransitBillList;
use models\core\PiaoliuHK_Models_Core_TransitBill;
use models\form\PiaoliuHK_Models_Form_PackageDetailFrontform;
use models\form\PiaoliuHK_Models_Form_TransitBillDetailFrontform;
use models\form\PiaoliuHK_Models_Form_CustomerDetailFrontform;
use models\core\PiaoliuHK_Models_Core_Customer;
/**
 * NewsController
 *
 * @author *
 *        
 *        
 * @version *
 *         
 *         
 */

require_once 'Zend/Controller/Action.php';
require_once APPLICATION_PATH . '/models/core/Admin.php';
require_once APPLICATION_PATH . '/models/core/PackageList.php';
require_once APPLICATION_PATH . '/models/core/TransitBillList.php';
require_once APPLICATION_PATH . '/models/form/PackageDetailFrontform.php';
require_once APPLICATION_PATH . '/models/form/TransitBillDetailFrontform.php';
require_once APPLICATION_PATH . '/models/form/CustomerDetailFrontform.php';

class HelpController extends Zend_Controller_Action
{

    function init ()
    {
        $this->initView();
    }

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated NewsController::indexAction() default action
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $AdminID = $AdminSession->AdminID;
        $Admin = new PiaoliuHK_Models_Core_Admin();
        echo 'Hello Admin,' . $Admin->findNamebyID($AdminID);
    }

    public function packagedetailAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
        } elseif ($this->_request->isGet()) {
            $FormData = $this->_request->getParams();
        }
        
        $PackageDetailForm = new PiaoliuHK_Models_Form_PackageDetailFrontform();
        if ($PackageDetailForm->isValid($FormData)) {
            $PackageDetail = PiaoliuHK_Models_Core_PackageList::findPackagebyID(
                    $PackageDetailForm->getValue('PackageID'));
            if ($PackageDetail instanceof PiaoliuHK_Models_Core_Package) {
                $this->view->PackageID = $PackageDetail->getPackageID();
                $this->view->PackageOwnerID = $PackageDetail->getPackageOwnerID();
                $this->view->PackageOwnerMobile = $PackageDetail->getPackageOwnerMobile();
                $this->view->PackageExpressCompany = $PackageDetail->getPackageExpressCompany();
                $this->view->PackageExpressTrackNumber = $PackageDetail->getPackageExpressTrackNumber();
                $this->view->PackageSnapshot = $PackageDetail->getPackageSnapshot();
                $this->view->PackageWeight = $PackageDetail->getPackageWeight();
                $this->view->PackageFare = $PackageDetail->getPackageFare();
                $this->view->PackageInTime = $PackageDetail->getPackageInTime();
                $this->view->PackageOutTime = $PackageDetail->getPackageOutTime();
                $this->view->PackageStatus = $PackageDetail->getPackageStatus();
            } else {
                echo '无此包裹。';
            }
        }
    }

    public function transitbilldetailAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
        } elseif ($this->_request->isGet()) {
            $FormData = $this->_request->getParams();
        }
        $TransitBillDetailForm = new PiaoliuHK_Models_Form_TransitBillDetailFrontform();
        if ($TransitBillDetailForm->isValid($FormData)) {
            $TransitBillDetail = PiaoliuHK_Models_Core_TransitBillList::findTransitBillbyID(
                    $TransitBillDetailForm->getValue('TransitBillID'));
            if ($TransitBillDetail instanceof PiaoliuHK_Models_Core_TransitBill) {
                $this->view->TransitBillID = $TransitBillDetail->getTransitBillID();
                $this->view->TransitBillOwnerID = $TransitBillDetail->getTransitBillOwnerID();
                $this->view->TransitBillRelatedPackageIDArray = implode(";", 
                        $TransitBillDetail->getTransitBillRelatedPackageIDArray());
                $this->view->TransitBillRelatedPackageQuantity = $TransitBillDetail->getTransitBillRelatedPackageQuantity();
                $this->view->TransitBillPrice = $TransitBillDetail->getTransitBillPrice();
                $this->view->TransitBillMethod = $TransitBillDetail->getTransitBillMethod();
                $this->view->TransitBillSettlement = $TransitBillDetail->getTransitBillSettlement();
                $this->view->TransitBillInitializationTime = $TransitBillDetail->getTransitBillInitializationTime();
                $this->view->TransitBillSignDate = $TransitBillDetail->getTransitBillSignDate();
                $this->view->TransitBillStatus = $TransitBillDetail->getTransitBillStatus();
            } else {
                echo '无此运单。';
            }
        }
    }

    public function customerdetailAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
        } elseif ($this->_request->isGet()) {
            $FormData = $this->_request->getParams();
        }
        $CustomerDetailForm = new PiaoliuHK_Models_Form_CustomerDetailFrontform();
        if ($CustomerDetailForm->isValid($FormData)) {
            $CustomerDetail = PiaoliuHK_Models_Core_Customer::findCustomerbyID(
                    $CustomerDetailForm->getValue('CustomerID'));
            if ($CustomerDetail instanceof PiaoliuHK_Models_Core_Customer) {
                $this->view->CustomerID = $CustomerDetail->getCustomerID();
                $this->view->CustomerName = $CustomerDetail->getCustomerName();
                $this->view->CustomerCollage = $CustomerDetail->getCustomerCollage();
                $this->view->CustomerSelfMobile = $CustomerDetail->getCustomerSelfMobile();
                $this->view->CustomerSelfAddress = implode(";", 
                        $CustomerDetail->getCustomerSelfAddress());
                $this->view->CustomerAvatarMobile = $CustomerDetail->getCustomerAvatarMobile();
                $this->view->CustomerAvatarAddress = implode(";", 
                        $CustomerDetail->getCustomerAvatarAddress());
                $this->view->CustomerMail = $CustomerDetail->getCustomerMail();
                $this->view->CustomerQQ = $CustomerDetail->getCustomerQQ();
                $this->view->CustomerRenren = $CustomerDetail->getCustomerRenren();
                $this->view->CustomerWeixin = $CustomerDetail->getCustomerWeixin();
                $this->view->CustomerAlipay = $CustomerDetail->getCustomerAlipay();
            } else {
                echo '无此用户。';
            }
        }
    }
}
