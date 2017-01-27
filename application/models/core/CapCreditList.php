<?php
namespace models\core;
use models\dbengine\PiaoliuHK_Models_DBEngine_CapCreditDB;
require_once APPLICATION_PATH . '/models/dbengine/CapCreditDB.php';
require_once APPLICATION_PATH . '/models/core/CapCredit.php';

class PiaoliuHK_Models_Core_CapCreditList
{

    public static function findCapCreditTablebyID ($CustomerID)
    {
        $CapCreditDBEngine = new PiaoliuHK_Models_DBEngine_CapCreditDB();
        $DBAdapter = $CapCreditDBEngine->getAdapter();
        $Where = $DBAdapter->quoteInto('CustomerID = ?', $CustomerID);
        $Order = 'CapCreditID desc';
        $RowSet = $CapCreditDBEngine->fetchAll($Where, $Order);
        
        $CapCreditArray = array();
        foreach ($RowSet as $Value) {
            $TempCapCredit = new PiaoliuHK_Models_Core_CapCredit();
            $TempCapCredit->setCapCreditTimeStamp($Value["CapCreditTimeStamp"]);
            $TempCapCredit->setCapCreditLedger($Value["CapCreditLedger"]);
            $TempCapCredit->setCapCreditRemarks($Value["CapCreditRemarks"]);
            array_push($CapCreditArray, $TempCapCredit);
        }
        return $CapCreditArray;
    }

    public static function toCapCreditTableArray (Array $CapCreditListArray)
    {
        $CapCreditTableArray = array();
        foreach ($CapCreditListArray as $Value) {
            if ($Value instanceof PiaoliuHK_Models_Core_CapCredit) {
                $CapCreditTable = [
                        "CapCreditTimeStamp" => $Value->getCapCreditTime(),
                        "CapCreditLedger" => $Value->getCapCreditLedger(),
                        "CapCreditRemarks" => $Value->getCapCreditRemarks()
                ];
                array_push($CapCreditTableArray, $CapCreditTable);
            }
        }
        return $CapCreditTableArray;
    }
}

?>