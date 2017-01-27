<?php
namespace models\form;

class PiaoliuHK_Models_Form_searchForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'PackageID');
        $this->addElement('text', 'TransitBillID');
        $this->addElement('radio', 'SearchObject', 
                array(
                        'multioptions' => array(
                                0 => 'Package',
                                1 => 'TransitBill'                         
                        )
                ));
        $this->addElement('submit', 'register');
    }
}

?>