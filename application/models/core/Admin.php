<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_AdminDB;
require_once APPLICATION_PATH . '/models/dbengine/UserDB.php';
require_once APPLICATION_PATH . '/models/dbengine/AdminDB.php';
require_once APPLICATION_PATH . '/configs/GlobalConstant.php';

class PiaoliuHK_Models_Core_Admin
{

    private $AdminID;

    private $AdminName;

    private $AdminMobile;

    private $AdminAccountStatus;

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
        $this->AdminID = $User->getUserID();
    }

    public static function initializebyID ($AdminID)
    {
        $AdminDBEngine = new PiaoliuHK_Models_DBEngine_AdminDB();
        $RowSet = $AdminDBEngine->find($AdminID);
        $AdminTemp = new PiaoliuHK_Models_Core_Admin();
        if ($RowSet->count() == 1) {
            $Result = $RowSet[0];
            $AdminTemp->AdminName = $Result["AdminName"];
            $AdminTemp->AdminMobile = $Result["AdminMobile"];
            $AdminTemp->AdminAccountStatus = $Result["AdminAccountStatus"];
            return $AdminTemp;
        } else {
            return NULL;
        }
    }

    public static function findNamebyID ($ID)
    {
        $AdminDBEngine = new PiaoliuHK_Models_DBEngine_AdminDB();
        $RowSet = $AdminDBEngine->find($ID);
        
        if ($RowSet) {
            $Result = $RowSet[0];
            return $Result["AdminName"];
        } else {
            return NULL;
        }
    }

    public function addAdmintoDB ()
    {
        $AdminDBEngine = new PiaoliuHK_Models_DBEngine_AdminDB();
        $NewRow = $AdminDBEngine->createRow();
        if (isset($this->AdminID)) {
            $NewRow->AdminID = $this->AdminID;
        }
        if (isset($this->AdminName)) {
            $NewRow->AdminName = $this->AdminName;
        } else {
            $NewRow->AdminName = rand(1000, 2000);
        }
        if (isset($this->AdminMobile)) {
            $NewRow->AdminMobile = $this->AdminMobile;
        }
        if (isset($this->AdminAccountStatus)) {
            $NewRow->AdminAccountStatus = $this->AdminAccountStatus;
        }
        $NewRow->save();
    }

    public function setAdminID ($ID)
    {
        $this->AdminID = $ID;
    }

    public function getAdminID ()
    {
        return $this->AdminID;
    }

    public function setAdminName ($Name)
    {
        $this->AdminName = $Name;
    }

    public function getAdminName ()
    {
        return $this->AdminName;
    }

    public function setAdminMobile ($Mobile)
    {
        $this->AdminMobile = $Mobile;
    }

    public function getAdminMobile ()
    {
        return $this->AdminMobile;
    }

    public function setAdminAccountStatus ($Status)
    {
        $this->AdminAccountStatus = $Status;
    }

    public function getAdminAccountStatus ()
    {
        return $this->AdminAccountStatus;
    }
}

?>