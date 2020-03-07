<?php namespace Concrete\Package\TallacmansCv;

use Package;
use BlockType;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_cv';
    protected $appVersionRequired = '8.0.0';
    protected $pkgVersion = '0.9.0';

    public function getPackageName()
    {
        return t('Tallacmans CV');
    }

    public function getPackageDescription()
    {
        return t('An easy way to create your curriculum vitae');
    }

    public function install()
    {
        $pkg = parent::install();
      	$btHandles = array (
         'tallacmans_cv',
      );
	    foreach($btHandles as $btHandle){
	        if (!BlockType::getByHandle($btHandle)) {
                BlockType::installBlockType($btHandle, $pkg);
            }
	    }
    }
}
