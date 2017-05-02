<?php
namespace HandmadeMedium\ProgressBar;

/**
 * Class DrushProgressBar
 * @package HandmadeMedium\ProgressBar
 * Methods designed for Drush output
 */
class DrushProgressBar extends BaseProgressBar  {
    
    /**
     * @inheritdoc
     */
    public function __construct($totalCount, $size=parent::SIZE_MEDIUM, $title=null, $titleColumnWidth=null)
    {
        if (!function_exists('drush_print')) {
            throw new \BadFunctionCallException("Method 'drush_print' is not available. DrushProgressBar requires that Drush is installed and bootstrapped");
        }
        parent::__construct($totalCount,$size,$title,$titleColumnWidth);
    }
    
    /**
     * Designed for Drush Console Output
     * @inheritdoc
     */
    public function showProgress($count) {
        drush_print($this->getProgress($count), 0, null, false);
    }
    
    
    /**
     * Designed for Drush Console Output
     * @inheritdoc
     */
    public function showComplete($finalMessage=null) {
        drush_print($this->getComplete($finalMessage), 0, null, false);
    }
    
}