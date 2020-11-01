<?php

namespace Uticlass\Audio\Muzmo;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\ConsoleOutput;

class Rename
{

    protected $folderPath;

    protected $onStartClosure, $onRenameClosure, $onFinishClosure;

    protected $onSuccessClosure, $onErrorClosure;


    public function __construct($folderPath)
    {
        $this->folderPath = $folderPath;
    }

    public function rename($folderPath = null)
    {
        //Invoke onStart event
        $onStartClosure = $this->onStartClosure;
        $onSuccessClosure = $this->onSuccessClosure;
        $onErrorClosure = $this->onErrorClosure;
        $onRenameClosure = $this->onRenameClosure;
        $onFinishClosure = $this->onFinishClosure;

        $totalFiles = Storage::files($this->folderPath);
        if ($onStartClosure) {
            $onStartClosure(count($totalFiles));
        }
        $output = console();

        $folderPath ??= $this->folderPath;
        $dir = opendir($folderPath);
        $c = 0;

        while ($file = readdir($dir)) {
            if ($onRenameClosure) {
                $onRenameClosure();
            }
            if (strlen($file) > 3) {

                $oldfile = "$folderPath/$file";

                //Remove "muzmo_ru"
                $file = $this->removeMuzmoName($file);

                $removeId = $this->removeMuzmoId($file, $folderPath);
                if ($removeId) {
                    $newfile = "$folderPath/$removeId";

                    if (rename($oldfile, $newfile)) {
                        if ($onSuccessClosure) {
                            $onSuccessClosure($file);
                        }
                    } else {
                        if ($onErrorClosure) {
                            $onErrorClosure($file);
                        }
                    }
                }
            }
            $c++;
        }
        if ($onFinishClosure) {
            $onFinishClosure();
        }
    }

    public function removeMuzmoName($file)
    {
        if ($file) {

            if (strstr($file, "muzmo_ru_")) {
                $exp = explode("muzmo_ru_", $file);
                return $exp[1] ?? $exp[0];
            }
        }
    }

    public function removeMuzmoId($file)
    {
        if ($file) {

            //Remove muzmo id
            $exp1 = explode("_", $file);
            $count1 = ((count($exp1)) - 1);

            $exp2 = explode(".", $exp1[$count1]);
            $muzmo_file_id = $exp2[0];

            $oldLen = strlen($muzmo_file_id);

            if (settype($muzmo_file_id, 'int')) {
                if (strlen($muzmo_file_id) == $oldLen) {

                    $loopCount = 0;
                    $mCount = $count1 - 1;

                    $name = "";
                    while (1) {
                        $uScore = (($loopCount != 0) ? ("_") : (""));
                        $name .= $uScore . $exp1[$loopCount];
                        if ($loopCount == $mCount) {
                            $newfile = "$name." . $exp2[1] . "";
                            return $newfile;
                            break;
                        }
                        $loopCount++;
                    }
                }
            }
        }
    }

    public function onSuccess($closure)
    {
        $this->onSuccessClosure = $closure;
        return $this;
    }


    public function onError($closure)
    {
        $this->onErrorClosure = $closure;
        return $this;
    }


    public function onStart($closure)
    {
        $this->onStartClosure = $closure;
        return $this;
    }


    public function onRename($closure)
    {
        $this->onRenameClosure = $closure;
        return $this;
    }


    public function onFinish($closure)
    {
        $this->onFinishClosure = $closure;
        return $this;
    }
}