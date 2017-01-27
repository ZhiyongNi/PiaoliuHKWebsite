<?php
namespace models\form;

class PiaoliuHK_Models_Form_AdminloginForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'UserName');
        $this->addElement('text', 'Password');
        $this->addElement('submit', 'login');
    }
}

?>