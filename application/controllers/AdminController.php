<?php
use models\form\PiaoliuHK_Models_Form_registerPackageForm;
use models\core\PiaoliuHK_Models_Core_Package;
use models\core\PiaoliuHK_Models_Core_PackageList;
use models\core\PiaoliuHK_Models_Core_Admin;
use models\core\PiaoliuHK_Models_Core_TransitBillList;
use models\core\PiaoliuHK_Models_Core_TransitBill;
use models\form\PiaoliuHK_Models_Form_checkoutTransitForm;
use models\form\PiaoliuHK_Models_Form_signTransitBillForm;
use models\form\PiaoliuHK_Models_Form_searchForm;
use models\core\PiaoliuHK_Models_Core_Customer;
use models\form\PiaoliuHK_Models_Form_CustomerListForm;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_SearchObjectType;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TransitBillStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageStatus;
use models\form\PiaoliuHK_Models_Form_PackageListPreviewForm;
use models\form\PiaoliuHK_Models_Form_PackageListForm;
use models\form\PiaoliuHK_Models_Form_TransitBillListPreviewForm;
use models\form\PiaoliuHK_Models_Form_TransitBillListForm;

/**
 * NewsController
 *
 * @author *
 *        
 * @version *
 *         
 *         
 */

require_once 'Zend/Controller/Action.php';
require_once APPLICATION_PATH . '/models/core/Admin.php';
require_once APPLICATION_PATH . '/models/core/PackageList.php';
require_once APPLICATION_PATH . '/models/core/TransitBillList.php';
require_once APPLICATION_PATH . '/models/form/registerPackageForm.php';
require_once APPLICATION_PATH . '/models/form/checkoutTransitForm.php';
require_once APPLICATION_PATH . '/models/form/signTransitBillForm.php';
require_once APPLICATION_PATH . '/models/form/searchForm.php';
require_once APPLICATION_PATH . '/models/form/CustomerListForm.php';
require_once APPLICATION_PATH . '/models/form/PackageListPreviewForm.php';
require_once APPLICATION_PATH . '/models/form/PackageListForm.php';
require_once APPLICATION_PATH . '/models/form/TransitBillListPreviewForm.php';
require_once APPLICATION_PATH . '/models/form/TransitBillListForm.php';

class AdminController extends Zend_Controller_Action
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
        echo 'Hello Admin,' . PiaoliuHK_Models_Core_Admin::findNamebyID($AdminID);
    }

    public function registerpackageAction ()
    {
        $this->view->PackageInTime = Zend_Date::now()->get(
                'YYYY-MM-dd HH:mm:ss');
    }

    public function registerpackageformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $registerPackageForm = new PiaoliuHK_Models_Form_registerPackageForm();
            if ($registerPackageForm->isValid($FormData)) {
                $registerPackage = new PiaoliuHK_Models_Core_Package();
                $registerPackage->setPackageOwnerMobile(
                        $registerPackageForm->getValue('PackageOwnerMobile'));
                $registerPackage->setPackageExpressCompany(
                        $registerPackageForm->getValue('PackageExpressCompany'));
                $registerPackage->setPackageExpressTrackNumber(
                        $registerPackageForm->getValue(
                                'PackageExpressTrackNumber'));
                $registerPackage->setPackageSnapshot(
                        $registerPackageForm->getValue('PackageSnapshot'));
                $registerPackage->setPackageWeight(
                        $registerPackageForm->getValue('PackageWeight'));
                $registerPackage->setPackageFare(
                        $registerPackageForm->getValue('PackageFare'));
                $registerPackage->setPackageInTime(
                        $registerPackageForm->getValue('PackageInTime'));
                /*
                 * $registerPackage->setPackageOutTime(
                 * $registerPackageForm->getValue('PackageOutTime'));
                 */
                PiaoliuHK_Models_Core_PackageList::registerTempPackage(
                        $registerPackage);
                if (PiaoliuHK_Models_Core_PackageList::ismatchable(
                        $registerPackage) == TRUE) {
                    echo '可匹配';
                } else {
                    echo '不可匹配';
                }
            } else {
                
                $this->view->result = "no";
            }
        } elseif ($this->_request->isGet()) {
            
            return NULL;
        } else {}
    }

    public function matchpackageAction ()
    {
        PiaoliuHK_Models_Core_PackageList::matchAllTempPackage();
    }

    public function rematchpackageAction ()
    {
        $UnmatchedPackageListArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                4);
        $UnmatchedPackageTableArray = array();
        foreach ($UnmatchedPackageListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                $PackageTable = [
                        "PackageTempID" => $Value->getPackageID(),
                        "PackageOwnerID" => $Value->getPackageOwnerID(),
                        "PackageOwnerMobile" => $Value->getPackageOwnerMobile(),
                        "PackageExpressCompany" => $Value->getPackageExpressCompany(),
                        "PackageExpressTrackNumber" => $Value->getPackageExpressTrackNumber(),
                        "PackageSnapshot" => $Value->getPackageSnapshot(),
                        "PackageWeight" => $Value->getPackageWeight(),
                        "PackageFare" => $Value->getPackageFare(),
                        "PackageInTime" => $Value->getPackageInTime(),
                        "PackageOutTime" => $Value->getPackageOutTime()
                ];
                array_push($UnmatchedPackageTableArray, $PackageTable);
            }
        }
        $this->view->UnmatchedPackageList = $UnmatchedPackageTableArray;
        
        $LostPackageListArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                5);
        $LostPackageTableArray = array();
        foreach ($LostPackageListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                $PackageTable = [
                        "PackageTempID" => $Value->getPackageID(),
                        "PackageOwnerID" => $Value->getPackageOwnerID(),
                        "PackageOwnerMobile" => $Value->getPackageOwnerMobile(),
                        "PackageExpressCompany" => $Value->getPackageExpressCompany(),
                        "PackageExpressTrackNumber" => $Value->getPackageExpressTrackNumber(),
                        "PackageSnapshot" => $Value->getPackageSnapshot(),
                        "PackageWeight" => $Value->getPackageWeight(),
                        "PackageFare" => $Value->getPackageFare(),
                        "PackageInTime" => $Value->getPackageInTime(),
                        "PackageOutTime" => $Value->getPackageOutTime()
                ];
                array_push($LostPackageTableArray, $PackageTable);
            }
        }
        $this->view->LostPackageList = $LostPackageTableArray;
        
        $ReservationPackageListArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                6);
        $ReservationPackageTableArray = array();
        foreach ($ReservationPackageListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                $PackageTable = [
                        "PackageTempID" => $Value->getPackageID(),
                        "PackageOwnerID" => $Value->getPackageOwnerID(),
                        "PackageOwnerMobile" => $Value->getPackageOwnerMobile(),
                        "PackageExpressCompany" => $Value->getPackageExpressCompany(),
                        "PackageExpressTrackNumber" => $Value->getPackageExpressTrackNumber(),
                        "PackageSnapshot" => $Value->getPackageSnapshot(),
                        "PackageWeight" => $Value->getPackageWeight(),
                        "PackageFare" => $Value->getPackageFare(),
                        "PackageInTime" => $Value->getPackageInTime(),
                        "PackageOutTime" => $Value->getPackageOutTime()
                ];
                array_push($ReservationPackageTableArray, $PackageTable);
            }
        }
        $this->view->ReservationPackageList = $ReservationPackageTableArray;
    }

    public function rematchpackageformAction ()
    {}

    public function checkouttransitAction ()
    {
        $TransitBillListArray = PiaoliuHK_Models_Core_TransitBillList::findAllTransitBillbyTransitBillStatus(
                PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout);
        $TransitBillTableArray = array();
        $ShipTransitBillIDArray = array();
        foreach ($TransitBillListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_TransitBill) {
                $TransitBillTable = [
                        "TransitBillID" => $Value->getTransitBillID(),
                        "TransitBillOwnerID" => $Value->getTransitBillOwnerID(),
                        "TransitBillRelatedPackageIDArray" => implode(";", 
                                $Value->getTransitBillRelatedPackageIDArray()),
                        "TransitBillRelatedPackageQuantity" => $Value->getTransitBillRelatedPackageQuantity(),
                        "TransitBillPrice" => $Value->getTransitBillPrice(),
                        "TransitBillMethod" => $Value->getTransitBillMethod(),
                        "TransitBillSettlement" => $Value->getTransitBillSettlement(),
                        "TransitBillInitializationTime" => $Value->getTransitBillInitializationTime(),
                        "TransitBillSignDate" => $Value->getTransitBillSignDate(),
                        "TransitBillStatus" => $Value->getTransitBillStatus()
                ];
                $ShipTransitBillIDArray[$TransitBillTable["TransitBillID"]] = TRUE;
                array_push($TransitBillTableArray, $TransitBillTable);
            }
        }
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $AdminSession->ShipTransitBillIDArray = $ShipTransitBillIDArray;
        $this->view->TransitBillList = $TransitBillTableArray;
    }

    public function checkouttransitformAction ()
    {
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $ShipTransitBillIDArray = $AdminSession->ShipTransitBillIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $checkoutTransitForm = new PiaoliuHK_Models_Form_checkoutTransitForm();
            $checkoutTransitForm->setShipTransitBillID($ShipTransitBillIDArray);
            if ($checkoutTransitForm->isValid($FormData)) {
                foreach ($checkoutTransitForm->getValue('ShipTransitBillID') as $Value) {
                    PiaoliuHK_Models_Core_TransitBillList::updateTransitBillStatusbyID(
                            $Value, 
                            PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout, 
                            PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip);
                    PiaoliuHK_Models_Core_TransitBillList::produceSignature($Value);
                }
            } else {
                $this->view->result = "no";
            }
        } elseif ($this->_request->isGet()) {
            echo '非法访问';
        }
    }

    public function signtransitbillAction ()
    {}

    public function signtransitbillformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $signTransitForm = new PiaoliuHK_Models_Form_signTransitBillForm();
            if ($signTransitForm->isValid($FormData)) {
                $Result = PiaoliuHK_Models_Core_TransitBillList::verifySignature(
                        $signTransitForm->getValue('TransitBillID'), 
                        $signTransitForm->getValue('VerificationCode'));
                switch ($Result) {
                    case PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::Right:
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::Wrong:
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::None:
                        break;
                }
            }
        }
    }

    public function customerlistAction ()
    {
        $CustomerArray = PiaoliuHK_Models_Core_Customer::findAllCustomer();
        
        $CustomerTableArray = array();
        $CustomerIDArray = array();
        foreach ($CustomerArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_Customer) {
                $CustomerTable = [
                        "CustomerID" => $Value->getCustomerID(),
                        "CustomerName" => $Value->getCustomerName(),
                        "CustomerCollage" => $Value->getCustomerCollage(),
                        "CustomerSelfMobile" => $Value->getCustomerSelfMobile(),
                        "CustomerSelfAddress" => implode(";", 
                                $Value->getCustomerSelfAddress()),
                        "CustomerAvatarMobile" => $Value->getCustomerAvatarMobile(),
                        "CustomerAvatarAddress" => implode(";", 
                                $Value->getCustomerAvatarAddress()),
                        "CustomerMail" => $Value->getCustomerMail(),
                        "CustomerQQ" => $Value->getCustomerQQ(),
                        "CustomerRenren" => $Value->getCustomerRenren(),
                        "CustomerWeixin" => $Value->getCustomerWeixin(),
                        "CustomerAlipay" => $Value->getCustomerAlipay(),
                        "CustomerAccountStatus" => $Value->getCustomerAccountStatus()
                ];
                $CustomerIDArray[$CustomerTable["CustomerID"]] = TRUE;
                array_push($CustomerTableArray, $CustomerTable);
            }
        }
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $AdminSession->CustomerIDArray = $CustomerIDArray;
        $this->view->CustomerList = $CustomerTableArray;
    }

    public function customerlistformAction ()
    {
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $CustomerIDArray = $AdminSession->CustomerIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $CustomerListForm = new PiaoliuHK_Models_Form_CustomerListForm();
            
            $CustomerListForm->setCustomerIDArray($CustomerIDArray);
            if ($CustomerListForm->isValid($FormData)) {
                $this->_redirect(
                        '/help/customerdetail/CustomerID/' .
                                 $CustomerListForm->getValue('CustomerIDR'));
            }
        }
    }

    public function packagelistAction ()
    {
        $this->view->Signed = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed;
        $this->view->inTransit = PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit;
        $this->view->Checkout = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout;
        $this->view->Waiting = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting;
        $this->view->Unmatched = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched;
        $this->view->Lost = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost;
        $this->view->Reservation = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation;
    }

    public function packagelistpreviewformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $PackageListPreviewForm = new PiaoliuHK_Models_Form_PackageListPreviewForm();
            if ($PackageListPreviewForm->isValid($FormData)) {
                $PackageArray = array();
                switch ($PackageListPreviewForm->getValue('SearchPackageType')) {
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                        $PackageArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyPackageStatus(
                                PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting);
                        break;
                    default:
                        break;
                }
                $PackageTableArray = array();
                $PackageIDArray = array();
                foreach ($PackageArray as $Value) {
                    if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                        $PackageTable = [
                                "PackageID" => $Value->getPackageID(),
                                "PackageOwnerID" => $Value->getPackageOwnerID(),
                                "PackageOwnerMobile" => $Value->getPackageOwnerMobile(),
                                "PackageExpressCompany" => $Value->getPackageExpressCompany(),
                                "PackageExpressTrackNumber" => $Value->getPackageExpressTrackNumber(),
                                "PackageSnapshot" => $Value->getPackageSnapshot(),
                                "PackageWeight" => $Value->getPackageWeight(),
                                "PackageFare" => $Value->getPackageFare(),
                                "PackageInTime" => $Value->getPackageInTime(),
                                "PackageOutTime" => $Value->getPackageOutTime(),
                                "PackageStatus" => $Value->getPackageStatus()
                        ];
                        $PackageIDArray[$PackageTable["PackageID"]] = TRUE;
                        array_push($PackageTableArray, $PackageTable);
                    }
                }
                $AdminSession = new Zend_Session_Namespace('AdminSession');
                $AdminSession->PackageIDArray = $PackageIDArray;
                $this->view->PackageList = $PackageTableArray;
            }
        }
    }

    public function packagelistformAction ()
    {
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $PackageIDArray = $AdminSession->PackageIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $PackageListForm = new PiaoliuHK_Models_Form_PackageListForm();
            
            $PackageListForm->setPackageIDArray($PackageIDArray);
            if ($PackageListForm->isValid($FormData)) {
                $this->_redirect(
                        '/help/packagedetail/PackageID/' .
                                 $PackageListForm->getValue('PackageIDR'));
            }
        }
    }

    public function transitbilllistAction ()
    {
        $this->view->Checkout = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout;
        $this->view->inShip = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip;
        $this->view->Signed = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed;
    }

    public function transitbilllistpreviewformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $TransitBillListPreviewForm = new PiaoliuHK_Models_Form_TransitBillListPreviewForm();
            if ($TransitBillListPreviewForm->isValid($FormData)) {
                $TransitBillArray = array();
                switch ($TransitBillListPreviewForm->getValue(
                        'SearchTransitBillType')) {
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout:
                        $TransitBillArray = PiaoliuHK_Models_Core_TransitBillList::findAllTransitBillbyTransitBillStatus(
                                PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip:
                        $TransitBillArray = PiaoliuHK_Models_Core_TransitBillList::findAllTransitBillbyTransitBillStatus(
                                PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip);
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed:
                        $TransitBillArray = PiaoliuHK_Models_Core_TransitBillList::findAllTransitBillbyTransitBillStatus(
                                PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed);
                        break;
                    default:
                        break;
                }
                $TransitBillTableArray = array();
                $TransitBillIDArray = array();
                foreach ($TransitBillArray as $Value) {
                    if ($Value instanceof PiaoliuHK_Models_Core_TransitBill) {
                        $TransitBillTable = [
                                "TransitBillID" => $Value->getTransitBillID(),
                                "TransitBillOwnerID" => $Value->getTransitBillOwnerID(),
                                "TransitBillRelatedPackageIDArray" => implode(
                                        ";", 
                                        $Value->getTransitBillRelatedPackageIDArray()),
                                "TransitBillRelatedPackageQuantity" => $Value->getTransitBillRelatedPackageQuantity(),
                                "TransitBillPrice" => $Value->getTransitBillPrice(),
                                "TransitBillMethod" => $Value->getTransitBillMethod(),
                                "TransitBillSettlement" => $Value->getTransitBillSettlement(),
                                "TransitBillInitializationTime" => $Value->getTransitBillInitializationTime(),
                                "TransitBillSignDate" => $Value->getTransitBillSignDate(),
                                "TransitBillStatus" => $Value->getTransitBillStatus()
                        ];
                        $TransitBillIDArray[$TransitBillTable["TransitBillID"]] = TRUE;
                        array_push($TransitBillTableArray, $TransitBillTable);
                    }
                }
                $AdminSession = new Zend_Session_Namespace('AdminSession');
                $AdminSession->TransitBillIDArray = $TransitBillIDArray;
                $this->view->TransitBillList = $TransitBillTableArray;
            }
        }
    }

    public function transitbilllistformAction ()
    {
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $TransitBillIDArray = $AdminSession->TransitBillIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $TransitBillListForm = new PiaoliuHK_Models_Form_TransitBillListForm();
            
            $TransitBillListForm->setTransitBillIDArray($TransitBillIDArray);
            if ($TransitBillListForm->isValid($FormData)) {
                $this->_redirect(
                        '/help/transitbilldetail/TransitBillID/' .
                                 $TransitBillListForm->getValue('TransitBillIDR'));
            }
        }
    }

    public function searchAction ()
    {
        $this->view->SearchObjectType = array(
                "Package" => PiaoliuHK_Configs_GlobalConstant_SearchObjectType::Package,
                "TransitBill" => PiaoliuHK_Configs_GlobalConstant_SearchObjectType::TransitBill
        );
    }

    public function searchformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $searchForm = new PiaoliuHK_Models_Form_searchForm();
            if ($searchForm->isValid($FormData)) {
                switch ($searchForm->getValue('SearchObject')) {
                    case PiaoliuHK_Configs_GlobalConstant_SearchObjectType::Package:
                        $this->_redirect(
                                '/help/packagedetail/PackageID/' .
                                         $searchForm->getValue('PackageID'));
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_SearchObjectType::TransitBill:
                        $this->_redirect(
                                '/help/transitbilldetail/TransitBillID/' .
                                         $searchForm->getValue('TransitBillID'));
                        break;
                }
            }
        }
    }

    public function logoutAction ()
    {
        $AdminSession = new Zend_Session_Namespace('AdminSession');
        $AdminSession->unsetAll();
        $this->_redirect('http://www.kindex.com/');
    }
}
