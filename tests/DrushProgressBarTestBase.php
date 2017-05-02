<?php
namespace HandmadeMedium\ProgressBar\Tests;
use HandmadeMedium\ProgressBar\DrushProgressBar;
use HandmadeMedium\ProgressBar\TestLib\ReflectionUtil;
use PHPUnit\Framework\TestCase;

// This is all we need to test drush_print
require_once(__DIR__ . '/../vendor/drush/drush/includes/context.inc');
require_once(__DIR__ . '/../vendor/drush/drush/includes/output.inc');

class DrushProgressBarTestBase extends TestCase {
    
    /** @var int $maxValue */
    protected $maxValue;
    /** @var DrushProgressBar $drushProgressBar */
    protected $drushProgressBar;
    
    public function setUp() {
        $this->maxValue = 100;
        $this->drushProgressBar = new DrushProgressBar($this->maxValue, 2, "Test", 25);
    }
    
    public function tearDown() {
        
    }
    
    public function testGetProgress() {
        for($x=10;$x<=90;$x+=10) {
            ob_clean();
            $str = ReflectionUtil::callProtectedMethod($this->drushProgressBar, 'getProgress', [$x]);
            $this->assertNotEmpty($str, "$x " . "Progress not empty");
            $this->assertStringEndsWith("\r", $str, "$x " . 'Progress ends with \r');
            $this->expectOutputString($str);
            $this->drushProgressBar->showProgress($x);
        }
    }
    
    public function testGetComplete() {
        
        // test showComplete
        $str = ReflectionUtil::callProtectedMethod($this->drushProgressBar, 'getComplete', ["Complete"]);
        $this->assertStringEndsWith("\n", $str, 'Complete ends with \n');
        
        // this was set to 'true' when we called 'getComplete', reset it
        ReflectionUtil::setProtectedProperty($this->drushProgressBar, 'isComplete', false);
        
        $this->expectOutputString($str);
        $this->drushProgressBar->showComplete("Complete");
        
    }
    
    public function testPostComplete() {
        
        // test no more output
        $this->expectOutputString(null);
        $this->drushProgressBar->showProgress(10);
        
        $this->expectOutputString(null);
        $this->drushProgressBar->showComplete($this->maxValue);
        
    }
    
}


