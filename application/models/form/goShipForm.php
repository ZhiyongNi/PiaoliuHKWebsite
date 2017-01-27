<?php
namespace models\form;

class PiaoliuHK_Models_Form_goShipForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        
        $this->addElement('radio', 'TransitBillMethod', 
                array(
                        'multioptions' => array(
                                0 => 'A. 指定时间指定地点，各自学校收货',
                                1 => 'B. 非标准时间来我们仓库收货',
                                2 => 'C. 我们送货上门'
                        )
                ));
        $this->addElement('text', 'TransitBillAddress');
        $this->addElement('text', 'TransitBillSignDate');
        $this->addElement('radio', 'TransitBillSettlement', 
                array(
                        'multioptions' => array(
                                0 => 'A.货到现金支付',
                                1 => 'B.货到银行卡支付',
                                2 => 'C.支付宝在线支付'
                        )
                ));
        $this->addElement('submit', 'register');
    }

    public function setRelatedPackageID (Array $PackageIDArray)
    {
        $area = $this->addElement('multiCheckbox', 'RelatedPackageID', 
                array(
                        'multioptions' => $PackageIDArray
                ));
    }
}

?>