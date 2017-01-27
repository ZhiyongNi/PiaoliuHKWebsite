<?php
namespace models\logistics;
use models\dbengine\PiaoliuHK_Models_DBEngine_TransitBillTrackInfoDB;

class PiaoliuHK_Models_Logistics_TransitBillTrackInfoList
{

    public static function findTrackInfobyTransitBillID ($ID)
    {
        $TempTransitBillTrackInfo = new PiaoliuHK_Models_Logistics_TransitBillTrackInfo();
        $TransitBillTrackInfoDBEngine = new PiaoliuHK_Models_DBEngine_TransitBillTrackInfoDB();
        $RowSet = $TransitBillTrackInfoDBEngine->find($ID);
        
        if ($RowSet) {
            $Result = $RowSet[0];
            $TempTransitBillTrackInfo->setTransitBillID($Result["TransitBillID"]);
            $TempTransitBillTrackInfo->setTransitBillTrackData(
                    unserialize($Result["TransitBillTrackData"]));
            $TempTransitBillTrackInfo->setPackageUpdateTimeStamp(
                    $Result["PackageUpdateTimeStamp"]);
            $TempTransitBillTrackInfo->setPackageTrackStatus($Result["PackageTrackStatus"]);
            
            return $TempTransitBillTrackInfo;
        } else {
            return NULL;
        }
    }
}

?>