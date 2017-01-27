<?php
namespace models\core;
require_once APPLICATION_PATH . '/models/dbengine/CapCreditDB.php';

class PiaoliuHK_Models_Core_CapCredit
{

    private $CustomerID;

    private $CapCreditTimeStamp;

    private $CapCreditLedger;

    private $CapCreditRemarks;

    public static function initializebyCustomerID ($CustomerID)
    {
        $CapCreditTemp = new PiaoliuHK_Models_Core_CapCredit();
        $CapCreditTemp->setCustomerID($CustomerID);
        return $CapCreditTemp;
    }

    public function setCustomerID ($CustomerID)
    {
        $this->CustomerID = $CustomerID;
    }

    public function getCustomerID ()
    {
        return $this->CustomerID;
    }

    public function setCapCreditTime ($Time)
    {
        $Time = new \DateTime($Time);
        $this->CapCreditTimeStamp = $Time->getTimestamp();
    }

    public function getCapCreditTime ()
    {
        $Time = new \DateTime();
        $Time->setTimestamp($this->CapCreditTimeStamp);
        return $Time->format('Y-m-d H:i:s');
    }

    public function setCapCreditTimeStamp ($StampTime)
    {
        $this->CapCreditTimeStamp = $StampTime;
    }

    public function getCapCreditTimeStamp ()
    {
        return $this->CapCreditTimeStamp;
    }

    public function setCapCreditLedger ($Ledger)
    {
        $this->CapCreditLedger = $Ledger;
    }

    public function getCapCreditLedger ()
    {
        return $this->CapCreditLedger;
    }

    public function setCapCreditRemarks ($Remarks)
    {
        $this->CapCreditRemarks = $Remarks;
    }

    public function getCapCreditRemarks ()
    {
        return $this->CapCreditRemarks;
    }
}

?>