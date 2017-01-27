<?php
use models\core\PiaoliuHK_Models_Core_Package;
use models\core\PiaoliuHK_Models_Core_PackageList;
use models\core\PiaoliuHK_Models_Core_TransitBillList;
use models\core\PiaoliuHK_Models_Core_Customer;
use models\core\PiaoliuHK_Models_Core_TransitBill;
use models\form\PiaoliuHK_Models_Form_goShipForm;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TransitBillStatus;
use models\form\PiaoliuHK_Models_Form_PreferenceForm;
use models\core\PiaoliuHK_Models_Core_User;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus;
use models\form\PiaoliuHK_Models_Form_MyStorehouseForm;
use models\form\PiaoliuHK_Models_Form_AllTransitBillForm;
use models\form\PiaoliuHK_Models_Form_findLostPackageForm;
use models\form\PiaoliuHK_Models_Form_reservePackageForm;
use models\core\PiaoliuHK_Models_Core_CapCredit;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageChannel;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany;
use models\logistics\PiaoliuHK_Models_Logistics_TrackInfoList;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_MaxSnapNum;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_SelfPickAddress;
use models\logistics\PiaoliuHK_Models_Logistics_PackageTrackInfoList;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus;
/**
 * WorkspaceController
 *
 * @author *
 *        
 * @version *
 *         
 */
require_once 'Zend/Controller/Action.php';
require_once APPLICATION_PATH . '/models/core/User.php';
require_once APPLICATION_PATH . '/models/core/PackageList.php';
require_once APPLICATION_PATH . '/models/core/TransitBillList.php';
require_once APPLICATION_PATH . '/models/core/CapCredit.php';
require_once APPLICATION_PATH . '/models/form/findLostPackageForm.php';
require_once APPLICATION_PATH . '/models/form/goShipForm.php';
require_once APPLICATION_PATH . '/models/form/PreferenceForm.php';
require_once APPLICATION_PATH . '/models/form/MyStorehouseForm.php';
require_once APPLICATION_PATH . '/models/form/AllTransitBillForm.php';
require_once APPLICATION_PATH . '/models/form/reservePackageForm.php';
require_once APPLICATION_PATH . '/models/logistics/PackageTrackInfoList.php';

class WorkspaceController extends Zend_Controller_Action
{
    
    // function WorkspaceController (){ }
    function init ()
    {
        $this->initView();
    }

    /**
     * The default action - show the home page
     */
    public function indexAction () // TODO Auto-generated
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
            $this->snappackagetrackinfo();
        } else {
            $this->view->starNews = '请先登录，游客，这里是控制面板';
        }
    }

    private function snappackagetrackinfo ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $PackageListReservationArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
            $PackageTrackInfoArray = array();
            
            for ($i = 0; $i < min(
                    [
                            count($PackageListReservationArray),
                            PiaoliuHK_Configs_GlobalConstant_MaxSnapNum::PiaoliuHK_Configs_GlobalConstant_PackageInfo
                    ]); $i ++) {
                $TempPackage = $PackageListReservationArray[$i];
                if ($TempPackage instanceof PiaoliuHK_Models_Core_Package) {
                    $TempTrackInfo = PiaoliuHK_Models_Logistics_PackageTrackInfoList::findTrackInfobyPackageID(
                            $TempPackage->getPackageID());
                    $reverseTempPackageTrackData = array_reverse(
                            $TempTrackInfo->getPackageTrackData());
                    array_splice($reverseTempPackageTrackData, 
                            PiaoliuHK_Configs_GlobalConstant_MaxSnapNum::PiaoliuHK_Configs_GlobalConstant_PackageInfoRow);
                    
                    array_push($PackageTrackInfoArray, 
                            array(
                                    'PackageExpressCompanyName' => $TempTrackInfo->getPackageExpressCompanyName(),
                                    'PackageExpressTrackNumber' => $TempTrackInfo->getPackageExpressTrackNumber(),
                                    'PackageTrackData' => $reverseTempPackageTrackData
                            ));
                }
            }
            
            $this->view->PackageTrackInfoArray = $PackageTrackInfoArray;
        }
    }

    public function goshipAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            
            if ($Customer->getCustomerAccountStatus() !=
                     PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus::Initialization) {
                $PackageListWaitingArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                        $CustomerID, 
                        PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting);
                
                $PackageWaitingList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                        $PackageListWaitingArray);
                
                $PackageListReservationArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                        $CustomerID, 
                        PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
                
                $PackageReservationList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                        $PackageListReservationArray);
                
                $this->view->PackageWaitingList = $PackageWaitingList;
                $this->view->PackageReservationList = $PackageReservationList;
                
                $TransitBillRelatedPackageIDArray = array();
                
                foreach (array_merge($PackageWaitingList, 
                        $PackageReservationList) as $Value) {
                    $TransitBillRelatedPackageIDArray[$Value["PackageID"]] = TRUE;
                }
                $CustomerSession = new Zend_Session_Namespace('CustomerSession');
                $CustomerSession->TransitBillRelatedPackageIDArray = $TransitBillRelatedPackageIDArray;
                
                $SelfPickAddressList = array(
                        PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::ChineseUniversityofHongKong => PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::ChineseUniversityofHongKongAddress,
                        PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::CityUniversityofHongKong => PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::CityUniversityofHongKongAddress,
                        PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::HongKongBaptistUniversity => PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::HongKongBaptistUniversityAddress,
                        PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::HongKongPolytechnicUniversity => PiaoliuHK_Configs_GlobalConstant_SelfPickAddress::HongKongPolytechnicUniversityAddress
                );
                $this->view->SelfPickAddressList = $SelfPickAddressList;
            } else {
                $this->_redirect('/workspace/preference');
            }
        }
    }

    public function goshipformAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $TransitBillRelatedPackageIDArray = $CustomerSession->TransitBillRelatedPackageIDArray;
            if ($this->_request->isPost()) {
                $FormData = $this->_request->getPost();
                $goShipForm = new PiaoliuHK_Models_Form_goShipForm();
                $goShipForm->setRelatedPackageID(
                        $TransitBillRelatedPackageIDArray);
                if ($goShipForm->isValid($FormData)) {
                    $TransitBill = new PiaoliuHK_Models_Core_TransitBill();
                    $TransitBill->setTransitBillOwnerID($CustomerID);
                    $TransitBill->setTransitBillRelatedPackageIDArray(
                            $goShipForm->getValue('RelatedPackageID'));
                    $TransitBill->setTransitBillRelatedPackageQuantity(
                            count($goShipForm->getValue('RelatedPackageID')));
                    $TransitBill->setTransitBillAddress(
                            $goShipForm->getValue('TransitBillAddrrss'));
                    // $TransitBill->setTransitBillPrice(
                    // $goShipForm->getValue('TransitBillPrice'));
                    $TransitBill->setTransitBillMethod(
                            $goShipForm->getValue('TransitBillMethod'));
                    $TransitBill->setTransitBillSettlement(
                            $goShipForm->getValue('TransitBillSettlement'));
                    $TransitBill->setTransitBillInitializationTime(
                            Zend_Date::now()->get('YYYY-MM-dd HH:mm:ss'));
                    $TransitBill->setTransitBillSignDate(
                            $goShipForm->getValue('TransitBillSignDate'));
                    $TransitBill->setTransitBillStatus(
                            PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout);
                    print_r($TransitBill);
                    
                    /*
                     * $ReturnTransitBillID =
                     * PiaoliuHK_Models_Core_TransitBillList::declarTransitBill(
                     * $TransitBill);
                     */
                } else {
                    print_r("no");
                    
                    $this->view->result = "no";
                }
            } elseif ($this->_request->isGet()) {
                echo '非法访问';
            } else {}
        }
    }

    public function myaddressAction ()
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

    public function mystorehouseAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
        }
    }

    public function mystorehouseformAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        $PackageIDArray = $CustomerSession->PackageIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $MyStorehouseForm = new PiaoliuHK_Models_Form_MyStorehouseForm();
            
            $MyStorehouseForm->setPackageIDArray($PackageIDArray);
            if ($MyStorehouseForm->isValid($FormData)) {
                $this->_redirect(
                        '/help/packagedetail/PackageID/' .
                                 $MyStorehouseForm->getValue('PackageIDR'));
            }
        }
    }

    public function trackuncheckedpackageAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $PackageListWaitingArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting);
            
            $PackageWaitingList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListWaitingArray);
            
            $PackageListReservationArray = PiaoliuHK_Models_Core_PackageList::findAllPackagebyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
            
            $PackageReservationList = PiaoliuHK_Models_Core_PackageList::toPackageTableArray(
                    $PackageListReservationArray);
            $PackageReservationTrackStatusArrayFlag = array();
            
            foreach ($PackageListReservationArray as $Value) {
                if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                    $ID = $Value->getPackageID();
                    switch (PiaoliuHK_Models_Logistics_PackageTrackInfoList::findTrackInfobyPackageID(
                            $ID)->getPackageTrackStatus()) {
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Error:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-gray";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::NoRecord:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-gray";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::OntheWay:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-yellow";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Delivery:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-yellow";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Signed:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-green";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Reject:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-red";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Difficult:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-red";
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_PackageTrackStatus::Returned:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-red";
                            break;
                        default:
                            $PackageReservationTrackStatusArrayFlag[$ID] = "fg-gray";
                            break;
                    }
                }
            }
            $this->view->PackageWaitingList = $PackageWaitingList;
            $this->view->PackageReservationList = $PackageReservationList;
            $this->view->PackageReservationTrackStatusArrayFlag = $PackageReservationTrackStatusArrayFlag;
        }
    }

    public function trackuncheckedtransitbillAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            
            $TransitBillCheckoutList = PiaoliuHK_Models_Core_TransitBillList::findCustomerTransitBillbyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout);
            $TransitBillCheckoutTable = PiaoliuHK_Models_Core_TransitBillList::toTransitTableArray(
                    $TransitBillCheckoutList);
            
            $TransitBillinShipList = PiaoliuHK_Models_Core_TransitBillList::findCustomerTransitBillbyOwnerID(
                    $CustomerID, 
                    PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip);
            $TransitBillinShipTable = PiaoliuHK_Models_Core_TransitBillList::toTransitTableArray(
                    $TransitBillinShipList);
            
            $this->view->TransitBillCheckoutTable = $TransitBillCheckoutTable;
            $this->view->TransitBillinShipTable = $TransitBillinShipTable;
        }
    }

    public function alltransitbillformAction () // not use
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        $TransitBillIDArray = $CustomerSession->TransitBillIDArray;
        
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $AllTransitBillForm = new PiaoliuHK_Models_Form_AllTransitBillForm();
            
            $AllTransitBillForm->setTransitBillIDArray($TransitBillIDArray);
            if ($AllTransitBillForm->isValid($FormData)) {
                $this->_redirect(
                        '/help/transitbilldetail/TransitBillID/' . $AllTransitBillForm->getValue(
                                'TransitBillIDR'));
            }
        }
    }

    public function findlostpackageAction ()
    {}

    public function findlostpackageformAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            if ($this->_request->isPost()) {
                $FormData = $this->_request->getPost();
                $FindpackageForm = new PiaoliuHK_Models_Form_findLostPackageForm();
                if ($FindpackageForm->isValid($FormData)) {
                    $Findpackage = new PiaoliuHK_Models_Core_Package();
                    $Findpackage->setPackageOwnerID($CustomerID);
                    $Findpackage->setPackageExpressCompany(
                            $FindpackageForm->getValue('ExpressCompany'));
                    $Findpackage->setPackageExpressTrackNumber(
                            $FindpackageForm->getValue(
                                    'PackageExpressTrackNumber'));
                    $Findpackage->setPackageInTime(
                            $FindpackageForm->getValue('ArriveTime'));
                    /*
                     * $form->getValue('Channel');
                     * $form->getValue('PackageValue'); $this->_redirect('/');
                     */
                    PiaoliuHK_Models_Core_PackageList::registerLostPackage(
                            $Findpackage);
                } else {
                    $this->view->result = "no";
                }
            } elseif ($this->_request->isGet()) {
                echo '非法访问';
            } else {}
        }
    }

    public function reservepackageAction ()
    {
        $this->view->PackageExpressCompanyUnknown = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::Unknown;
        $this->view->ShunfengExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShunfengExpress;
        $this->view->YuantongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YuantongExpress;
        $this->view->ZhongtongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZhongtongExpress;
        $this->view->YundaExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YundaExpress;
        $this->view->ShentongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpress;
        $this->view->EMSExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::EMSExpress;
        $this->view->HuitongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuitongExpress;
        $this->view->TiantianExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::TiantianExpress;
        $this->view->YousuExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YousuExpress;
        $this->view->JingdongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::JingdongExpress;
        $this->view->GuotongExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::GuotongExpress;
        $this->view->LongbangExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::LongbangExpress;
        $this->view->SuerExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::SuerExpress;
        $this->view->HuiqiangExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuiqiangExpress;
        $this->view->ZJSExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZJSExpress;
        $this->view->QuanfengExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::QuanfengExpress;
        $this->view->DangdangExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::DangdangExpress;
        $this->view->AmazonExpress = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::AmazonExpress;
        
        $this->view->PackageChannelUnknown = PiaoliuHK_Configs_GlobalConstant_PackageChannel::Unknown;
        $this->view->TaoBaoWEB = PiaoliuHK_Configs_GlobalConstant_PackageChannel::TaoBaoWEB;
        $this->view->JingDongWEB = PiaoliuHK_Configs_GlobalConstant_PackageChannel::JingDongWEB;
        $this->view->DangdangWEB = PiaoliuHK_Configs_GlobalConstant_PackageChannel::DangdangWEB;
        $this->view->AmazonWEB = PiaoliuHK_Configs_GlobalConstant_PackageChannel::AmazonWEB;
        $this->view->VIPWEB = PiaoliuHK_Configs_GlobalConstant_PackageChannel::VIPWEB;
    }

    public function reservepackageformAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            if ($this->_request->isPost()) {
                $FormData = $this->_request->getPost();
                $reservePackageForm = new PiaoliuHK_Models_Form_reservePackageForm();
                if ($reservePackageForm->isValid($FormData)) {
                    $TempPackage = new PiaoliuHK_Models_Core_Package();
                    $TempPackage->setPackageOwnerID($CustomerID);
                    
                    $TempPackage->setPackageStatus(
                            PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation);
                    
                    $TempPackage->setPackageExpressCompany(
                            $reservePackageForm->getValue(
                                    'PackageExpressCompany'));
                    $TempPackage->setPackageExpressTrackNumber(
                            $reservePackageForm->getValue(
                                    'PackageExpressTrackNumber'));
                    $TempPackage->setPackageChannel(
                            $reservePackageForm->getValue('PackageChannel'));
                    $TempPackage->setPackageRemarks(
                            $reservePackageForm->getValue('Remarks'));
                    /*
                     * if ($reservePackageForm->getElement(
                     * 'PackageExpressTrackSwitch')->isChecked()) { echo
                     * $reservePackageForm->getValue(
                     * 'PackageExpressTrackSwitch'); }
                     */
                    
                    $TempPackageID = PiaoliuHK_Models_Core_PackageList::registerReservationPackage(
                            $TempPackage);
                    $TempPackage->setPackageID($TempPackageID);
                    PiaoliuHK_Models_Logistics_PackageTrackInfoList::registerPackageTrackInfo(
                            $TempPackage);
                } else {
                    $this->view->result = "no";
                }
            } elseif ($this->_request->isGet()) {
                echo '非法访问';
            } else {}
        }
    }

    public function preferenceAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            $Customer = PiaoliuHK_Models_Core_Customer::initializebyID(
                    $CustomerID);
            
            $this->view->CustomerName = $Customer->getCustomerName();
            $this->view->CustomerCollage = $Customer->getCustomerCollage();
            $this->view->CustomerSelfMobile = $Customer->getCustomerSelfMobile();
            $this->view->CustomerSelfAddress = $Customer->getCustomerSelfAddress();
            $this->view->CustomerMail = $Customer->getCustomerMail();
            $this->view->CustomerQQ = $Customer->getCustomerQQ();
            $this->view->CustomerRenren = $Customer->getCustomerRenren();
            $this->view->CustomerWeixin = $Customer->getCustomerWeixin();
            $this->view->CustomerAlipay = $Customer->getCustomerAlipay();
        }
    }

    public function preferenceformAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
            $CustomerID = $CustomerSession->CustomerID;
            if ($this->_request->isPost()) {
                $FormData = $this->_request->getPost();
                $PreferenceForm = new PiaoliuHK_Models_Form_PreferenceForm();
                if ($PreferenceForm->isValid($FormData)) {
                    $CustomerTemp = new PiaoliuHK_Models_Core_Customer();
                    $ElementsArray = array();
                    if ($PreferenceForm->getElement('CustomerNameCB')->isChecked()) {
                        $CustomerTemp->setCustomerName(
                                $PreferenceForm->getValue('CustomerName'));
                        array_push($ElementsArray, 'CustomerName');
                    }
                    if ($PreferenceForm->getElement('CustomerCollageCB')->isChecked()) {
                        $CustomerTemp->setCustomerCollage(
                                $PreferenceForm->getValue('CustomerCollage'));
                        array_push($ElementsArray, 'CustomerCollage');
                    }
                    if ($PreferenceForm->getElement('CustomerSelfMobileCB')->isChecked()) {
                        $CustomerTemp->setCustomerSelfMobile(
                                $PreferenceForm->getValue('CustomerSelfMobile'));
                        array_push($ElementsArray, 'CustomerSelfMobile');
                    }
                    if ($PreferenceForm->getElement('CustomerSelfAddressCB')->isChecked()) {
                        $CustomerSelfAddress = [
                                "Province" => $PreferenceForm->getValue(
                                        'CustomerSelfAddress_Province'),
                                "City" => $PreferenceForm->getValue(
                                        'CustomerSelfAddress_City'),
                                "District" => $PreferenceForm->getValue(
                                        'CustomerSelfAddress_District'),
                                "Apartment" => $PreferenceForm->getValue(
                                        'CustomerSelfAddress_Apartment')
                        ];
                        $CustomerTemp->setCustomerSelfAddress(
                                $CustomerSelfAddress);
                        array_push($ElementsArray, 'CustomerSelfAddress');
                    }
                    if ($PreferenceForm->getElement('CustomerMailCB')->isChecked()) {
                        $CustomerTemp->setCustomerMail(
                                $PreferenceForm->getValue('CustomerMail'));
                        array_push($ElementsArray, 'CustomerMail');
                    }
                    if (PiaoliuHK_Models_Core_User::AuthUserbyPassword(
                            $CustomerID, 
                            md5($PreferenceForm->getValue('Password'), FALSE)) ==
                             1) {
                        if (! empty($ElementsArray)) {
                            $CustomerTemp->setCustomerID($CustomerID);
                            PiaoliuHK_Models_Core_Customer::updateCustomerElementstoDB(
                                    $CustomerTemp, $ElementsArray);
                            PiaoliuHK_Models_Core_Customer::updateCustomerAccountStatus(
                                    $CustomerID);
                        }
                        if ($PreferenceForm->getElement('NewPasswordCB')->isChecked()) {
                            PiaoliuHK_Models_Core_User::updateNewPassword(
                                    $CustomerID, 
                                    md5(
                                            $PreferenceForm->getValue(
                                                    'NewPassword'), FALSE));
                        }
                    }
                } else {
                    $this->view->result = "no";
                }
            } elseif ($this->_request->isGet()) {
                echo '非法访问';
            } else {}
        }
    }

    public function capcreditAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        if (isset($CustomerSession->CustomerID)) {
        	$CustomerID = $CustomerSession->CustomerID;
        }        
    }

    public function guideAction ()
    {
        // $this->view->result = Aikuaidi::trackExpressInfo("yunda",
        // "1900851189546");
        // PiaoliuHK_Models_Logistics_TrackInfoList::trackAllBlankPackageInfo();
        PiaoliuHK_Models_Logistics_TrackInfoList::trackPartBlankPackageInfo();
    }
}