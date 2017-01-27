<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_UserDB;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult;
require_once APPLICATION_PATH . '/models/dbengine/UserDB.php';
require_once APPLICATION_PATH . '/configs/GlobalConstant.php';

class PiaoliuHK_Models_Core_User
{

    private $UserID;

    private $UserName;

    private $UserPassword;

    private $Type;

    private $isAuthorized;

    public static function updateNewPassword ($UserID, $Password)
    {
        $UserDBEngine = new PiaoliuHK_Models_DBEngine_UserDB();
        $DBAdapter = $UserDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('UserID = ?', $UserID);
        $Update = array(
                'Password' => $Password
        );
        $UserDBEngine->update($Update, $Where);
    }

    public static function AuthUserbyNameandPassword ($f_UserName, $f_Password)
    {
        $UserDBEngine = new PiaoliuHK_Models_DBEngine_UserDB();
        $DBAdapter = $UserDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('UserName = ?', $f_UserName);
        $Order = 'UserID desc';
        $RowSet = $UserDBEngine->fetchRow($Where, $Order);
        
        if (isset($RowSet)) {
            if (strcmp($f_Password, $RowSet["Password"]) == 0) {
                $Result = array(
                        "AuthResult" => PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Right,
                        "UserID" => $RowSet["UserID"]
                );
                return $Result;
            } else {
                $Result = array(
                        "AuthResult" => PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Wrong,
                        "UserID" => null
                );
                return $Result;
            }
        } else {
            $Result = array(
                    "AuthResult" => PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::None,
                    "UserID" => null
            );
            return $Result;
        }
    }

    public static function AuthUserbyIDandPassword ($UserID, $Password)
    {
        $UserDBEngine = new PiaoliuHK_Models_DBEngine_UserDB();
        $RowSet = $UserDBEngine->find($UserID);
        $Result = $RowSet[0];
        
        if ($Result) {
            if (strcmp($Password, $Result["Password"]) == 0) {
                return '1';
            } else {
                echo '密码错误';
                return '2';
            }
        } else {
            echo '无用户';
            return '3';
        }
    }

    public function addUsertoDB ()
    
    {
        $UserDBEngine = new PiaoliuHK_Models_DBEngine_UserDB();
        $NewRow = $UserDBEngine->createRow();
        $NewRow->UserName = $this->UserName;
        $NewRow->Password = $this->UserPassword;
        $NewRow->Type = $this->Type;
        $ID = $NewRow->save();
        return $ID;
    }

    public function setUserID ($ID)
    {
        $this->UserID = $ID;
    }

    public function getUserID ()
    {
        return $this->UserID;
    }

    public function setUserName ($Name)
    {
        $this->UserName = $Name;
    }

    public function getUserName ()
    {
        return $this->UserName;
    }

    public function setUserPassword ($Password)
    {
        $this->UserPassword = $Password;
    }

    public function getUserPassword ()
    {
        return $this->UserPassword;
    }

    public function setType ($Type)
    {
        $this->Type = $Type;
    }

    public function getType ()
    {
        return $this->Type;
    }

    public function isAuthorized ()
    {
        return $this->isAuthorized;
    }
}

?>