<?php
namespace HandmadeMedium\ProgressBar;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Class Drupal8DrushProgressBar
 * @package HandmadeMedium\ProgressBar
 * Methods designed for Drush output under Drupal 8
 */
class Drupal8DrushProgressBar extends BaseProgressBar  {
    
    /**
     * @inheritdoc
     */
    public function __construct($totalCount, $size=parent::SIZE_MEDIUM, $title=null, $titleColumnWidth=null)
    {
        parent::setDefaultTitle(new TranslatableMarkup("Progress"));
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