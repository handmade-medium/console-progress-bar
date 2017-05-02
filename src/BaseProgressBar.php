<?php
namespace HandmadeMedium\ProgressBar;

abstract class BaseProgressBar {
    
    /** @var int $totalCount The total number of items to measure progress for */
    protected $totalCount;
    /** @var string|null $title The Display title of the bar */
    protected $title;
    /** @var int|null $titleColumnWidth When multiple progres bars are required, keeps the titles in a consistent column width. Defaults to the width of the title */
    protected $titleColumnWidth;
    /** @var int $size The Size of the progress bar. Defaults to Medium. See Constants for details. */
    protected $size;
    /** @var string $defaultTitle The default title if none is provided */
    protected $defaultTitle = "Progress";
    /** @var bool $completed Flag to indicate the progress bar is complete to prevent further output */
    protected $isComplete = false;
    
    /**
     * @param string $defaultTitle
     */
    public function setDefaultTitle($defaultTitle) {
        $this->defaultTitle = $defaultTitle;
    }
    
    /** Small width = 100/4 or 25 pixels */
    const SIZE_SMALL = 4;
    /** Medium width = 100/2 or 50 pixels */
    const SIZE_MEDIUM = 2;
    /** Large width = 100/1 or 100 pixels */
    const SIZE_LARGE = 1;
    
    /**
     * Progress Bar constructor.
     * @param int $totalCount           The total number of items to measure progress for
     * @param int $size                 The Size of the progress bar. Options: Large(1) 100 chars, Medium(2) 50 chars, Small(4) 25 chars. Defaults to Medium(2) or 50 characters. See SIZE Constants for details.
     * @param null $title               The Display title of the bar. Defaults to string 'Progress'
     * @param null $titleColumnWidth    When multiple progress bars are required, keeps the titles in a consistent column width. Defaults to the width of the title + 5
     */
    public function __construct($totalCount, $size=self::SIZE_MEDIUM, $title=null, $titleColumnWidth=null)
    {
        $this->totalCount = $totalCount;
        $this->size = (in_array($size, [1,2,4])) ? $size : self::SIZE_MEDIUM;
        $this->title = (empty($title)) ? $this->defaultTitle : $title;
        $this->titleColumnWidth = (empty($titleColumnWidth)) ? (strlen($this->title) + 5) : $titleColumnWidth;
    }
    
    /**
     * Show progress of the progress bar
     * @param int $count The current value to compare against the Total Count
     * @return void
     */
    public abstract function showProgress($count);
    
    /**
     * Displays the final output, or purges the progress bar.
     * Sets a flag to prevent further output.
     * Always adds a newline.
     * @param string:null $finalMessage If defined, printed in place of and removes the Progress bar when complete. Defaults to Null.
     * @return string
     */
    public abstract function showComplete($finalMessage=null);
    
    /**
     * Get progress of the progress bar
     * @param int $count The current value to compare against the Total Count
     * @return string
     */
    protected function getProgress($count) {
        if ($this->isComplete) return null;
        $progress = floor(($count / $this->totalCount) * 100);
        $bars = str_repeat('=',floor($progress / $this->size));
        $barspad = 100/$this->size;
        if ($progress < 100) {
            $bars .= ">";
        }
        return sprintf("%s [%s] %s%%\r",
            substr(str_pad($this->title, $this->titleColumnWidth), 0, $this->titleColumnWidth),
            str_pad($bars, $barspad),
            str_pad($progress, 3, ' ', STR_PAD_LEFT)
        );
    }
    
    /**
     * Displays the final output, or purges the progress bar.
     * Sets a flag to prevent further output.
     * Always adds a newline.
     * @param string:null $finalMessage If defined, printed in place of and removes the Progress bar when complete. Defaults to Null.
     * @return string
     */
    protected function getComplete($finalMessage=null) {
        if ($this->isComplete) return null;
        if (!empty($finalMessage)) {
            $this->title = $finalMessage;
        }
        $response = $this->getProgress($this->totalCount) . "\n";
        $this->isComplete = true;
        return $response;
    }
    
}