<?php
use HandmadeMedium\ProgressBar\ConsoleProgressBar;
use HandmadeMedium\ProgressBar\ConsoleProgressBar as Cp; // const shorthand
require __DIR__."/../vendor/autoload.php";

$titleColumnWidth = 20;
$maxVal = 10000;

$loopFunction = function(ConsoleProgressBar $progressBar) {
    global $maxVal;
    for($x=1;$x<=$maxVal;$x++) {
        $progressBar->showProgress($x);
        usleep(100);
    }
};

printf ("\n\n%1\$s\nConsole Progress Bar Examples\n%1\$s\n\n", str_repeat('-', 64));

print "Defaults\n";
$progressBar = new ConsoleProgressBar($maxVal);
$loopFunction($progressBar);
$progressBar->showComplete();

print "\n\n";

/**
 * Example: Spinner Progress Bar
 */
$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_SPINNER, "Example Spinner", $titleColumnWidth);
$loopFunction($progressBar);
$progressBar->showComplete();

/**
 * Example: Small Progress Bar
 */
$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_SMALL, "Example Small Bar", $titleColumnWidth);
$loopFunction($progressBar);
$progressBar->showComplete();

/**
 * Example: Medium Progress Bar
 */
$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_MEDIUM, "Example Medium Bar", $titleColumnWidth);
$loopFunction($progressBar);
$progressBar->showComplete();

/**
 * Example: Large Progress Bar
 */
$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_LARGE, "Example Large Bar", $titleColumnWidth);
$loopFunction($progressBar);
$progressBar->showComplete();

$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_MEDIUM, "Custom Bar", $titleColumnWidth);
$progressBar->setBarCharacter('#');
$loopFunction($progressBar);
$progressBar->showComplete();

$progressBar = new ConsoleProgressBar($maxVal, Cp::SIZE_SPINNER, "Custom Spinner", $titleColumnWidth);
$progressBar->setSpinnerCharacters('#-');
$loopFunction($progressBar);
$progressBar->showComplete();

print "\n\n";