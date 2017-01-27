<?php
namespace models\form;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageChannel;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany;

class PiaoliuHK_Models_Form_reservePackageForm extends \Zend_Form
{

    public function __construct ()
    {
        $this->setMethod('post');
        $this->addElement('select', 'PackageExpressCompany', 
                array(
                        'multioptions' => array(
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShunfengExpress => '顺丰快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YuantongExpress => '圆通快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZhongtongExpress => '中通快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YundaExpress => '韵达快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ShentongExpress => '申通快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::EMSExpress => 'EMS快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuitongExpress => '汇通快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::TiantianExpress => '天天快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::YousuExpress => '优速快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::JingdongExpress => '京东快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::GuotongExpress => '国通快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::LongbangExpress => '龙邦快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::SuerExpress => '速尔快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::HuiqiangExpress => '汇强快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::ZJSExpress => '宅急送快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::QuanfengExpress => '全峰快递',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::DangdangExpress => '当当物流',
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::AmazonExpress => '亚马逊物流',
                                
                                PiaoliuHK_Configs_GlobalConstant_PackageExpressCompany::Unknown => '未知'
                        )
                ));
        $this->addElement('text', 'PackageExpressTrackNumber');
        $this->addElement('select', 'PackageChannel', 
                array(
                        'multioptions' => array(
                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::TaoBaoWEB => '淘宝网/天猫',
                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::JingDongWEB => '京东商城',
                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::DangdangWEB => '当当网',
                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::AmazonWEB => '亚马逊',
                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::VIPWEB => '唯品会',

                                PiaoliuHK_Configs_GlobalConstant_PackageChannel::Unknown => '未知'
                        )
                ));
        /*
         * $this->addElement('checkbox', 'PackageExpressTrackSwitch', array(
         * 'CheckedValue' => 'PackageExpressTrackSwitch' ));
         */
        
        $this->addElement('textArea', 'Remarks');
        $this->addElement('submit', 'register');
    }
}

?>