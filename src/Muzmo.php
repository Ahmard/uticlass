<?php
namespace Uticlass;

use Uticlass\Muzmo\Rename;
use Symfony\Component\Console\Output\ConsoleOutput; 
use Symfony\Component\Console\Helper\ProgressBar;

class Muzmo
{
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