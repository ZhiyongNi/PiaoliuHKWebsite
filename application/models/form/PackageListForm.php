<?php
namespace models\form;

class PiaoliuHK_Models_Form_PackageListForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('submit', 'detail');
    }

    public function setPackageIDArray (Array $PackageIDArray)
    {
        $this->addElement('radio', 'PackageIDR', 
                array(
                        'multioptions' => $PackageIDArray
                ));
    }
}

?>