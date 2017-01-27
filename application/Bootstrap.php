<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload ()
    {
        $autoloader = new Zend_Loader_Autoloader_Resource(
                array(
                        'basePath' => APPLICATION_PATH . '/models/',
                        'namespace' => 'PiaoliuHK'
                ));
        
        $autoloader->addResourceType('model', 'models', 'Model');
        return $autoloader;
    }
}

