<?php

namespace Gurpreetsinghin\VaultsSecurity\Traits\Lib;

use Gurpreetsinghin\VaultsSecurity\Traits\Lib\UserAgentPSec;
use Gurpreetsinghin\VaultsSecurity\Traits\Lib\UserAgentDetectos;
use Gurpreetsinghin\VaultsSecurity\Traits\Lib\UserAgentDetectbrowser;

class UserAgentFactoryPSec
{
    public static function analyze($string, $imageSize = null, $imagePath = null, $imageExtension = null)
    {
        $class = new UserAgentPSec();
        $imageSize === null || $class->imageSize = $imageSize;
        $imagePath === null || $class->imagePath = $imagePath;
        $imageExtension === null || $class->imageExtension = $imageExtension;
        $class->analyze($string);
        
        return $class;
    }
}