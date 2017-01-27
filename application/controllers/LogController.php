<?php
use models\core\PiaoliuHK_Models_Core_User;
use models\form\PiaoliuHK_Models_Form_AdminloginForm;
use models\form\PiaoliuHK_Models_Form_loginForm;
use models\form\PiaoliuHK_Models_Form_registerForm;
use models\core\PiaoliuHK_Models_Core_Customer;
use models\core\PiaoliuHK_Models_Core_Admin;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult;
use configs\globalconstant\PiaoliuHK_Configs_GlobalConstant_UserType;
/**
 * NewsController
 *
 * @author *
 *        
 *        
 * @version *
 *         
 *         
 */

require_once 'Zend/Controller/Action.php';
require_once APPLICATION_PATH . '/models/core/User.php';
require_once APPLICATION_PATH . '/models/core/Customer.php';
require_once APPLICATION_PATH . '/models/core/Admin.php';
require_once APPLICATION_PATH . '/models/form/loginForm.php';
require_once APPLICATION_PATH . '/models/form/registerForm.php';
require_once APPLICATION_PATH . '/models/form/AdminloginForm.php';

class LogController extends Zend_Controller_Action
{

    function init ()
    {
        $this->initView();
    }

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated NewsController::indexAction() default action
        $this->_redirect('/log/login');
    }

    public function loginAction ()
    {}

    public function loginformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $loginForm = new PiaoliuHK_Models_Form_loginForm();
            if ($loginForm->isValid($FormData)) {
                $AuthResult = PiaoliuHK_Models_Core_User::AuthUserbyNameandPassword(
                        $loginForm->getValue('UserName'), 
                        md5($loginForm->getValue('Password'), FALSE));
                
               switch ($AuthResult["AuthResult"]) {
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Right:
                        $CustomerSession = new Zend_Session_Namespace(
                                'CustomerSession');
                        $CustomerSession->CustomerID = $AuthResult["UserID"];
                        $this->_redirect('/workspace');
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Wrong:
                        $this->_redirect('/log/login');
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::None:
                        $this->_redirect('/log/register');
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function logoutAction ()
    {
        $CustomerSession = new Zend_Session_Namespace('CustomerSession');
        $CustomerSession->unsetAll();
        $this->_redirect('http://www.kindex.com/');
    }

    public function registerAction ()
    {
        $this->view->UserType = array(
                "Customer" => PiaoliuHK_Configs_GlobalConstant_UserType::Customer,
                "Admin" => PiaoliuHK_Configs_GlobalConstant_UserType::Admin
        );
    }

    public function registerformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $loginForm = new PiaoliuHK_Models_Form_registerForm();
            if ($loginForm->isValid($FormData)) {
                $Userlogin = new PiaoliuHK_Models_Core_User();
                $Userlogin->setUserName($loginForm->getValue('UserName'));
                if (strcmp($loginForm->getValue('Password'), 
                        $loginForm->getValue('confirmPassword')) == 0) {
                    $Userlogin->setUserPassword(
                            md5($loginForm->getValue('Password'), FALSE));
                    $Userlogin->setType($loginForm->getValue('UserType'));
                    $Userlogin->setUserID($Userlogin->addUsertoDB());
                    switch ($loginForm->getValue('UserType')) {
                        case PiaoliuHK_Configs_GlobalConstant_UserType::Customer:
                            $Customer = new PiaoliuHK_Models_Core_Customer(
                                    $Userlogin);
                            $Customer->addCustomertoDB();
                            $this->_redirect('/log/login');
                            break;
                        case PiaoliuHK_Configs_GlobalConstant_UserType::Admin:
                            $Admin = new PiaoliuHK_Models_Core_Admin($Userlogin);
                            $Admin->addAdmintoDB();
                            $this->_redirect('/log/adminlogin');
                            break;
                        default:
                            break;
                    }
                } else {
                    echo '密码不匹配';
                }
            }
        }
    }

    public function adminloginAction ()
    {}

    public function adminloginformAction ()
    {
        if ($this->_request->isPost()) {
            $FormData = $this->_request->getPost();
            $AdminloginForm = new PiaoliuHK_Models_Form_AdminloginForm();
            if ($AdminloginForm->isValid($FormData)) {
                $AuthResult = PiaoliuHK_Models_Core_User::AuthUserbyNameandPassword(
                        $AdminloginForm->getValue('UserName'), 
                        md5($AdminloginForm->getValue('Password'), FALSE));
                switch ($AuthResult["AuthResult"]) {
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Right:
                        $AdminSession = new Zend_Session_Namespace(
                                'AdminSession');
                        $AdminSession->AdminID = $AuthResult["UserID"];
                        $this->_redirect('/admin');
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::Wrong:
                        $this->_redirect('/log/adminlogin');
                        break;
                    case PiaoliuHK_Configs_GlobalConstant_PasswordAuthResult::None:
                        $this->_redirect('/log/register');
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
