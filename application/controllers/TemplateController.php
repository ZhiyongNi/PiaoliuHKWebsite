<?php
use models\core\PiaoliuHK_Models_Core_PackageList;
use models\core\PiaoliuHK_Models_Core_Admin;
use models\core\PiaoliuHK_Models_Core_TransitBillList;
use models\form\PiaoliuHK_Models_Form_PackageDetailFrontform;
use models\form\PiaoliuHK_Models_Form_TransitBillDetailFrontform;
use models\core\PiaoliuHK_Models_Core_Customer;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TrackStatus;
use models\logistics\PiaoliuHK_Models_Logistics_PackageTrackInfo;
use models\logistics\PiaoliuHK_Models_Logistics_PackageTrackInfoList;
use models\logistics\PiaoliuHK_Models_Logistics_TransitBillTrackInfo;
use models\logistics\PiaoliuHK_Models_Logistics_TransitBillTrackInfoList;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TransitBillStatus;
use models\core\PiaoliuHK_Models_Core_CapCreditList;
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
require_once APPLICATION_PATH . '/models/logistics/PackageTrackInfoList.php';
require_once APPLICATION_PATH . '/models/logistics/PackageTrackInfo.php';
require_once APPLICATION_PATH . '/models/core/CapCreditList.php';

class TemplateController extends Zend_Controller_Action
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

    public function headerAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            $this->view->CustomerName = $Customer->getCustomerName();
        } else {
            $this->view->CustomerName = '请先登录，游客，这里是控制面板';
        }
    }

    public function taobaoAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            $Customer->initializeAvatar();
            
            $this->view->CustomerName = $Customer->getCustomerName();
            $this->view->CustomerCollage = $Customer->getCustomerCollage();
            $this->view->CustomerSelfMobile = $Customer->getCustomerSelfMobile();
            $this->view->CustomerAvatarMobile = $Customer->getCustomerAvatarMobile();
            $this->view->CustomerSelfAddress = $Customer->getCustomerSelfAddress();
            $this->view->CustomerAvatarAddress = $Customer->getCustomerAvatarAddress();
        }
    }

    public function jingdongAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            $Customer->initializeAvatar();
            
            $this->view->CustomerName = $Customer->getCustomerName();
            $this->view->CustomerCollage = $Customer->getCustomerCollage();
            $this->view->CustomerSelfMobile = $Customer->getCustomerSelfMobile();
            $this->view->CustomerAvatarMobile = $Customer->getCustomerAvatarMobile();
            $this->view->CustomerSelfAddress = $Customer->getCustomerSelfAddress();
            $this->view->CustomerAvatarAddress = $Customer->getCustomerAvatarAddress();
        }
    }

    public function amazonAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            $Customer->initializeAvatar();
            
            $this->view->CustomerName = $Customer->getCustomerName();
            $this->view->CustomerCollage = $Customer->getCustomerCollage();
            $this->view->CustomerSelfMobile = $Customer->getCustomerSelfMobile();
            $this->view->CustomerAvatarMobile = $Customer->getCustomerAvatarMobile();
            $this->view->CustomerSelfAddress = $Customer->getCustomerSelfAddress();
            $this->view->CustomerAvatarAddress = $Customer->getCustomerAvatarAddress();
        }
    }

    public function dangdangAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            $Customer->initializeAvatar();
            
            $this->view->CustomerName = $Customer->getCustomerName();
            $this->view->CustomerCollage = $Customer->getCustomerCollage();
            $this->view->CustomerSelfMobile = $Customer->getCustomerSelfMobile();
            $this->view->CustomerAvatarMobile = $Customer->getCustomerAvatarMobile();
            $this->view->CustomerSelfAddress = $Customer->getCustomerSelfAddress();
            $this->view->CustomerAvatarAddress = $Customer->getCustomerAvatarAddress();
        }
    }

    public function packagetrackinfoAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
        } elseif ($this->_request->isGet()) {
            $FormData = $this->_request->getParams();
        }
        $TempTrackInfo = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
        
        $PackageDetailForm = new PiaoliuHK_Models_Form_PackageDetailFrontform();
        if ($PackageDetailForm->isValid($FormData)) {
            $TempTrackInfo = PiaoliuHK_Models_Logistics_PackageTrackInfoList::findTrackInfobyPackageID(
                    $PackageDetailForm->getValue('PackageID'));
        }
        if ($TempTrackInfo) {
            $this->view->TempTrackInfoData = $TempTrackInfo->getPackageTrackData();
            $this->view->PackageExpressCompanyName = $TempTrackInfo->getPackageExpressCompanyName();
            $this->view->PackageExpressTrackNumber = $TempTrackInfo->getPackageExpressTrackNumber();
            
            switch ($TempTrackInfo->getPackageTrackStatus()) {
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Error:
                    $this->view->TrackStatus = "查询出错";
                    $this->view->TrackStatus_Step = 4;
                    $this->view->TrackStatus_ThisStep = 0;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::NoRecord:
                    $this->view->TrackStatus = "暂无记录，待查";
                    $this->view->TrackStatus_Step = 4;
                    $this->view->TrackStatus_ThisStep = 0;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::OntheWay:
                    $this->view->TrackStatus = "在途中";
                    $this->view->TrackStatus_Step = 4;
                    $this->view->TrackStatus_ThisStep = 2;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Delivery:
                    $this->view->TrackStatus = "派送中";
                    $this->view->TrackStatus_Step = 4;
                    $this->view->TrackStatus_ThisStep = 3;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Signed:
                    $this->view->TrackStatus = "已签收";
                    $this->view->TrackStatus_Step = 4;
                    $this->view->TrackStatus_ThisStep = 4;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Reject:
                    $this->view->TrackStatus = "拒收";
                    $this->view->TrackStatus_Step = 5;
                    $this->view->TrackStatus_ThisStep = 5;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Difficult:
                    $this->view->TrackStatus = "疑难件";
                    $this->view->TrackStatus_Step = 5;
                    $this->view->TrackStatus_ThisStep = 5;
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Returned:
                    $this->view->TrackStatus = "退回";
                    $this->view->TrackStatus_Step = 5;
                    $this->view->TrackStatus_ThisStep = 5;
                    break;
                default:
                    $this->view->TrackStatus = "";
                    break;
            }
        } else {}
    }

    public function transitbilltrackinfoAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
        } elseif ($this->_request->isGet()) {
            $FormData = $this->_request->getParams();
        }
        $TempTransitBillTrackInfo = new PiaoliuHK_Models_Logistics_TransitBillTrackInfo();
        $TransitBillDetailForm = new PiaoliuHK_Models_Form_TransitBillDetailFrontform();
        if ($TransitBillDetailForm->isValid($FormData)) {
            $TempTrackInfo = PiaoliuHK_Models_Logistics_TransitBillTrackInfoList::findTrackInfobyTransitBillID(
                    $TransitBillDetailForm->getValue('TransitBillID'));
        }
        $this->view->TempTrackInfoData = $TempTrackInfo->getPackageTrackData();
        
        switch ($TempTrackInfo->getPackageTrackStatus()) {
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Error:
                $this->view->TrackStatus = "查询出错";
                $this->view->TrackStatus_Step = 4;
                $this->view->TrackStatus_ThisStep = 0;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::NoRecord:
                $this->view->TrackStatus = "暂无记录，待查";
                $this->view->TrackStatus_Step = 4;
                $this->view->TrackStatus_ThisStep = 0;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::OntheWay:
                $this->view->TrackStatus = "在途中";
                $this->view->TrackStatus_Step = 4;
                $this->view->TrackStatus_ThisStep = 2;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Delivery:
                $this->view->TrackStatus = "派送中";
                $this->view->TrackStatus_Step = 4;
                $this->view->TrackStatus_ThisStep = 3;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Signed:
                $this->view->TrackStatus = "已签收";
                $this->view->TrackStatus_Step = 4;
                $this->view->TrackStatus_ThisStep = 4;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Reject:
                $this->view->TrackStatus = "拒收";
                $this->view->TrackStatus_Step = 5;
                $this->view->TrackStatus_ThisStep = 5;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Difficult:
                $this->view->TrackStatus = "疑难件";
                $this->view->TrackStatus_Step = 5;
                $this->view->TrackStatus_ThisStep = 5;
                break;
            case PiaoliuHK_Configs_GlobalConstant_TrackStatus::Returned:
                $this->view->TrackStatus = "退回";
                $this->view->TrackStatus_Step = 5;
                $this->view->TrackStatus_ThisStep = 5;
                break;
            default:
                $this->view->TrackStatus = "";
                break;
        }
    }

    public function alltransitbilldataAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $TransitBillCheckoutList = PiaoliuHK_Models_Core_TransitBillList::findCustomerTransitBillbyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout);
            $TransitBillCheckoutTable = PiaoliuHK_Models_Core_TransitBillList::toTransitBillTableArray(
                    $TransitBillCheckoutList);
            
            $TransitBillinShipList = PiaoliuHK_Models_Core_TransitBillList::findCustomerTransitBillbyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip);
            $TransitBillinShipTable = PiaoliuHK_Models_Core_TransitBillList::toTransitBillTableArray(
                    $TransitBillinShipList);
            
            $TransitBillSignedList = PiaoliuHK_Models_Core_TransitBillList::findCustomerTransitBillbyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed);
            $TransitBillSignedTable = PiaoliuHK_Models_Core_TransitBillList::toTransitBillTableArray(
                    $TransitBillSignedList);
            
            $this->view->TransitBillTable = array_merge(
                    $TransitBillCheckoutTable, $TransitBillinShipTable, 
                    $TransitBillSignedTable);
        }
    }

    public function allpackagedataAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $PackageListWaitingArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting);
            $PackageWaitingList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListWaitingArray);
            
            $PackageListinTransitArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit);
            $PackageinTransitList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListinTransitArray);
            
            $PackageListCheckoutArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout);
            $PackageCheckoutList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListCheckoutArray);
            
            $PackageListSignedArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed);
            $PackageSignedList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListSignedArray);
            
            // $PackageListLostArray =
            // PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
            // $CustomerID,
            // PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost);
            $PackageListReservationArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
            $PackageReservationList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListReservationArray);
            
            $this->view->PackageList = array_merge($PackageReservationList, 
                    $PackageWaitingList, $PackageCheckoutList, 
                    $PackageinTransitList, $PackageSignedList);
        }
    }

    public function allcapcreditdataAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $CapCreditArray = PiaoliuHK_Models_Core_CapCreditList::findCapCreditTablebyID(
                    $CustomerID);
            
            $this->view->CapCreditTable = PiaoliuHK_Models_Core_CapCreditList::toCapCreditTableArray(
                    $CapCreditArray);
        }
    }
}
