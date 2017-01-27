<?php
namespace models\logistics;

class PiaoliuHK_Models_Logistics_TransitBillTrackInfo
{

    public $TransitBillID;

    public $TransitBillTrackData;

    public $TransitBillUpdateTimeStamp;

    public $TransitBillTrackStatus;
    
    public function getTransitBillID ()
    {
    	return $this->TransitBillID;
    }
    
    public function setTransitBillID ($ID)
    {
    	$this->TransitBillID = $ID;
    }
    
    public function getTransitBillExpressCompany ()
    {
    	return $this->TransitBillExpressCompany;
    }
    
    public function getTransitBillTrackData ()
    {
    	return $this->TransitBillTrackData;
    }
    
    public function setTransitBillTrackData ($Data)
    {
    	$this->TransitBillTrackData = $Data;
    }
    
    public function getTransitBillUpdateTimeStamp ()
    {
    	return $this->TransitBillUpdateTimeStamp;
    }
    
    public function setTransitBillUpdateTimeStamp ($TimeStamp)
    {
    	$this->TransitBillUpdateTimeStamp = $TimeStamp;
    }
    
    public function getTransitBillTrackStatus ()
    {
    	return $this->TransitBillTrackStatus;
    }
    
    public function setTransitBillTrackStatus ($Status)
    {
    	$this->TransitBillTrackStatus = $Status;
    }
}

?>