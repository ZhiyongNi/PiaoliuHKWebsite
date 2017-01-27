<?php
namespace models\form;

class PiaoliuHK_Models_Form_TransitBillListForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('submit', 'detail');
    }

    public function setTransitBillIDArray (Array $TransitBillIDArray)
    {
        $this->addElement('radio', 'TransitBillIDR', 
                array(
                        'multioptions' => $TransitBillIDArray
                ));
    }
}

?>