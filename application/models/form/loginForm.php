<?php
namespace models\form;

class PiaoliuHK_Models_Form_loginForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'UserName');
        $this->addElement('password', 'Password');
        //$this->addElement('submit', 'login');
    }
}

?>