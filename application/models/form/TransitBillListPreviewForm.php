<?php
namespace models\form;

class PiaoliuHK_Models_Form_TransitBillListPreviewForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('radio', 'SearchTransitBillType', 
                array(
                        'multioptions' => array(
                                0 => 'Signed',
                                1 => 'inShip',
                                2 => 'Checkout'                             
                        )
                ));
        $this->addElement('submit', 'search');
    }
}

?>