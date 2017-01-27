<?php
namespace models\form;

class PiaoliuHK_Models_Form_registerPackageForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'PackageOwnerID');
        $this->addElement('text', 'PackageOwnerMobile');
        $this->addElement('text', 'PackageExpressCompany');
        $this->addElement('text', 'PackageExpressTrackNumber');
        $this->addElement('text', 'PackageSnapshot');
        $this->addElement('text', 'PackageWeight');
        $this->addElement('text', 'PackageFare');
        $this->addElement('text', 'PackageInTime');
        $this->addElement('text', 'PackageOutTime');
        $this->addElement('submit', 'register');
    }
}

?>