<?php
namespace HandmadeMedium\ProgressBar\TestLib;

class ComposerUtil {
    
    public static function vendorPackageIsLoaded($composerJsonPath, $vendorPackage, $versionRegex, $checkDev=false) {
        if (file_exists($composerJsonPath)) {
           $composerJson = json_decode(file_get_contents($composerJsonPath), true);
            if (array_key_exists($vendorPackage, $composerJson['require'])) {
                if (preg_match($versionRegex,$composerJson['require'][$vendorPackage] )) {
                    return true;
                }
            }
            elseif ($checkDev && array_key_exists($vendorPackage, $composerJson['require-dev'])) {
                if (preg_match($versionRegex,$composerJson['require-dev'][$vendorPackage] )) {
                    return true;
                }
            }
        }
        return false;
    }
}