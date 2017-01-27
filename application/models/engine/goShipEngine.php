<?php
namespace models\engine;
use models\core\PiaoliuHK_Models_Core_PackageList;
use models\core\PiaoliuHK_Models_Core_Package;
require_once APPLICATION_PATH . '/models/core/PackageList.php';
require_once APPLICATION_PATH . '/models/core/Package.php';

class PiaoliuHK_Models_Engine_goShipEngine
{

    public static function getFareArraybyPackageIDArray ($PackageIDArray)
    {
        $FareArray = array();
        foreach ($PackageIDArray as $Value) {
            $PackageTemp = PiaoliuHK_Models_Core_PackageList::findPackagebyID(
                    $Value);
            if ($PackageTemp instanceof PiaoliuHK_Models_Core_Package) {
                $PackageFare = $PackageTemp->getPackageFare();
                array_push($FareArray, $PackageFare);
            }
        }
        return $FareArray;
    }

    public static function getFeeArraybyPackageIDArray ($PackageIDArray)
    {
        $FeeArray = array(
                '10',
                '10',
                '26'
        );
        return $FeeArray;
    }
}