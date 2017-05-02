<?php
namespace HandmadeMedium\ProgressBar\Tests;
use HandmadeMedium\ProgressBar\ConsoleProgressBar;
use HandmadeMedium\ProgressBar\TestLib\ReflectionUtil;
use PHPUnit\Framework\TestCase;

class ConsoleProgressBarTest extends TestCase {
    
    /** @var int $maxValue */
    protected $maxValue;
    /** @var ConsoleProgressBar $consoleProgressBar */
    protected $consoleProgressBar;
    
    protected function setUp() {
        $this->maxValue = 100;
        $this->consoleProgressBar = new ConsoleProgressBar($this->maxValue, 2, "Test", 25);
    }
    
    public function tearDown() {
    
    }
    
    /**
     * @outputBuffering enabled
     */
    public function testGetProgress() {
        for($x=10;$x<=90;$x+=10) {
            ob_clean();
            $str = ReflectionUtil::callProtectedMethod($this->consoleProgressBar, 'getProgress', [$x]);
            $this->assertNotEmpty($str, "$x " . "Progress not empty");
            $this->assertStringEndsWith("\r", $str, "$x " . 'Progress ends with \r');
            $this->consoleProgressBar->showProgress($x);
            $this->expectOutputString($str);
        }
    }
    
    /**
     * @outputBuffering enabled
     */
    public function testGetComplete() {
        
        // test showComplete
        $str = ReflectionUtil::callProtectedMethod($this->consoleProgressBar, 'getComplete', ["Complete"]);
        $this->assertStringEndsWith("\n", $str, 'Complete ends with \n');
        
        // this was set to 'true' when we called 'getComplete', reset it
        ReflectionUtil::setProtectedProperty($this->consoleProgressBar, 'isComplete', false);
        
        $this->consoleProgressBar->showComplete("Complete");
        $this->expectOutputString($str);
    }
    
    public function testPostComplete() {
    
        // test no more output
        $this->expectOutputString(null);
        $this->consoleProgressBar->showProgress(10);
        
        $this->expectOutputString(null);
        $this->consoleProgressBar->showComplete($this->maxValue);
    }

}


