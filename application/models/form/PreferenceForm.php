<?php
namespace models\form;

class PiaoliuHK_Models_Form_PreferenceForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('text', 'CustomerName');
        $this->addElement('checkbox', 'CustomerNameCB', 
                array(
                        'CheckedValue' => 'CustomerNameCB'
                ));
        $this->addElement('text', 'CustomerCollage');
        $this->addElement('checkbox', 'CustomerCollageCB', 
                array(
                        'CheckedValue' => 'CustomerCollageCB'
                ));
        $this->addElement('text', 'CustomerSelfMobile');
        $this->addElement('checkbox', 'CustomerSelfMobileCB', 
                array(
                        'CheckedValue' => 'CustomerSelfMobileCB'
                ));
        $this->addElement('select', 'CustomerSelfAddress_Province', 
                array(
                        'multioptions' => array(
                               0 => '上海市'
                        )
                ));
        $this->addElement('select', 'CustomerSelfAddress_City', 
                array(
                        'multioptions' => array(
                                0 => '上海市'
                        )
                ));
        $this->addElement('select', 'CustomerSelfAddress_District', 
                array(
                        'multioptions' => array(
                                0 => '徐汇区'
                        )
                ));
        $this->addElement('text', 'CustomerSelfAddress_Apartment');
        $this->addElement('checkbox', 'CustomerSelfAddressCB', 
                array(
                        'CheckedValue' => 'CustomerSelfAddressCB'
                ));
        $this->addElement('text', 'CustomerMail');
        $this->addElement('checkbox', 'CustomerMailCB', 
                array(
                        'CheckedValue' => 'CustomerMailCB'
                ));
        
        $this->addElement('text', 'NewPassword');
        $this->addElement('checkbox', 'NewPasswordCB', 
                array(
                        'CheckedValue' => 'NewPasswordCB'
                ));
        
        $this->addElement('text', 'Password');
        $this->addElement('submit', 'register');
    }
}

?>