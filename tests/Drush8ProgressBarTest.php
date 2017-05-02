<?php
namespace HandmadeMedium\ProgressBar\Tests;
use HandmadeMedium\ProgressBar\TestLib\ComposerUtil;

class Drush8ProgressBarTest extends DrushProgressBarTestBase {

    public  function setUp() {
        $composerPath = __DIR__.'/../composer.json';
        $vendorPackage = "drush/drush";
        $versionRegex = '/8\.[\d\*x]{1}/';
        $checkDev = true;
        if (!ComposerUtil::vendorPackageIsLoaded($composerPath, $vendorPackage, $versionRegex, $checkDev)) {
            $this->markTestSkipped(
                'Drush 8.x is not loaded.'
            );
        }
        parent::setUp();
        
    }
}


