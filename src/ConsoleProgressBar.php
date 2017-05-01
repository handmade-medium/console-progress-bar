<?php
namespace HandmadeMedium\ProgressBar;

/**
 * Class ConsoleProgressBar
 * @package HandmadeMedium\ProgressBar
 * Generic methods for PHP CLI Output
 */
class ConsoleProgressBar extends BaseProgressBar  {
    
    /**
     * @inheritdoc
     */
    public function __construct($totalCount, $size=parent::SIZE_MEDIUM, $title=null, $titleColumnWidth=null)
    {
        parent::__construct($totalCount,$size,$title,$titleColumnWidth);
    }
    
    /**
     * Generic method for PHP CLI Output
     * @inheritdoc
     */
    public function showProgress($count) {
        print parent::getProgress($count);
    }
    
    
    /**
     * Generic method for PHP CLI Output
     * @inheritdoc
     */
    public function showComplete($finalMessage=null) {
        print parent::getComplete($finalMessage);
    }
    
}