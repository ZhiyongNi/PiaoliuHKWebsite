<?php
namespace models\logistics;
use models\dbengine\PiaoliuHK_Models_DBEngine_PackageTrackInfoDB;
use models\core\PiaoliuHK_Models_Core_Package;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany;
require_once APPLICATION_PATH . '/models/dbengine/PackageTrackInfoDB.php';
require_once APPLICATION_PATH . '/models/logistics/PackageTrackInfo.php';
require_once APPLICATION_PATH . '/models/Aikuaidi/Aikuaidi.php';

class PiaoliuHK_Models_Logistics_PackageTrackInfoList
{

    public static function trackAllPackageInfo ()
    {
        ignore_user_abort();
        set_time_limit(0);
        
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $RowSet = $PackageTrackInfoDBEngine->fetchAll();
        $i = 0;
        foreach ($RowSet as $Value) {
            $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
            $TrackInfoTemp->setPackageID($Value["PackageID"]);
            
            $JsonString = \Aikuaidi::trackExpressInfo(
                    $Value["PackageExpressCompany"], 
                    $Value["PackageExpressTrackNumber"]);
            $TrackInfoTemp->initializebyJson($JsonString);
            
            $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
            $Where = $DBAdapter->quoteInto('PackageID = ?', 
                    $TrackInfoTemp->getPackageID());
            $Update = array(
                    'PackageExpressCompanyName' => $TrackInfoTemp->getPackageExpressCompanyName(),
                    'PackageTrackData' => serialize(
                            $TrackInfoTemp->getPackageTrackData()),
                    'PackageUpdateTimeStamp' => $TrackInfoTemp->getPackageUpdateTimeStamp(),
                    'PackageTrackStatus' => $TrackInfoTemp->getPackageTrackStatus()
            );
            $PackageTrackInfoDBEngine->update($Update, $Where);
            
            $i ++;
            if (($TrackInfoTemp->getErrorCode() == 3) or ($i > 100)) {
                echo "OK";
                break;
            }
        }
    }

    public static function trackAllBlankPackageInfo ()
    {
        ignore_user_abort(True);
        set_time_limit(0);
        echo 'begin...</br>';
        
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
        $Where[0] = $DBAdapter->quoteInto('PackageTrackStatus IS NULL', NULL);
        $Where[1] = $DBAdapter->quoteInto('PackageUpdateTimeStamp IS NULL', 
                NULL);
        $Order = 'PackageID ASC';
        $RowSet = $PackageTrackInfoDBEngine->fetchAll($Where, $Order);
        
        $i = 0;
        foreach ($RowSet as $Value) {
            $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
            $TrackInfoTemp->setPackageID($Value["PackageID"]);
            
            $JsonString = \Aikuaidi::trackExpressInfo(
                    $Value["PackageExpressCompany"], 
                    $Value["PackageExpressTrackNumber"]);
            $TrackInfoTemp->initializebyJson($JsonString);
            
            $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
            $Where = $DBAdapter->quoteInto('PackageID = ?', 
                    $TrackInfoTemp->getPackageID());
            $Update = array(
                    'PackageExpressCompanyName' => $TrackInfoTemp->getPackageExpressCompanyName(),
                    'PackageTrackData' => serialize(
                            $TrackInfoTemp->getPackageTrackData()),
                    'PackageUpdateTimeStamp' => $TrackInfoTemp->getPackageUpdateTimeStamp(),
                    'PackageTrackStatus' => $TrackInfoTemp->getPackageTrackStatus()
            );
            $PackageTrackInfoDBEngine->update($Update, $Where);
            
            echo $TrackInfoTemp->getPackageID() . "</br>";
            
            $i ++;
            if (($TrackInfoTemp->getErrorCode() == 3) or ($i > 300)) {
                echo "OK";
                break;
            }
        }
    }

    public static function trackPartBlankPackageInfo ()
    {
        ignore_user_abort(True);
        set_time_limit(0);
        echo 'begin...</br>';
        
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
        $Where[0] = $DBAdapter->quoteInto('PackageTrackStatus IS NULL', NULL);
        $Where[1] = $DBAdapter->quoteInto('PackageUpdateTimeStamp IS NOT NULL', 
                NULL);
        $Order = 'PackageID ASC';
        $RowSet = $PackageTrackInfoDBEngine->fetchAll($Where, $Order);
        
        $i = 0;
        foreach ($RowSet as $Value) {
            $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
            $TrackInfoTemp->setPackageID($Value["PackageID"]);
            
            $JsonString = \Aikuaidi::trackExpressInfo(
                    $Value["PackageExpressCompany"], 
                    $Value["PackageExpressTrackNumber"]);
            $TrackInfoTemp->initializebyJson($JsonString);
            
            $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
            $Where = $DBAdapter->quoteInto('PackageID = ?', 
                    $TrackInfoTemp->getPackageID());
            $Update = array(
                    'PackageExpressCompanyName' => $TrackInfoTemp->getPackageExpressCompanyName(),
                    'PackageTrackData' => serialize(
                            $TrackInfoTemp->getPackageTrackData()),
                    'PackageUpdateTimeStamp' => $TrackInfoTemp->getPackageUpdateTimeStamp(),
                    'PackageTrackStatus' => $TrackInfoTemp->getPackageTrackStatus()
            );
            $PackageTrackInfoDBEngine->update($Update, $Where);
            
            echo $TrackInfoTemp->getPackageID() . "</br>";
            
            $i ++;
            if (($TrackInfoTemp->getErrorCode() == 3) or ($i > 600)) {
                echo "OK";
                break;
            }
        }
    }

    public static function trackUnfinishedPackageInfo ()
    {
        ignore_user_abort(True);
        set_time_limit(0);
        echo 'begin...</br>';
        
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('PackageTrackStatus IS NULL', NULL);
        $Order = 'PackageID ASC';
        $RowSet = $PackageTrackInfoDBEngine->fetchAll($Where, $Order);
        
        $i = 0;
        foreach ($RowSet as $Value) {
            $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
            $TrackInfoTemp->setPackageID($Value["PackageID"]);
            
            $JsonString = \Aikuaidi::trackExpressInfo(
                    $Value["PackageExpressCompany"], 
                    $Value["PackageExpressTrackNumber"]);
            $TrackInfoTemp->initializebyJson($JsonString);
            
            $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
            $Where = $DBAdapter->quoteInto('PackageID = ?', 
                    $TrackInfoTemp->getPackageID());
            $Update = array(
                    'PackageExpressCompanyName' => $TrackInfoTemp->getPackageExpressCompanyName(),
                    'PackageTrackData' => serialize(
                            $TrackInfoTemp->getPackageTrackData()),
                    'PackageUpdateTimeStamp' => $TrackInfoTemp->getPackageUpdateTimeStamp(),
                    'PackageTrackStatus' => $TrackInfoTemp->getPackageTrackStatus()
            );
            $PackageTrackInfoDBEngine->update($Update, $Where);
            
            echo $TrackInfoTemp->getPackageID() . "</br>";
            
            $i ++;
            if (($TrackInfoTemp->getErrorCode() == 3) or ($i > 300)) {
                echo "OK";
                break;
            }
        }
    }

    public static function trackPackageInfobyID ($PackageID) // something wrong
    {
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $RowSet = $PackageTrackInfoDBEngine->find($PackageID);
        
        if ($RowSet) {
            $Result = $RowSet[0];
            $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
            $TrackInfoTemp->setPackageID($Result["PackageID"]);
            
            $JsonString = \Aikuaidi::trackExpressInfo(
                    $Result["PackageExpressCompany"], 
                    $Result["PackageExpressTrackNumber"]);
            $TrackInfoTemp->initializebyJson($JsonString);
            
            $DBAdapter = $PackageTrackInfoDBEngine->getAdapter();
            $Where = $DBAdapter->quoteInto('PackageID = ?', 
                    $TrackInfoTemp->getPackageID());
            $Update = array(
                    'PackageExpressCompanyName' => $TrackInfoTemp->getPackageExpressCompanyName(),
                    'PackageTrackData' => serialize(
                            $TrackInfoTemp->getPackageTrackData()),
                    'PackageUpdateTimeStamp' => $TrackInfoTemp->getPackageUpdateTimeStamp(),
                    'PackageTrackStatus' => $TrackInfoTemp->getPackageTrackStatus()
            );
            $PackageTrackInfoDBEngine->update($Update, $Where);
        }
    }

    public static function findTrackInfobyPackageID ($ID)
    {
        $TempTrackInfo = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $RowSet = $PackageTrackInfoDBEngine->find($ID);
        
        if (count($RowSet) != 0) {
            $Result = $RowSet[0];
            $TempTrackInfo->setPackageID($Result["PackageID"]);
            $TempTrackInfo->setPackageExpressCompany(
                    $Result["PackageExpressCompany"]);
            $TempTrackInfo->setPackageExpressTrackNumber(
                    $Result["PackageExpressTrackNumber"]);
            $TempTrackInfo->setPackageExpressCompanyName(
                    $Result["PackageExpressCompanyName"]);
            $TempTrackInfo->setPackageTrackData(
                    unserialize($Result["PackageTrackData"]));
            $TempTrackInfo->setPackageUpdateTimeStamp(
                    $Result["PackageUpdateTimeStamp"]);
            $TempTrackInfo->setPackageTrackStatus($Result["PackageTrackStatus"]);
            
            return $TempTrackInfo;
        } else {
            return NULL;
        }
    }

    public static function registerPackageTrackInfo (
            PiaoliuHK_Models_Core_Package $Package)
    {
        $TrackInfoTemp = new PiaoliuHK_Models_Logistics_PackageTrackInfo();
        $TrackInfoTemp->setPackageID($Package->getPackageID());
        $PackageExpressCompany = "";
        switch ($Package->getPackageExpressCompany()) {
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShunfengExpress:
                $PackageExpressCompany = PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpressCode;
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YuantongExpress:
                $PackageExpressCompany = "yuantong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZhongtongExpress:
                $PackageExpressCompany = "zhongtong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YundaExpress:
                $PackageExpressCompany = "yunda";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpress:
                $PackageExpressCompany = "shentong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::EMSExpress:
                $PackageExpressCompany = "ems";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuitongExpress:
                $PackageExpressCompany = "huitong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::TiantianExpress:
                $PackageExpressCompany = "tiantian";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YousuExpress:
                $PackageExpressCompany = "yousu";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::JingdongExpress:
                $PackageExpressCompany = "jingdong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::GuotongExpress:
                $PackageExpressCompany = "guotong";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::LongbangExpress:
                $PackageExpressCompany = "longbang";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::SuerExpress:
                $PackageExpressCompany = "suer";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuiqiangExpress:
                $PackageExpressCompany = "huiqiang";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZJSExpress:
                $PackageExpressCompany = "zjs";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::QuanfengExpress:
                $PackageExpressCompany = "quanfeng";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::DangdangExpress:
                $PackageExpressCompany = "dangdang";
                break;
            case PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::AmazonExpress:
                $PackageExpressCompany = "amazon";
                break;
            
            default:
                $PackageExpressCompany = - 1;
                break;
        }
        $JsonString = \Aikuaidi::trackExpressInfo($PackageExpressCompany, 
                $Package->getPackageExpressTrackNumber());
        $TrackInfoTemp->initializebyJson($JsonString);
        
        $PackageTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_PackageTrackInfoDB();
        $NewRow = $PackageTrackInfoDBEngine->createRow();
        
        $NewRow->PackageID = $Package->getPackageID();
        $NewRow->PackageExpressCompany = $PackageExpressCompany;
        $NewRow->PackageExpressTrackNumber = $Package->getPackageExpressTrackNumber();
        $NewRow->PackageExpressCompanyName = $TrackInfoTemp->getPackageExpressCompanyName();
        $NewRow->PackageTrackData = serialize(
                $TrackInfoTemp->getPackageTrackData());
        $NewRow->PackageUpdateTimeStamp = $TrackInfoTemp->getPackageUpdateTimeStamp();
        $NewRow->PackageTrackStatus = $TrackInfoTemp->getPackageTrackStatus();
        
        $NewRow->save();
        return TRUE;
    }
}

?>