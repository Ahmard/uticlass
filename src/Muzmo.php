<?php
namespace Uticlass;

use Uticlass\Audio\Muzmo\Rename;
use Symfony\Component\Console\Output\ConsoleOutput; 
use Symfony\Component\Console\Helper\ProgressBar;

class Muzmo
{
    use Eve
    public static function rename($folderPath) 
    {
        console()->comment('Renaming files...')->newLine();
        (new Rename($folderPath))
            ->onSuccess(function($file){
                console()->info($file);
            })->onError(function($file){
                console()->error($file);
            })->onFinish(function(){
                console()->comment('Renaming finished.');
            })->rename();
        console()->newLine();
    }
}