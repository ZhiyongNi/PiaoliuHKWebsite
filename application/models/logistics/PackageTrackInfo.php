<?php
namespace models\logistics;

class PiaoliuHK_Models_Logistics_PackageTrackInfo
{

    public $PackageID;

    public $PackageExpressCompany;

    public $PackageExpressTrackNumber;

    public $PackageExpressCompanyName;

    public $PackageTrackData;

    public $PackageUpdateTimeStamp;

    public $PackageTrackStatus;

    private $ErrorCode;

    public function initializebyJson ($Json)
    {
        $JsonArray = json_decode($Json, TRUE);
        $this->PackageExpressCompany = $JsonArray["id"];
        $this->PackageExpressCompanyName = $JsonArray["name"];
        $this->PackageExpressTrackNumber = $JsonArray["order"];
        
        $Time = new \DateTime($JsonArray["updateTime"]);
        $this->PackageUpdateTimeStamp = $Time->getTimestamp();
        
        $this->ErrorCode = $JsonArray["errCode"];
        $this->PackageTrackStatus = $JsonArray["status"];
        $this->PackageTrackData = $JsonArray["data"];
    }

    public static function toJson (PiaoliuHK_Models_Logistics_PackageTrackInfo $Info)
    {
        if ($Info instanceof PiaoliuHK_Models_Logistics_PackageTrackInfo) {
            $JsonString = "";
            $JsonString .= '{';
            $JsonString .= '"PackageID":"' . $Info->getPackageID() . '",';
            $JsonString .= '"PackageExpressCompany":"' .
                     $Info->getPackageExpressCompany() . '",';
            $JsonString .= '"PackageExpressTrackNumber":"' .
                     $Info->getPackageExpressTrackNumber() . '",';
            $JsonString .= '"PackageExpressCompanyName":"' .
                     $Info->getPackageExpressCompanyName() . '",';
            $JsonString .= '"PackageUpdateTimeStamp":"' .
                     $Info->getPackageUpdateTimeStamp() . '",';
            $JsonString .= '"PackageTrackStatus":"' .
                     $Info->getPackageTrackStatus() . '",';
            $JsonString .= '"PackageTrackData":[';
            
            foreach ($Info->getPackageTrackData() as $midValue) {
                $JsonString .= '{';
                foreach ($midValue as $Key => $Value) {
                    $JsonString .= '"' . $Key . '":"' . $Value . '",';
                }
                $JsonString = rtrim($JsonString, ',');
                $JsonString .= '},';
            }
            $JsonString = rtrim($JsonString, ',');
            $JsonString .= ']}';
                    
            return $JsonString;
        } else {
            return NULL;
        }
    }

    public function getPackageID ()
    {
        return $this->PackageID;
    }

    public function setPackageID ($ID)
    {
        $this->PackageID = $ID;
    }

    public function getPackageExpressCompany ()
    {
        return $this->PackageExpressCompany;
    }

    public function setPackageExpressCompany ($ExpressCompany)
    {
        $this->PackageExpressCompany = $ExpressCompany;
    }

    public function getPackageExpressTrackNumber ()
    {
        return $this->PackageExpressTrackNumber;
    }

    public function setPackageExpressTrackNumber ($TrackNumber)
    {
        $this->PackageExpressTrackNumber = $TrackNumber;
    }

    public function getPackageExpressCompanyName ()
    {
        return $this->PackageExpressCompanyName;
    }

    public function setPackageExpressCompanyName ($ExpressCompanyName)
    {
        $this->PackageExpressCompanyName = $ExpressCompanyName;
    }

    public function getPackageTrackData ()
    {
        return $this->PackageTrackData;
    }

    public function setPackageTrackData ($Data)
    {
        $this->PackageTrackData = $Data;
    }

    public function getPackageUpdateTimeStamp ()
    {
        return $this->PackageUpdateTimeStamp;
    }

    public function setPackageUpdateTimeStamp ($TimeStamp)
    {
        $this->PackageUpdateTimeStamp = $TimeStamp;
    }

    public function getPackageTrackStatus ()
    {
        return $this->PackageTrackStatus;
    }

    public function setPackageTrackStatus ($Status)
    {
        $this->PackageTrackStatus = $Status;
    }

    public function getErrorCode ()
    {
        return $this->ErrorCode;
    }

    public function setErrorCode ($Code)
    {
        $this->ErrorCode = $Code;
    }
}

?>