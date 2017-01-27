<?php
namespace models\core;

class PiaoliuHK_Models_Core_TransitBill
{

    private $TransitBillID;

    private $TransitBillOwnerID;

    private $TransitBillRelatedPackageIDArray = array();

    private $TransitBillRelatedPackageQuantity;

    private $TransitBillPrice;

    private $TransitBillMethod;

    private $TransitBillAddress;

    private $TransitBillSettlement;

    private $TransitBillInitializationTimeStamp;

    private $TransitBillSignDate;

    private $TransitBillStatus;

    public function getTransitBillID ()
    {
        return $this->TransitBillID;
    }

    public function setTransitBillID ($ID)
    {
        $this->TransitBillID = $ID;
    }

    public static function getTransitBillSerialNumber ()
    {
        $CacheFile = \Zend_Cache::factory('Core', 'File');
        $TransitBillSerialNumber = $CacheFile->load('TransitBillSerialNumber');
        
        if (! empty($TransitBillSerialNumber) && substr(
                $TransitBillSerialNumber, 0, 8) ==
                 \Zend_Date::now()->toString('yyyyMMdd')) {
            return (int) substr($TransitBillSerialNumber, 8);
        } else {
            return 0;
        }
    }

    public static function setTransitBillSerialNumber ($SerialNumber)
    {
        $CacheFile = \Zend_Cache::factory('Core', 'File');
        $SerialNumber = \Zend_Date::now()->toString('yyyyMMdd') .
                 sprintf("%03d", $SerialNumber);
        $CacheFile->save($SerialNumber, 'TransitBillSerialNumber');
    }

    public static function initializeTransitBillSerialID ($SerialNumber)
    {
        $TransitBillSerialID = \Zend_Date::now()->toString('yyyyMMdd') .
                 sprintf("%03d", $SerialNumber);
        if (strlen($TransitBillSerialID) != 11) {
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
        for ($i = 0; $i < strlen($TransitBillSerialID); $i ++) {
            $checksum += substr($TransitBillSerialID, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return 'TB' . $TransitBillSerialID . $verify_number;
    }

    public function getTransitBillOwnerID ()
    {
        return $this->TransitBillOwnerID;
    }

    public function setTransitBillOwnerID ($ID)
    {
        $this->TransitBillOwnerID = $ID;
    }

    public function getTransitBillRelatedPackageIDArray ()
    {
        return $this->TransitBillRelatedPackageIDArray;
    }

    public function setTransitBillRelatedPackageIDArray (array $PackageIDArray)
    {
        $this->TransitBillRelatedPackageIDArray = $PackageIDArray;
    }

    public function addTransitBillRelatedPackageIDArray ($PackageID)
    {
        array_push($this->TransitBillRelatedPackageIDArray, $PackageID);
    }

    public function getTransitBillRelatedPackageQuantity ()
    {
        return $this->TransitBillRelatedPackageQuantity;
    }

    public function setTransitBillRelatedPackageQuantity ($Quantity)
    {
        $this->TransitBillRelatedPackageQuantity = $Quantity;
    }

    public function getTransitBillPrice ()
    {
        return $this->TransitBillPrice;
    }

    public function setTransitBillPrice ($money)
    {
        $this->TransitBillPrice = $money;
    }

    public function getTransitBillMethod ()
    {
        return $this->TransitBillMethod;
    }

    public function setTransitBillMethod ($Method)
    {
        $this->TransitBillMethod = $Method;
    }

    public function getTransitBillAddress ()
    {
        return $this->TransitBillAddress;
    }

    public function setTransitBillAddress ($Address)
    {
        $this->TransitBillAddress = $Address;
    }

    public function getTransitBillSettlement ()
    {
        return $this->TransitBillSettlement;
    }

    public function setTransitBillSettlement ($Settlement)
    {
        $this->TransitBillSettlement = $Settlement;
    }

    public function getTransitBillInitializationTime ()
    {
        $Time = new \DateTime();
        $Time->setTimestamp($this->TransitBillInitializationTimeStamp);
        return $Time->format('Y-m-d H:i:s');
    }

    public function setTransitBillInitializationTime ($Time)
    {
        $Time = new \DateTime($Time);
        $this->TransitBillInitializationTimeStamp = $Time->getTimestamp();
    }

    public function getTransitBillInitializationTimeStamp ()
    {
        return $this->TransitBillInitializationTimeStamp;
    }

    public function setTransitBillInitializationTimeStamp ($TimeStamp)
    {
        $this->TransitBillInitializationTimeStamp = $TimeStamp;
    }

    public function getTransitBillSignDate ()
    {
        return $this->TransitBillSignDate;
    }

    public function setTransitBillSignDate ($Date)
    {
        $this->TransitBillSignDate = $Date;
    }

    public function getTransitBillStatus ()
    {
        return $this->TransitBillStatus;
    }

    public function setTransitBillStatus ($Status)
    {
        $this->TransitBillStatus = $Status;
    }
}

?>