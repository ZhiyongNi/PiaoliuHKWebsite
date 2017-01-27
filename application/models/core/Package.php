<?php
namespace models\core;

class PiaoliuHK_Models_Core_Package
{

    private $PackageID;

    private $PackageOwnerID;

    private $PackageOwnerMobile;

    private $PackageExpressCompany;

    private $PackageExpressTrackNumber;

    private $PackageSnapshot;

    private $PackageWeight;

    private $PackageFare;

    private $PackageInTimeStamp;

    private $PackageOutTimeStamp;

    private $PackageStatus;

    private $PackageChannel;

    private $PackageRemarks;

    public function getPackageID ()
    {
        return $this->PackageID;
    }

    public function setPackageID ($ID)
    {
        $this->PackageID = $ID;
    }

    public static function getPackageSerialNumber ()
    {
        $CacheFile = \Zend_Cache::factory('Core', 'File');
        $PackageSerialNumber = $CacheFile->load('PackageSerialNumber');
        if (! empty($PackageSerialNumber) && substr($PackageSerialNumber, 0, 8) ==
                 \Zend_Date::now()->toString('yyyyMMdd')) {
            return (int) substr($PackageSerialNumber, 8);
        } else {
            return 0;
        }
    }

    public static function setPackageSerialNumber ($SerialNumber)
    {
        $CacheFile = \Zend_Cache::factory('Core', 'File');
        $SerialNumber = \Zend_Date::now()->toString('yyyyMMdd') .
                 sprintf("%03d", $SerialNumber);
        $CacheFile->save($SerialNumber, 'PackageSerialNumber');
    }

    public static function initializePackageSerialID ($SerialNumber)
    {
        $PackageSerialID = \Zend_Date::now()->toString('yyyyMMdd') .
                 sprintf("%03d", $SerialNumber);
        if (strlen($PackageSerialID) != 11) {
            return false;
        }
        // 加权因子
        $factor = array(
                7,
                9,
                10,
                5,
                8,
                4,
                2,
                1,
                6,
                3,
                7
        );
        // 校验码对应值
        $verify_number_list = array(
                '1',
                '0',
                'X',
                '9',
                '8',
                '7',
                '6',
                '5',
                '4',
                '3',
                '2'
        );
        $checksum = 0;
        for ($i = 0; $i < strlen($PackageSerialID); $i ++) {
            $checksum += substr($PackageSerialID, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return 'PA' . $PackageSerialID . $verify_number;
    }

    public function getPackageOwnerID ()
    {
        return $this->PackageOwnerID;
    }

    public function setPackageOwnerID ($OwnerID)
    {
        $this->PackageOwnerID = $OwnerID;
    }

    public function getPackageOwnerMobile ()
    {
        return $this->PackageOwnerMobile;
    }

    public function setPackageOwnerMobile ($Mobile)
    {
        $this->PackageOwnerMobile = $Mobile;
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

    public function setPackageExpressTrackNumber ($ExpressTrackNumber)
    {
        $this->PackageExpressTrackNumber = $ExpressTrackNumber;
    }

    public function getPackageSnapshot ()
    {
        return $this->packageSnapshot;
    }

    public function setPackageSnapshot ($Snapshot)
    {
        $this->packageSnapshot = $Snapshot;
    }

    public function getPackageWeight ()
    {
        return $this->packageWeight;
    }

    public function setPackageWeight ($Weight)
    {
        $this->packageWeight = $Weight;
    }

    public function getPackageFare ()
    {
        return $this->PackageFare;
    }

    public function setPackageFare ($Fare)
    {
        $this->PackageFare = $Fare;
    }

    public function getPackageInTime ()
    {
        $Time = new \DateTime();
        $Time->setTimestamp($this->PackageInTimeStamp);
        return $Time->format('Y-m-d H:i:s');
    }

    public function setPackageInTime ($InTime)
    {
        $Time = new \DateTime($InTime);
        $this->PackageInTimeStamp = $Time->getTimestamp();
    }

    public function getPackageInTimeStamp ()
    {
        return $this->PackageInTimeStamp;
    }

    public function setPackageInTimeStamp ($InTimeStamp)
    {
        $this->PackageInTimeStamp = $InTimeStamp;
    }

    public function getPackageOutTime ()
    {
        $Time = new \DateTime();
        $Time->setTimestamp($this->PackageOutTimeStamp);
        return $Time->format('Y-m-d H:i:s');
    }

    public function setPackageOutTime ($OutTime)
    {
        $Time = new \DateTime($OutTime);
        $this->PackageOutTimeStamp = $Time->getTimestamp();
    }

    public function getPackageOutTimeStamp ()
    {
        return $this->PackageOutTimeStamp;
    }

    public function setPackageOutTimeStamp ($OutTimeStamp)
    {
        $this->PackageOutTimeStamp = $OutTimeStamp;
    }

    public function getPackageStatus ()
    {
        return $this->PackageStatus;
    }

    public function setPackageStatus ($Status)
    {
        $this->PackageStatus = $Status;
    }

    public function getPackageChannel ()
    {
        return $this->PackageChannel;
    }

    public function setPackageChannel ($Channel)
    {
        $this->PackageChannel = $Channel;
    }

    public function getPackageRemarks ()
    {
        return $this->PackageRemarks;
    }

    public function setPackageRemarks ($Remarks)
    {
        $this->PackageRemarks = $Remarks;
    }
}

?>