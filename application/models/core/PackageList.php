<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBWaiting;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBTemporary;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBUnmatched;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBLost;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBReservation;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageStatus;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBSigned;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBinTransit;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageDBCheckout;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageChannel;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany;
require_once APPLICATION_PATH . '/models/core/Package.php';
require_once APPLICATION_PATH . '/models/core/Customer.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBSigned.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBinTransit.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBCheckout.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBWaiting.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBUnmatched.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBLost.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBReservation.php';
require_once APPLICATION_PATH . '/models/dbengine/PackageDBTemporary.php';

class PiaoliuHK_Models_Core_PackageList
{

    function __construct ()
    {
        $this->PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
    }

    public function getOwnerID ()
    {
        return $this->OwnerID;
    }

    public function setOwnerID ($ID)
    {
        $this->OwnerID = $ID;
    }

    public function getPackageArray ()
    {
        return $this->PackageArray;
    }

    public function setPackageArray ($Array)
    {
        $this->PackageArray = $Array;
    }

    public function addPackageArray (PiaoliuHK_Models_Core_Package $Package)
    {
        array_push($this->PackageArray, $Package);
    }

    public static function findAllPackagebyOwnerID ($OwnerID, $PackageStatus)
    {
        $PackageDBEngine = new \Zend_Db_Table();
        switch ($PackageStatus) {
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBSigned();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBinTransit();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBCheckout();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBLost();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
                break;
        }
        $DBAdapter = $PackageDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('PackageOwnerID = ?', $OwnerID);
        $Order = 'PackageOwnerID desc';
        $RowSet = $PackageDBEngine->fetchAll($Where, $Order);
        
        $PackageArray = array();
        foreach ($RowSet as $Value) {
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            if (isset($Value["PackageID"])) {
                $PackageTemp->setPackageID($Value["PackageID"]);
            }
            $PackageTemp->setPackageOwnerID($Value["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Value["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Value["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Value["PackageExpressTrackNumber"]);
            
            $PackageTemp->setPackageSnapshot($Value["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Value["PackageWeight"]);
            $PackageTemp->setPackageFare($Value["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Value["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Value["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Value["PackageStatus"]);
            $PackageTemp->setPackageChannel($Value["PackageChannel"]);
            $PackageTemp->setPackageRemarks($Value["PackageRemarks"]);
            
            array_push($PackageArray, $PackageTemp);
        }
        return $PackageArray;
    }

    public static function registerLostPackage (
            PiaoliuHK_Models_Core_Package $Package)
    {
        $SerialNumber = PiaoliuHK_Models_Core_Package::getPackageSerialNumber();
        $SerialNumber ++;
        
        $PackageDBLost = new PiaoliuHK_Models_DBEngine_PackageDBLost();
        $NewRow = $PackageDBLost->createRow();
        $NewRow->PackageID = 'Temp' . PiaoliuHK_Models_Core_Package::initializePackageSerialID(
                $SerialNumber);
        $NewRow->PackageOwnerID = $Package->getPackageOwnerID();
        $NewRow->PackageExpressCompany = $Package->getPackageExpressCompany();
        $NewRow->PackageExpressTrackNumber = $Package->getPackageExpressTrackNumber();
        $NewRow->PackageInTimeStamp = $Package->getPackageInTimeStamp();
        $NewRow->save();
        PiaoliuHK_Models_Core_Package::setPackageSerialNumber($SerialNumber);
    }

    public static function registerTempPackage (
            PiaoliuHK_Models_Core_Package $Package)
    {
        $PackageDBTemporaryEngine = new PiaoliuHK_Models_DBEngine_PackageDBTemporary();
        $NewRow = $PackageDBTemporaryEngine->createRow();
        
        $NewRow->PackageOwnerMobile = $Package->getPackageOwnerMobile();
        $NewRow->PackageExpressCompany = $Package->getPackageExpressCompany();
        $NewRow->PackageExpressTrackNumber = $Package->getPackageExpressTrackNumber();
        $NewRow->PackageSnapshot = $Package->getPackageSnapshot();
        $NewRow->PackageWeight = $Package->getPackageWeight();
        $NewRow->PackageFare = $Package->getPackageFare();
        $NewRow->PackageInTimeStamp = $Package->getPackageInTimeStamp();
        // $NewRow->PackageOutTime = $Package->getPackageOutTime();
        // $NewRow->PackageStatus = $Package->getPackageStatus();
        $NewRow->save();
    }

    public static function registerReservationPackage (
            PiaoliuHK_Models_Core_Package $Package)
    {
        $SerialNumber = PiaoliuHK_Models_Core_Package::getPackageSerialNumber();
        $SerialNumber ++;
        
        $PackageDBReservationEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
        $NewRow = $PackageDBReservationEngine->createRow();
        $NewRow->PackageID = 'Temp' . PiaoliuHK_Models_Core_Package::initializePackageSerialID(
                $SerialNumber);
        $NewRow->PackageOwnerID = $Package->getPackageOwnerID();
        $NewRow->PackageExpressCompany = $Package->getPackageExpressCompany();
        $NewRow->PackageExpressTrackNumber = $Package->getPackageExpressTrackNumber();
        // $NewRow->PackageSnapshot = $Package->getPackageSnapshot();
        // $NewRow->PackageWeight = $Package->getPackageWeight();
        // $NewRow->PackageFare = $Package->getPackageFare();
        // $NewRow->PackageInTimeStamp = $Package->getPackageInTimeStamp();
        // $NewRow->PackageOutTime = $Package->getPackageOutTime();
        $NewRow->PackageStatus = $Package->getPackageStatus();
        $NewRow->PackageChannel = $Package->getPackageChannel();
        $NewRow->PackageRemarks = $Package->getPackageRemarks();
        $NewRow->save();
        PiaoliuHK_Models_Core_Package::setPackageSerialNumber($SerialNumber);
        return $NewRow->PackageID;
    }

    public static function ismatchable (PiaoliuHK_Models_Core_Package $Package)
    {
        $ID = PiaoliuHK_Models_Core_Customer::findIDbySelfMobile(
                $Package->getPackageOwnerMobile());
        if ($ID) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function matchAllTempPackage ()
    {
        $PackageDBTemporaryEngine = new PiaoliuHK_Models_DBEngine_PackageDBTemporary();
        $RowSet = $PackageDBTemporaryEngine->fetchAll();
        
        $SerialNumber = PiaoliuHK_Models_Core_Package::getPackageSerialNumber();
        
        foreach ($RowSet as $Value) {
            $SerialNumber ++;
            $PackageOwnerID = PiaoliuHK_Models_Core_Customer::findIDbySelfMobile(
                    $Value["PackageOwnerMobile"]);
            if ($PackageOwnerID) {
                $PackageDBWaitingEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
                
                $NewRow = $PackageDBWaitingEngine->createRow();
                $NewRow->PackageID = PiaoliuHK_Models_Core_Package::initializePackageSerialID(
                        $SerialNumber);
                $NewRow->PackageOwnerID = $PackageOwnerID;
                $NewRow->PackageOwnerMobile = $Value["PackageOwnerMobile"];
                $NewRow->PackageExpressCompany = $Value["PackageExpressCompany"];
                $NewRow->PackageExpressTrackNumber = $Value["PackageExpressTrackNumber"];
                $NewRow->PackageSnapshot = $Value["PackageSnapshot"];
                $NewRow->PackageWeight = $Value["PackageWeight"];
                $NewRow->PackageFare = $Value["PackageFare"];
                $NewRow->PackageInTimeStamp = $Value["PackageInTimeStamp"];
                $NewRow->PackageOutTimeStamp = $Value["PackageOutTimeStamp"];
                $NewRow->PackageStatus = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting;
                $NewRow->save();
            } else {
                $PackageDBUnmatchedEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
                $NewRow = $PackageDBUnmatchedEngine->createRow();
                $NewRow->PackageID = PiaoliuHK_Models_Core_Package::initializePackageSerialID(
                        $SerialNumber);
                $NewRow->PackageOwnerMobile = $Value["PackageOwnerMobile"];
                $NewRow->PackageExpressCompany = $Value["PackageExpressCompany"];
                $NewRow->PackageExpressTrackNumber = $Value["PackageExpressTrackNumber"];
                $NewRow->PackageSnapshot = $Value["PackageSnapshot"];
                $NewRow->PackageWeight = $Value["PackageWeight"];
                $NewRow->PackageFare = $Value["PackageFare"];
                $NewRow->PackageInTimeStamp = $Value["PackageInTimeStamp"];
                $NewRow->PackageOutTimeStamp = $Value["PackageOutTimeStamp"];
                $NewRow->PackageStatus = PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched;
                $NewRow->save();
            }
        }
        
        PiaoliuHK_Models_Core_Package::setPackageSerialNumber($SerialNumber);
        $PackageDBTemporaryEngine->getAdapter()->query(
                'TRUNCATE TABLE ' . $PackageDBTemporaryEngine->info(
                        \Zend_Db_Table::NAME));
    }

    public static function updatePackageStatusbyPackageID ($PackageID, 
            $OriginStatus, $FinalStatus)
    {
        $PackageOriginEngine = new \Zend_Db_Table();
        switch ($OriginStatus) {
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBSigned();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBinTransit();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBCheckout();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBLost();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                $PackageOriginEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
                break;
        }
        
        $RowSet = $PackageOriginEngine->find($PackageID);
        
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $PackageFinalEngine = new \Zend_Db_Table();
            switch ($FinalStatus) {
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBSigned();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBinTransit();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBCheckout();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBLost();
                    break;
                case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                    $PackageFinalEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
                    break;
            }
        }
        
        $NewRow = $PackageFinalEngine->createRow();
        
        if ($OriginStatus >=
                 PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched && $FinalStatus <=
                 PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting) {
            $NewRow->PackageID = substr($Result->PackageID, 4);
        } else {
            $NewRow->PackageID = $Result->PackageID;
        }
        
        $NewRow->PackageOwnerID = $Result->PackageOwnerID;
        $NewRow->PackageOwnerMobile = $Result->PackageOwnerMobile;
        $NewRow->PackageExpressCompany = $Result->PackageExpressCompany;
        $NewRow->PackageExpressTrackNumber = $Result->PackageExpressTrackNumber;
        $NewRow->PackageSnapshot = $Result->PackageSnapshot;
        $NewRow->PackageWeight = $Result->PackageWeight;
        $NewRow->PackageFare = $Result->PackageFare;
        $NewRow->PackageInTimeStamp = $Result->PackageInTimeStamp;
        $NewRow->PackageOutTimeStamp = $Result->PackageOutTimeStamp;
        $NewRow->PackageStatus = $FinalStatus;
        $NewRow->save();
        
        $Where = $PackageOriginEngine->getAdapter()->quoteInto('PackageID = ?', 
                $PackageID);
        $PackageOriginEngine->delete($Where);
    }

    public static function findAllPackagebyPackageStatus ($PackageStatus)
    {
        $PackageDBEngine = new \Zend_Db_Table();
        switch ($PackageStatus) {
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBCheckout();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBinTransit();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBLost();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBSigned();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                $PackageDBEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
                break;
        }
        $DBAdapter = $PackageDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('PackageStatus = ?', $PackageStatus);
        $Order = 'PackageOwnerID desc';
        $RowSet = $PackageDBEngine->fetchAll($Where, $Order);
        
        $PackageArray = array();
        foreach ($RowSet as $Value) {
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($Value["PackageID"]);
            $PackageTemp->setPackageOwnerID($Value["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Value["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Value["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Value["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Value["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Value["PackageWeight"]);
            $PackageTemp->setPackageFare($Value["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Value["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Value["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Value["PackageStatus"]);
            
            array_push($PackageArray, $PackageTemp);
        }
        return $PackageArray;
    }

    public static function toPackageTableArray (Array $PackageListArray)
    {
        $PackageTableArray = array();
        foreach ($PackageListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_Package) {
                $PackageChannelChinese = "";
                switch ($Value->getPackageChannel()) {
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::Unknown:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::UnknownChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::TaoBaoWEB:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::TaoBaoWEBChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::JingDongWEB:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::JingDongWEBChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::DangdangWEB:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::DangdangWEBChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::AmazonWEB:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::AmazonWEBChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageChannel::VIPWEB:
                        $PackageChannel = PiaoliuHK_Configs_GlobalConstant_PackageChannel::VIPWEBChinese;
                        break;
                    default:
                        ;
                        break;
                }
                $PackageExpressCompanyChinese = "";
                switch ($Value->getPackageExpressCompany()) {
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::Unknown:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::UnknownChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShunfengExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShunfengExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YuantongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YuantongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZhongtongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZhongtongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YundaExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YundaExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::EMSExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::EMSExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuitongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuitongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::TiantianExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::TiantianExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YousuExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YousuExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::JingdongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::JingdongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::GuotongExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::GuotongExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::LongbangExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::LongbangExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::SuerExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::SuerExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuiqiangExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuiqiangExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZJSExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZJSExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::QuanfengExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::QuanfengExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::DangdangExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::DangdangExpressChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::AmazonExpress:
                        $PackageExpressCompanyChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::AmazonExpressChinese;
                        break;
                    default:
                        $PackageExpressCompanyChineseChinese = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::UnknownChinese;
                        break;
                }
                
                $PackageStatusChinese = "";
                switch ($Value->getPackageStatus()) {
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Signed:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::SignedChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransit:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::inTransitChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::CheckoutChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::WaitingChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Unmatched:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::UnmatchedChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Lost:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::LostChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PackageStatus::Reservation:
                        $PackageStatusChinese = PiaoliuHK_Configs_GlobalConstant_PackageStatus::ReservationChinese;
                        break;
                    default:
                        ;
                        break;
                }
                
                $PackageTable = [
                        "PackageID" => $Value->getPackageID(),
                        "PackageOwnerID" => $Value->getPackageOwnerID(),
                        "PackageOwnerMobile" => $Value->getPackageOwnerMobile(),
                        "PackageExpressCompany" => $PackageExpressCompanyChinese,
                        "PackageExpressTrackNumber" => $Value->getPackageExpressTrackNumber(),
                        "PackageSnapshot" => $Value->getPackageSnapshot(),
                        "PackageWeight" => $Value->getPackageWeight(),
                        "PackageFare" => $Value->getPackageFare(),
                        "PackageInTime" => $Value->getPackageInTime(),
                        "PackageOutTime" => $Value->getPackageOutTime(),
                        "PackageStatusChinese" => $PackageStatusChinese,
                        "PackageChannel" => $PackageChannel,
                        "PackageRemarks" => $Value->getPackageRemarks()
                ];
                array_push($PackageTableArray, $PackageTable);
            }
        }
        return $PackageTableArray;
    }

    public static function findPackagebyID ($PackageID)
    {
        $PackageTempArray = array();
        $PackageDBSignedEngine = new PiaoliuHK_Models_DBEngine_PackageDBSigned();
        $RowSetSigned = $PackageDBSignedEngine->find($PackageID);
        if ($RowSetSigned->count() == 1) {
            $Result = $RowSetSigned[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        
        $PackageDBinTransitEngine = new PiaoliuHK_Models_DBEngine_PackageDBinTransit();
        $RowSetinTransit = $PackageDBinTransitEngine->find($PackageID);
        if ($RowSetinTransit->count() == 1) {
            $Result = $RowSetinTransit[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        
        $PackageDBCheckoutEngine = new PiaoliuHK_Models_DBEngine_PackageDBCheckout();
        $RowSetCheckout = $PackageDBCheckoutEngine->find($PackageID);
        if ($RowSetCheckout->count() == 1) {
            $Result = $RowSetCheckout[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        
        $PackageDBWaitingEngine = new PiaoliuHK_Models_DBEngine_PackageDBWaiting();
        $RowSetWaiting = $PackageDBWaitingEngine->find($PackageID);
        if ($RowSetWaiting->count() == 1) {
            $Result = $RowSetWaiting[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        
        $PackageDBUnmatchedEngine = new PiaoliuHK_Models_DBEngine_PackageDBUnmatched();
        $RowSetUnmatched = $PackageDBUnmatchedEngine->find($PackageID);
        if ($RowSetUnmatched->count() == 1) {
            $Result = $RowSetUnmatched[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        $PackageDBLostEngine = new PiaoliuHK_Models_DBEngine_PackageDBLost();
        $RowSetLost = $PackageDBLostEngine->find($PackageID);
        if ($RowSetLost->count() == 1) {
            $Result = $RowSetLost[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        $PackageDBReservationEngine = new PiaoliuHK_Models_DBEngine_PackageDBReservation();
        $RowSetReservation = $PackageDBReservationEngine->find($PackageID);
        if ($RowSetReservation->count() == 1) {
            $Result = $RowSetReservation[0];
            $PackageTemp = new PiaoliuHK_Models_Core_Package();
            $PackageTemp->setPackageID($PackageID);
            $PackageTemp->setPackageOwnerID($Result["PackageOwnerID"]);
            $PackageTemp->setPackageOwnerMobile($Result["PackageOwnerMobile"]);
            $PackageTemp->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $PackageTemp->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $PackageTemp->setPackageSnapshot($Result["PackageSnapshot"]);
            $PackageTemp->setPackageWeight($Result["PackageWeight"]);
            $PackageTemp->setPackageFare($Result["PackageFare"]);
            $PackageTemp->setPackageInTimeStamp($Result["PackageInTimeStamp"]);
            $PackageTemp->setPackageOutTimeStamp($Result["PackageOutTimeStamp"]);
            $PackageTemp->setPackageStatus($Result["PackageStatus"]);
            array_push($PackageTempArray, $PackageTemp);
        }
        return isset($PackageTempArray[0]) ? $PackageTempArray[0] : NULL;
        // return $PackageTempArray;
    }
}

?>