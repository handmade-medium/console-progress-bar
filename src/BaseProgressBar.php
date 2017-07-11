<?php
namespace HandmadeMedium\ProgressBar;
use CLI\Cursor;

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
    /** @var bool $isInitialized Flag to indicate the class is initialized on first progress caller */
    protected $isInitialized = false;
    /** @var string $bar The default bar character */
    protected $bar = "=";
    /** @var string $spinners The default spinner characters */
    protected $spinners = "=>}|{<=-\\|/- ";
    /** @var int $maxbarsValue The maximum number of bars possible considering the console width */
    protected $maxbarsValue;

    /** Spinner Effect */
    const SIZE_SPINNER = 8;
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
        $this->size = (in_array($size, [1,2,4,8])) ? $size : self::SIZE_MEDIUM;
        $this->title = (empty($title)) ? $this->defaultTitle : $title;
        $this->titleColumnWidth = (empty($titleColumnWidth)) ? (strlen($this->title) + 1) : $titleColumnWidth;
    }
    
    public function setBarCharacter($char) {
        if (!empty($char) && is_string($char)) {
            $this->bar = substr($char, 0, 1);
            return;
        }
        throw new \InvalidArgumentException("Bar character not provided");
    }
    
    public function setSpinnerCharacters($chars) {
        if (!empty($chars) && is_string($chars)) {
            $this->spinners = $chars;
            return;
        }
        throw new \InvalidArgumentException("Spinner characters not provided");
    }
    
    private function _init() {
        if ($this->isInitialized) return;
        if ($this->size == self::SIZE_SPINNER) {
            $spinners = str_split($this->spinners);
            // multiply spinners in relation to the total for a more even animation
            array_walk($spinners, function($value, $key, $totalCount) {
                $i = 0;
                while ($i++ < ($totalCount / 25)) {
                    $this->spinnerChars[] = $value;
                }
            }, $this->totalCount);
        }
        if (!$this->_isWindows()) {
            fwrite(STDERR, "\033[?25l"); // hides the cursor
        }
        $this->isInitialized = true;
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
    
    
    
    private function _isWindows() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return true;
        }
        return false;
    }
    
    /**
     * Determine the maximum number of bars according to the terminal width
     */
    private function _getMaxBars() {
        if ($this->maxbarsValue) {
            return $this->maxbarsValue;
        }
        $maxBars = 100 / $this->size;
        /**
         * output cannot exceed the total width of the terminal
         * $maxBars, titleColumnWidth, spacing ' [ ] ' = 4, '100%' = 4, \r = 1
         */
        $maxTotalWidth = $maxBars + $this->titleColumnWidth + 4 + 4 + 1;
    
        if ($this->_isWindows()) { // get Windows terminal width
            exec('mode con', $terminalWidth);
            $terminalWidth = preg_replace('/^(.+Columns:\s+)([0-9]+)(.+)$/si', "$2", implode("\n", $terminalWidth));
        } else {
            $terminalWidth = `tput cols`;
        }
    
        if ($terminalWidth < $maxTotalWidth) {
            $maxBars -= ($maxTotalWidth - $terminalWidth);
        }
        $this->maxbarsValue = $maxBars;
        return $maxBars;
    }
    
    private function _getProgressBar($progress) {
        $maxBars = $this->_getMaxBars();
        $bars = str_repeat($this->bar,floor(($maxBars / 100) * $progress ));
        $barspad = $maxBars - $bars;
    
        if ($progress < 100) {
            $bars .= ">";
        }
        return sprintf("%s [%s] %s%%\r",
            substr(str_pad($this->title, $this->titleColumnWidth), 0, $this->titleColumnWidth),
            str_pad($bars, $barspad),
            str_pad($progress, 3, ' ', STR_PAD_LEFT)
        );
    }
    
    private $spinnerChars = [];
    
    private function _getSpinner($progress) {
        if ($progress == 100) {
            $spinChar = 'X';
        } else {
            $spinChar = array_shift($this->spinnerChars);
            array_push($this->spinnerChars, $spinChar);
            reset($this->spinnerChars);
        }
        return sprintf("%s [%s] %s%%\r",
            substr(str_pad($this->title, $this->titleColumnWidth), 0, $this->titleColumnWidth),
            $spinChar,
            str_pad($progress, 3, ' ', STR_PAD_LEFT)
        );
    }
    
    /**
     * Get progress of the progress bar
     * @param int $count The current value to compare against the Total Count
     * @return string
     */
    protected function getProgress($count) {
        if ($this->isComplete) return null;
        if (!$this->isInitialized) { $this->_init(); }
        $progress = floor(($count / $this->totalCount) * 100);
        if ($this->size == self::SIZE_SPINNER) {
            return $this->_getSpinner($progress);
        } else {
            return $this->_getProgressBar($progress);
        }
    }
    
    /**
     * Returns the final output, or purges the progress bar.
     * Sets a flag to prevent further output.
     * Always adds a newline.
     * @param string:null $finalMessage If defined, printed in place of and removes the Progress bar when complete. Defaults to Null.
     * @return string
     */
    protected function getComplete($finalMessage=null) {
        if ($this->isComplete) return null;
        $this->isComplete = true;
        if (!empty($finalMessage)) {
            $this->title = $finalMessage;
        }
        if (!$this->_isWindows()) {
            fwrite(STDERR, "\033[?25h\033[?0c"); // shows the cursor
        }
        if ($this->size == self::SIZE_SPINNER) {
            return $this->_getSpinner(100) . "\n";
        } else {
            return $this->_getProgressBar(100) . "\n";
        }
    }
    
}