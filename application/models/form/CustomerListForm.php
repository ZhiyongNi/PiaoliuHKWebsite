<?php
namespace models\form;

class PiaoliuHK_Models_Form_CustomerListForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('submit', 'register');
    }

    public function setCustomerIDArray (Array $CustomerIDArray)
    {
        $this->addElement('radio', 'CustomerIDR', 
                array(
                        'multioptions' => $CustomerIDArray
                ));
    }
}

?>