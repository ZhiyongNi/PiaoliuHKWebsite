<?php
namespace models\form;

class PiaoliuHK_Models_Form_CustomerDetailFrontform extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'CustomerID');
    }
}

?>