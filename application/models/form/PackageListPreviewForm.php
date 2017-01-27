<?php
namespace models\form;

class PiaoliuHK_Models_Form_PackageListPreviewForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('radio', 'SearchPackageType', 
                array(
                        'multioptions' => array(
                                0 => 'Signed',
                                1 => 'inTransit',
                                2 => 'Checkout',
                                3 => 'Waiting',
                                4 => 'Unmatched',
                                5 => 'Lost',
                                6 => 'Reservation'
                        )
                ));
        $this->addElement('submit', 'search');
    }
}

?>