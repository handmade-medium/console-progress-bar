# Console Progress Bar
Provides a progress bar for console applications

* **ConsoleProgressBar** = Generic PHP Cli Progress Bar
* **DrushProgressBar** = Customized for use in a Drush console application

## Usage

```php
// width of the left title column
$colWidth = 50;
```
```php
// size of bar
$size = ConsoleProgressBar::SIZE_MEDIUM;
```
```php
// max value of loop
$max = 100;
```
```php
// text for column
$text = "Test";
```
```php
// init
$progressBar = new ConsoleProgressBar($max, $size, $text, $colWidth);
```
```php
// loop
for($x=1;$x<=$max;$x++) {
$progressBar->showProgress($x);
}
```
```php
// end
$progressBar->showComplete();
```

    
    
## Examples

see examples/consoleExample.php

```php
> php examples/consoleExample.php
```
```php
Example Spinner      [X] 100%
Example Small Bar    [=========================] 100%
Example Medium Bar   [==================================================] 100%
Example Large Bar    [====================================================================================================] 100%
Custom Bar           [##################################################] 100%
Custom Spinner       [X] 100%
```

## v1.0.2

*   Added Tests for Drush 6 .. 8
*   Added Test for Console
*   Added Custom bar and Custom Spinner options
*   Fixed Large Bar bug - do not exceed viewport width

## v1.0

*   Initial Release


