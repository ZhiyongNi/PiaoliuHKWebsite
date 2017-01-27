<?php
namespace models\form;

class PiaoliuHK_Models_Form_signTransitBillForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'TransitBillID');
        $this->addElement('text', 'VerificationCode');
        $this->addElement('submit', 'Verify');
    }
}

?>