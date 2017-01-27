<?php
namespace models\form;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_UserType;

class PiaoliuHK_Models_Form_registerForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'UserID');
        $this->addElement('text', 'UserName');
        $this->addElement('text', 'Password');
        $this->addElement('text', 'confirmPassword');
        $this->addElement('radio', 'UserType', 
                array(
                        'multioptions' => array(
                                PiaoliuHK_Configs_GlobalConstant_UserType::Customer => 'Customer',
                                PiaoliuHK_Configs_GlobalConstant_UserType::Admin => 'Admin'
                        )
                ));
        $this->addElement('submit', 'register');
    }
}

?>