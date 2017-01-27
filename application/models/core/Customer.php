<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_CustomerDB;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_StationAddress;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus;
require_once APPLICATION_PATH . '/models/dbengine/UserDB.php';
require_once APPLICATION_PATH . '/models/dbengine/CustomerDB.php';
require_once APPLICATION_PATH . '/configs/GlobalConstant.php';

class PiaoliuHK_Models_Core_Customer
{

    private $CustomerID;

    private $CustomerName;

    private $CustomerCollage;

    private $CustomerSelfMobile;

    private $CustomerAvatarMobile;

    private $CustomerSelfAddress = array();

    private $CustomerAvatarAddress = array();

    private $CustomerMail;

    private $CustomerQQ;

    private $CustomerRenren;

    private $CustomerWeixin;

    private $CustomerAlipay;

    private $CustomerAccountStatus;

    function __construct ()
    {
        $Args = func_get_args();
        $ArgsNum = func_num_args();
        switch ($ArgsNum) {
            case 0:
                break;
            case 1:
                $ArgsTemp = $Args[0];
                if ($ArgsTemp instanceof PiaoliuHK_Models_Core_User) {
                    $this->constructbyUser($ArgsTemp);
                }
                break;
        }
    }

    function constructbyUser (PiaoliuHK_Models_Core_User $User)
    {
        $this->CustomerID = $User->getUserID();
    }

    function initializeAvatar ()
    {
        $this->CustomerAvatarAddress = [
                "StationCountry" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Country,
                "StationProvince" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Province,
                "StationCity" => PiaoliuHK_Configs_GlobalConstant_StationAddress::City,
                "StationDistrict" => PiaoliuHK_Configs_GlobalConstant_StationAddress::District,
                "StationStreets" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Streets,
                "StationApartment" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Apartment,
                "StationZipCode" => PiaoliuHK_Configs_GlobalConstant_StationAddress::ZipCode,
                "StationMobile" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Mobile,
                "StationTele" => PiaoliuHK_Configs_GlobalConstant_StationAddress::Tele
        ];
        $this->CustomerAvatarMobile = PiaoliuHK_Configs_GlobalConstant_StationAddress::Mobile;
    }

    public static function initializebyID ($CustomerID)
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $RowSet = $CustomerDBEngine->find($CustomerID);
        $CustomerTemp = new PiaoliuHK_Models_Core_Customer();
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $CustomerTemp->CustomerName = $Result["CustomerName"];
            $CustomerTemp->CustomerCollage = $Result["CustomerCollage"];
            $CustomerTemp->CustomerSelfMobile = $Result["CustomerSelfMobile"];
            $CustomerTemp->CustomerAvatarMobile = $Result["CustomerAvatarMobile"];
            $CustomerTemp->CustomerSelfAddress = unserialize(
                    $Result["CustomerSelfAddress"]);
            $CustomerTemp->CustomerAvatarAddress = unserialize(
                    $Result["CustomerAvatarAddress"]);
            $CustomerTemp->CustomerMail = $Result["CustomerMail"];
            $CustomerTemp->CustomerQQ = $Result["CustomerQQ"];
            $CustomerTemp->CustomerRenren = $Result["CustomerRenren"];
            $CustomerTemp->CustomerWeixin = $Result["CustomerWeixin"];
            $CustomerTemp->CustomerAlipay = $Result["CustomerAlipay"];
            $CustomerTemp->CustomerAccountStatus = $Result["CustomerAccountStatus"];
            return $CustomerTemp;
        } else {
            return NULL;
        }
    }

    public static function findCustomerbyID ($CustomerID)
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $RowSet = $CustomerDBEngine->find($CustomerID);
        $CustomerTemp = new PiaoliuHK_Models_Core_Customer();
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $CustomerTemp->setCustomerID($CustomerID);
            $CustomerTemp->setCustomerName($Result["CustomerName"]);
            $CustomerTemp->setCustomerCollage($Result["CustomerCollage"]);
            $CustomerTemp->setCustomerSelfMobile($Result["CustomerSelfMobile"]);
            $CustomerTemp->setCustomerAvatarMobile(
                    $Result["CustomerAvatarMobile"]);
            $CustomerTemp->setCustomerSelfAddress(
                    unserialize($Result["CustomerSelfAddress"]));
            $CustomerTemp->setCustomerAvatarAddress(
                    unserialize($Result["CustomerAvatarAddress"]));
            $CustomerTemp->setCustomerMail($Result["CustomerMail"]);
            $CustomerTemp->setCustomerQQ($Result["CustomerQQ"]);
            $CustomerTemp->setCustomerRenren($Result["CustomerRenren"]);
            $CustomerTemp->setCustomerWeixin($Result["CustomerWeixin"]);
            $CustomerTemp->setCustomerAlipay($Result["CustomerAlipay"]);
            $CustomerTemp->setCustomerAccountStatus(
                    $Result["CustomerAccountStatus"]);
            return $CustomerTemp;
        } else {
            return NULL;
        }
    }

    public static function findAllCustomer ()
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $RowSet = $CustomerDBEngine->fetchAll();
        $CustomerArray = array();
        foreach ($RowSet as $Value) {
            $CustomerTemp = new PiaoliuHK_Models_Core_Customer();
            $CustomerTemp->setCustomerID($Value["CustomerID"]);
            $CustomerTemp->setCustomerName($Value["CustomerName"]);
            $CustomerTemp->setCustomerCollage($Value["CustomerCollage"]);
            $CustomerTemp->setCustomerSelfMobile($Value["CustomerSelfMobile"]);
            $CustomerTemp->setCustomerAvatarMobile(
                    $Value["CustomerAvatarMobile"]);
            $CustomerTemp->setCustomerSelfAddress(
                    unserialize($Value["CustomerSelfAddress"]));
            $CustomerTemp->setCustomerAvatarAddress(
                    unserialize($Value["CustomerAvatarAddress"]));
            $CustomerTemp->setCustomerMail($Value["CustomerMail"]);
            $CustomerTemp->setCustomerQQ($Value["CustomerQQ"]);
            $CustomerTemp->setCustomerRenren($Value["CustomerRenren"]);
            $CustomerTemp->setCustomerWeixin($Value["CustomerWeixin"]);
            $CustomerTemp->setCustomerAlipay($Value["CustomerAlipay"]);
            $CustomerTemp->setCustomerAccountStatus(
                    $Value["CustomerAccountStatus"]);
            
            array_push($CustomerArray, $CustomerTemp);
        }
        return $CustomerArray;
    }

    public static function findIDbySelfMobile ($Mobile)
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $DBAdapter = $CustomerDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('CustomerSelfMobile = ?', $Mobile);
        $Order = 'CustomerID desc';
        $RowSet = $CustomerDBEngine->fetchRow($Where, $Order);
        
        if (isset($RowSet)) {
            return $RowSet["CustomerID"];
        } else {
            return NULL;
        }
    }

    public static function updateCustomerElementstoDB (
            PiaoliuHK_Models_Core_Customer $Customer, array $ElementsArray)
    {
        $Update = array();
        foreach ($ElementsArray as $Value) {
            switch ($Value) {
                case 'CustomerName':
                    $Update['CustomerName'] = $Customer->getCustomerName();
                    break;
                case 'CustomerCollage':
                    $Update['CustomerCollage'] = $Customer->getCustomerCollage();
                    break;
                case 'CustomerSelfMobile':
                    $Update['CustomerSelfMobile'] = $Customer->getCustomerSelfMobile();
                    break;
                case 'CustomerSelfAddress':
                    $Update['CustomerSelfAddress'] = serialize(
                            $Customer->getCustomerSelfAddress());
                    break;
                case 'CustomerMail':
                    $Update['CustomerMail'] = $Customer->getCustomerMail();
                    break;
                case 'CustomerQQ':
                    $Update['CustomerQQ'] = $Customer->getCustomerQQ();
                    break;
                case 'CustomerRenren':
                    $Update['CustomerRenren'] = $Customer->getCustomerRenren();
                    break;
                case 'CustomerWeixin':
                    $Update['CustomerWeixin'] = $Customer->getCustomerWeixin();
                    break;
                case 'CustomerAlipay':
                    $Update['CustomerAlipay'] = $Customer->getCustomerAlipay();
                    break;
                case 'CustomerAccountStatus':
                    $Update['CustomerAccountStatus'] = $Customer->getCustomerAccountStatus();
                    break;
            }
        }
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $DBAdapter = $CustomerDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('CustomerID = ?', 
                $Customer->getCustomerID());
        $CustomerDBEngine->update($Update, $Where);
    }

    public static function updateCustomerAccountStatus ($CustomerID)
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $RowSet = $CustomerDBEngine->find($CustomerID);
        $Status = PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus::Initialization;
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $CustomerName = $Result["CustomerName"];
            $M = mb_strlen($CustomerName, 'utf-8');
            $S = strlen($CustomerName);
            if ($S % $M == 0 && $S % 3 == 0) {
                if (! empty($Result["CustomerSelfMobile"]) &&
                         ! empty($Result["CustomerCollage"]) &&
                         ! empty($Result["CustomerSelfAddress"]) &&
                         ! empty($Result["CustomerMail"])) {
                    $Status = PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus::Unverified;
                }
            }
        } else {
            $Status = PiaoliuHK_Configs_GlobalConstant_CustomerAccountStatus::Initialization;
        }
        $Update = array(
                'CustomerAccountStatus' => $Status
        );
        $DBAdapter = $CustomerDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('CustomerID = ?', $CustomerID);
        $CustomerDBEngine->update($Update, $Where);
    }

    public function addCustomertoDB ()
    {
        $CustomerDBEngine = new PiaoliuHK_Models_DBEngine_CustomerDB();
        $NewRow = $CustomerDBEngine->createRow();
        if ($this->CustomerID) {
            $NewRow->CustomerID = $this->CustomerID;
        }
        if ($this->CustomerName) {
            $NewRow->CustomerName = $this->CustomerName;
        } else {
            $NewRow->CustomerName = rand(1000, 2000);
        }
        $NewRow->CustomerCollage = $this->CustomerCollage;
        $NewRow->CustomerSelfMobile = $this->CustomerSelfMobile;
        $NewRow->CustomerSelfAddress = serialize($this->CustomerSelfAddress);
        $this->initializeAvatar();
        $NewRow->CustomerAvatarMobile = $this->CustomerAvatarMobile;
        $NewRow->CustomerAvatarAddress = serialize($this->CustomerAvatarAddress);
        $NewRow->CustomerMail = $this->getCustomerMail();
        $NewRow->CustomerQQ = $this->getCustomerQQ();
        $NewRow->CustomerRenren = $this->getCustomerRenren();
        $NewRow->CustomerWeixin = $this->getCustomerWeixin();
        $NewRow->CustomerAlipay = $this->getCustomerAlipay();
        $NewRow->CustomerAccountStatus = $this->getCustomerAccountStatus();
        
        $NewRow->save();
    }

    public function setCustomerID ($ID)
    {
        $this->CustomerID = $ID;
    }

    public function getCustomerID ()
    {
        return $this->CustomerID;
    }

    public function setCustomerName ($Name)
    {
        $this->CustomerName = $Name;
    }

    public function getCustomerName ()
    {
        return $this->CustomerName;
    }

    public function setCustomerCollage ($Collage)
    {
        $this->CustomerCollage = $Collage;
    }

    public function getCustomerCollage ()
    {
        return $this->CustomerCollage;
    }

    public function setCustomerSelfMobile ($Mobile)
    {
        $this->CustomerSelfMobile = $Mobile;
    }

    public function getCustomerSelfMobile ()
    {
        return $this->CustomerSelfMobile;
    }

    public function setCustomerAvatarMobile ($Mobile)
    {
        $this->CustomerAvatarMobile = $Mobile;
    }

    public function getCustomerAvatarMobile ()
    {
        return $this->CustomerAvatarMobile;
    }

    public function setCustomerSelfAddress ($Address)
    {
        $this->CustomerSelfAddress = $Address;
    }

    public function getCustomerSelfAddress ()
    {
        return $this->CustomerSelfAddress;
    }

    public function setCustomerAvatarAddress ($Address)
    {
        $this->CustomerAvatarAddress = $Address;
    }

    public function getCustomerAvatarAddress ()
    {
        return $this->CustomerAvatarAddress;
    }

    public function setCustomerMail ($Mail)
    {
        $this->CustomerMail = $Mail;
    }

    public function getCustomerMail ()
    {
        return $this->CustomerMail;
    }

    public function setCustomerQQ ($QQ)
    {
        $this->CustomerQQ = $QQ;
    }

    public function getCustomerQQ ()
    {
        return $this->CustomerQQ;
    }

    public function setCustomerRenren ($Renren)
    {
        $this->CustomerRenren = $Renren;
    }

    public function getCustomerRenren ()
    {
        return $this->CustomerRenren;
    }

    public function setCustomerWeixin ($Weixin)
    {
        $this->CustomerWeixin = $Weixin;
    }

    public function getCustomerWeixin ()
    {
        return $this->CustomerWeixin;
    }

    public function setCustomerAlipay ($Alipay)
    {
        $this->CustomerAlipay = $Alipay;
    }

    public function getCustomerAlipay ()
    {
        return $this->CustomerAlipay;
    }

    public function setCustomerAccountStatus ($Status)
    {
        $this->CustomerAccountStatus = $Status;
    }

    public function getCustomerAccountStatus ()
    {
        return $this->CustomerAccountStatus;
    }
}

?>