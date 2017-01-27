<?php
namespace models\form;

class PiaoliuHK_Models_Form_checkoutTransitForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');        
      
        $this->addElement('submit', 'checkout');
    }

    public function setShipTransitBillID (Array $TransitBillIDArray)
    {
        $area = $this->addElement('multiCheckbox', 'ShipTransitBillID', 
                array(
                        'multioptions' => $TransitBillIDArray
                ));
    }
}

?>