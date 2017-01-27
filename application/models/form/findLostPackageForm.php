<?php
namespace models\form;

class PiaoliuHK_Models_Form_findLostPackageForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'Channel');
        $this->addElement('text', 'ExpressCompany');
        $this->addElement('text', 'PackageExpressTrackNumber');
        $this->addElement('text', 'ArriveTime');
        $this->addElement('text', 'PackageValue');
        $this->addElement('submit', 'register');
    }
}

?>