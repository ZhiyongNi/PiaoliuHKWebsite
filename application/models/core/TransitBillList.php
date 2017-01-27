<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_TransitBillDBinShip;
use models\dbengine\PiaoliuHK_Models_DBEngine_TransitBillDBCheckout;
use models\dbengine\PiaoliuHK_Models_DBEngine_TransitBillDBSigned;
use models\dbengine\PiaoliuHK_Models_DBEngine_TransitBillDBVerificationCode;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TransitBillStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageStatus;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_TransitBillMethod;
require_once APPLICATION_PATH . '/models/core/TransitBill.php';
require_once APPLICATION_PATH . '/models/dbengine/TransitBillDBSigned.php';
require_once APPLICATION_PATH . '/models/dbengine/TransitBillDBinShip.php';
require_once APPLICATION_PATH . '/models/dbengine/TransitBillDBCheckout.php';
require_once APPLICATION_PATH . '/models/dbengine/TransitBillDBVerificationCode.php';

class PiaoliuHK_Models_Core_TransitBillList
{

    private $OwnerID;

    private $TransitBillEngine;

    private $TransitBillArray = array();

    function __construct ()
    {
        $this->TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
    }

    public function getOwnerID ()
    {
        return $this->OwnerID;
    }

    public function setOwnerID ($ID)
    {
        $this->OwnerID = $ID;
    }

    public function getTransitBillArray ()
    {
        return $this->TransitBillArray;
    }

    public function setTransitBillArray ($Array)
    {
        $this->TransitBillArray = $Array;
    }

    public function addTransitBillArray (
            PiaoliuHK_Models_Core_TransitBill $TransitBill)
    {
        array_push($this->TransitBillArray, $TransitBill);
    }

    public static function findAllTransitBillbyTransitBillStatus (
            $TransitBillStatus)
    {
        $TransitBillEngine = new \Zend_Db_Table();
        switch ($TransitBillStatus) {
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBSigned();
                break;
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
                break;
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
                break;
        }
        
        $DBAdapter = $TransitBillEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('TransitBillStatus = ?', 
                $TransitBillStatus);
        $Order = 'TransitBillOwnerID desc';
        $RowSet = $TransitBillEngine->fetchAll($Where, $Order);
        
        $TransitBillArray = array();
        foreach ($RowSet as $Value) {
            $TransitBillTemp = new PiaoliuHK_Models_Core_TransitBill();
            $TransitBillTemp->setTransitBillID($Value["TransitBillID"]);
            $TransitBillTemp->setTransitBillOwnerID(
                    $Value["TransitBillOwnerID"]);
            $TransitBillTemp->setTransitBillRelatedPackageIDArray(
                    unserialize($Value["TransitBillRelatedPackageIDArray"]));
            $TransitBillTemp->setTransitBillRelatedPackageQuantity(
                    $Value["TransitBillRelatedPackageQuantity"]);
            $TransitBillTemp->setTransitBillPrice($Value["TransitBillPrice"]);
            $TransitBillTemp->setTransitBillMethod($Value["TransitBillMethod"]);
            $TransitBillTemp->setTransitBillSettlement(
                    $Value["TransitBillSettlement"]);
            $TransitBillTemp->setTransitBillInitializationTimeStamp(
                    $Value["TransitBillInitializationTimeStamp"]);
            $TransitBillTemp->setTransitBillSignDate(
                    $Value["TransitBillSignDate"]);
            $TransitBillTemp->setTransitBillStatus($Value["TransitBillStatus"]);
            array_push($TransitBillArray, $TransitBillTemp);
        }
        return $TransitBillArray;
    }

    public static function declarTransitBill (
            PiaoliuHK_Models_Core_TransitBill $TransitBill)
    {
        $SerialNumber = PiaoliuHK_Models_Core_TransitBill::getTransitBillSerialNumber();
        $SerialNumber ++;
        
        $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
        $NewRow = $TransitBillEngine->createRow();
        $NewRow->TransitBillID = PiaoliuHK_Models_Core_TransitBill::initializeTransitBillSerialID(
                $SerialNumber);
        $NewRow->TransitBillOwnerID = $TransitBill->getTransitBillOwnerID();
        
        $NewRow->TransitBillRelatedPackageIDArray = serialize(
                $TransitBill->getTransitBillRelatedPackageIDArray());
        $NewRow->TransitBillRelatedPackageQuantity = $TransitBill->getTransitBillRelatedPackageQuantity();
        $NewRow->TransitBillPrice = $TransitBill->getTransitBillPrice();
        $NewRow->TransitBillMethod = $TransitBill->getTransitBillMethod();
        $NewRow->TransitBillSettlement = $TransitBill->getTransitBillSettlement();
        $NewRow->TransitBillInitializationTimeStamp = $TransitBill->getTransitBillInitializationTimeStamp();
        $NewRow->TransitBillSignDate = $TransitBill->getTransitBillSignDate();
        $NewRow->TransitBillStatus = $TransitBill->getTransitBillStatus();
        
        $TransitBillID = $NewRow->save();
        
        PiaoliuHK_Models_Core_TransitBill::setTransitBillSerialNumber(
                $SerialNumber);
        $TransitBillRelatedPackageIDArray = $TransitBill->getTransitBillRelatedPackageIDArray();
        foreach ($TransitBillRelatedPackageIDArray as $Value) {
            PiaoliuHK_Models_Core_PackageList::updatePackageStatusbyPackageID(
                    $Value, PiaoliuHK_Configs_GlobalConstant_PackageStatus::Waiting, 
                    PiaoliuHK_Configs_GlobalConstant_PackageStatus::Checkout);
        }
        return $TransitBillID;
    }

    public static function updateTransitBillStatusbyID ($TransitBillID, 
            $OriginStatus, $FinalStatus)
    {
        $TransitBillOriginEngine = new \Zend_Db_Table();
        switch ($OriginStatus) {
            case 0:
                $TransitBillOriginEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBSigned();
                break;
            case 1:
                $TransitBillOriginEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
                break;
            case 2:
                $TransitBillOriginEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
                break;
        }
        
        $RowSet = $TransitBillOriginEngine->find($TransitBillID);
        
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $TransitBillFinalEngine = new \Zend_Db_Table();
            switch ($FinalStatus) {
                case 0:
                    $TransitBillFinalEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBSigned();
                    break;
                case 1:
                    $TransitBillFinalEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
                    break;
                case 2:
                    $TransitBillFinalEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
                    break;
            }
            
            $NewRow = $TransitBillFinalEngine->createRow();
            $NewRow->TransitBillID = $Result->TransitBillID;
            $NewRow->TransitBillOwnerID = $Result->TransitBillOwnerID;
            $NewRow->TransitBillRelatedPackageIDArray = $Result->TransitBillRelatedPackageIDArray;
            $NewRow->TransitBillRelatedPackageQuantity = $Result->TransitBillRelatedPackageQuantity;
            $NewRow->TransitBillPrice = $Result->TransitBillPrice;
            $NewRow->TransitBillMethod = $Result->TransitBillMethod;
            $NewRow->TransitBillSettlement = $Result->TransitBillSettlement;
            $NewRow->TransitBillInitializationTimeStamp = $Result->TransitBillInitializationTimeStamp;
            $NewRow->TransitBillSignDate = $Result->TransitBillSignDate;
            $NewRow->TransitBillStatus = $FinalStatus;
            
            $NewRow->save();
            $Where = $TransitBillOriginEngine->getAdapter()->quoteInto(
                    'TransitBillID = ?', $TransitBillID);
            $TransitBillOriginEngine->delete($Where);
        }
    }

    public static function produceSignature ($TransitBillID)
    {
        srand((double) microtime() * 1000000); // create a random number feed.
        $RandomList = array(
                "0",
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
                "A",
                "B",
                "C",
                "D",
                "E",
                "F",
                "G",
                "H",
                "I",
                "J",
                "K",
                "L",
                "M",
                "N",
                "O",
                "P",
                "Q",
                "R",
                "S",
                "T",
                "U",
                "V",
                "W",
                "X",
                "Y",
                "Z"
        );
        $VerificationCode = "";
        for ($i = 0; $i < 6; $i ++) {
            $Random = rand(0, 35);
            $VerificationCode .= $RandomList[$Random];
        }
        
        $TransitBillDBVerificationCode = new PiaoliuHK_Models_DBEngine_TransitBillDBVerificationCode();
        $NewRow = $TransitBillDBVerificationCode->createRow();
        $NewRow->TransitBillID = $TransitBillID;
        $NewRow->VerificationCode = $VerificationCode;
        $NewRow->save();
    }

    public static function verifySignature ($TransitID, $VerificationCode)
    {
        $TransitBillDBVerificationCode = new PiaoliuHK_Models_DBEngine_TransitBillDBVerificationCode();
        $DBAdapter = $TransitBillDBVerificationCode->getAdapter();
        $Where = $DBAdapter->quoteInto('TransitBillID = ?', $TransitID);
        $Order = 'TransitBillID desc';
        $RowSet = $TransitBillDBVerificationCode->fetchRow($Where, $Order);
        
        if ($RowSet) {
            if (strcmp($VerificationCode, $RowSet["VerificationCode"]) == 0) {
                self::updateTransitBillStatusbyID($TransitID, 
                        PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip, 
                        PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed);
                return PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::Right;
            } else {
                return PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::Wrong;
            }
        } else {
            return PiaoliuHK_Configs_GlobalConstant_SignatureVerifyResult::None;
        }
    }

    public static function findCustomerTransitBillbyOwnerID ($OwnerID, 
            $TransitBillStatus)
    {
        $TransitBillEngine = new \Zend_Db_Table();
        switch ($TransitBillStatus) {
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBSigned();
                break;
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
                break;
            case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout:
                $TransitBillEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
                break;
        }
        
        $DBAdapter = $TransitBillEngine->getAdapter();
        $Where[0] = $DBAdapter->quoteInto('TransitBillOwnerID = ?', $OwnerID);
        $Where[1] = $DBAdapter->quoteInto('TransitBillStatus = ?', 
                $TransitBillStatus);
        $Order = 'TransitBillOwnerID desc';
        $RowSet = $TransitBillEngine->fetchAll($Where, $Order);
        
        $TransitBillArray = array();
        foreach ($RowSet as $Value) {
            $TransitBillTemp = new PiaoliuHK_Models_Core_TransitBill();
            $TransitBillTemp->setTransitBillID($Value["TransitBillID"]);
            $TransitBillTemp->setTransitBillOwnerID(
                    $Value["TransitBillOwnerID"]);
            $TransitBillTemp->setTransitBillRelatedPackageIDArray(
                    unserialize($Value["TransitBillRelatedPackageIDArray"]));
            $TransitBillTemp->setTransitBillRelatedPackageQuantity(
                    $Value["TransitBillRelatedPackageQuantity"]);
            $TransitBillTemp->setTransitBillPrice($Value["TransitBillPrice"]);
            $TransitBillTemp->setTransitBillMethod($Value["TransitBillMethod"]);
            $TransitBillTemp->setTransitBillSettlement(
                    $Value["TransitBillSettlement"]);
            $TransitBillTemp->setTransitBillInitializationTimeStamp(
                    $Value["TransitBillInitializationTimeStamp"]);
            $TransitBillTemp->setTransitBillSignDate(
                    $Value["TransitBillSignDate"]);
            $TransitBillTemp->setTransitBillStatus($Value["TransitBillStatus"]);
            
            array_push($TransitBillArray, $TransitBillTemp);
        }
        return $TransitBillArray;
    }

    public static function toTransitBillTableArray (Array $PackageListArray)
    {
        $TransitBillTableArray = array();
        foreach ($PackageListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_TransitBill) {
                $TransitBillMethod = "";
                switch ($Value->getTransitBillMethod()) {
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::ExpressPick:
                        $TransitBillMethod = PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::ExpressShortChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::HousePick:
                        $TransitBillMethod = PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::HousePickShortChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::SelfPick:
                        $TransitBillMethod = PiaoliuHK_Configs_GlobalConstant_TransitBillMethod::SelfPickShortChinese;
                        break;
                    default:
                        ;
                        break;
                }
                $TransitBillStatus = "";
                switch ($Value->getTransitBillStatus()) {
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Signed:
                        $TransitBillStatus = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::SignedChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShip:
                        $TransitBillStatus = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::inShipChinese;
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::Checkout:
                        $TransitBillStatus = PiaoliuHK_Configs_GlobalConstant_TransitBillStatus::CheckoutChinese;
                        break;
                    default:
                        ;
                        break;
                }
                
                $TransitBillTable = [
                        "TransitBillID" => $Value->getTransitBillID(),
                        "TransitBillOwnerID" => $Value->getTransitBillOwnerID(),
                        "TransitBillRelatedPackageIDArray" => implode(";", 
                                $Value->getTransitBillRelatedPackageIDArray()),
                        "TransitBillRelatedPackageQuantity" => $Value->getTransitBillRelatedPackageQuantity(),
                        "TransitBillPrice" => $Value->getTransitBillPrice(),
                        "TransitBillMethod" => $TransitBillMethod,
                        "TransitBillSettlement" => $Value->getTransitBillSettlement(),
                        "TransitBillInitializationTime" => $Value->getTransitBillInitializationTime(),
                        "TransitBillSignDate" => $Value->getTransitBillSignDate(),
                        "TransitBillStatus" => $TransitBillStatus
                ];
                array_push($TransitBillTableArray, $TransitBillTable);
            }
        }
        return $TransitBillTableArray;
    }

    public static function findTransitBillbyID ($TransitID)
    {
        $TransitTempArray = array();
        $TransitBillSignedEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBSigned();
        $RowSetSigned = $TransitBillSignedEngine->find($TransitID);
        if ($RowSetSigned->count() == 1) {
            $Result = $RowSetSigned[0];
            $TransitBillTemp = new PiaoliuHK_Models_Core_TransitBill();
            $TransitBillTemp->setTransitBillID($TransitID);
            $TransitBillTemp->setTransitBillOwnerID(
                    $Result["TransitBillOwnerID"]);
            $TransitBillTemp->setTransitBillRelatedPackageIDArray(
                    unserialize($Result["TransitBillRelatedPackageIDArray"]));
            $TransitBillTemp->setTransitBillRelatedPackageQuantity(
                    $Result["TransitBillRelatedPackageQuantity"]);
            $TransitBillTemp->setTransitBillPrice($Result["TransitBillPrice"]);
            $TransitBillTemp->setTransitBillMethod($Result["TransitBillMethod"]);
            $TransitBillTemp->setTransitBillSettlement(
                    $Result["TransitBillSettlement"]);
            $TransitBillTemp->setTransitBillInitializationTimeStamp(
                    $Result["TransitBillInitializationTimeStamp"]);
            $TransitBillTemp->setTransitBillSignDate(
                    $Result["TransitBillSignDate"]);
            $TransitBillTemp->setTransitBillStatus($Result["TransitBillStatus"]);
            array_push($TransitTempArray, $TransitBillTemp);
        }
        
        $TransitBillinShipEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBinShip();
        $RowSetinShip = $TransitBillinShipEngine->find($TransitID);
        if ($RowSetinShip->count() == 1) {
            $Result = $RowSetinShip[0];
            $TransitBillTemp = new PiaoliuHK_Models_Core_TransitBill();
            $TransitBillTemp->setTransitBillID($TransitID);
            $TransitBillTemp->setTransitBillOwnerID(
                    $Result["TransitBillOwnerID"]);
            $TransitBillTemp->setTransitBillRelatedPackageIDArray(
                    unserialize($Result["TransitBillRelatedPackageIDArray"]));
            $TransitBillTemp->setTransitBillRelatedPackageQuantity(
                    $Result["TransitBillRelatedPackageQuantity"]);
            $TransitBillTemp->setTransitBillPrice($Result["TransitBillPrice"]);
            $TransitBillTemp->setTransitBillMethod($Result["TransitBillMethod"]);
            $TransitBillTemp->setTransitBillSettlement(
                    $Result["TransitBillSettlement"]);
            $TransitBillTemp->setTransitBillInitializationTimeStamp(
                    $Result["TransitBillInitializationTimeStamp"]);
            $TransitBillTemp->setTransitBillSignDate(
                    $Result["TransitBillSignDate"]);
            $TransitBillTemp->setTransitBillStatus($Result["TransitBillStatus"]);
            array_push($TransitTempArray, $TransitBillTemp);
        }
        
        $TransitBillCheckoutEngine = new PiaoliuHK_Models_DBEngine_TransitBillDBCheckout();
        $RowSetCheckout = $TransitBillCheckoutEngine->find($TransitID);
        if ($RowSetCheckout->count() == 1) {
            $Result = $RowSetCheckout[0];
            $TransitBillTemp = new PiaoliuHK_Models_Core_TransitBill();
            $TransitBillTemp->setTransitBillID($TransitID);
            $TransitBillTemp->setTransitBillOwnerID(
                    $Result["TransitBillOwnerID"]);
            $TransitBillTemp->setTransitBillRelatedPackageIDArray(
                    unserialize($Result["TransitBillRelatedPackageIDArray"]));
            $TransitBillTemp->setTransitBillRelatedPackageQuantity(
                    $Result["TransitBillRelatedPackageQuantity"]);
            $TransitBillTemp->setTransitBillPrice($Result["TransitBillPrice"]);
            $TransitBillTemp->setTransitBillMethod($Result["TransitBillMethod"]);
            $TransitBillTemp->setTransitBillSettlement(
                    $Result["TransitBillSettlement"]);
            $TransitBillTemp->setTransitBillInitializationTimeStamp(
                    $Result["TransitBillInitializationTimeStamp"]);
            $TransitBillTemp->setTransitBillSignDate(
                    $Result["TransitBillSignDate"]);
            $TransitBillTemp->setTransitBillStatus($Result["TransitBillStatus"]);
            array_push($TransitTempArray, $TransitBillTemp);
        }
        return isset($TransitTempArray[0]) ? $TransitTempArray[0] : null;
    }
}

?>