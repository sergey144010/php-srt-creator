<?php
/**
 * Created by PhpStorm.
 * User: Мария
 * Date: 25.05.2017
 * Time: 23:41
 */

namespace sergey144010\phpSrtCreator;

class SrtService
{
    protected $groups;
    protected $stack;

    public function write()
    {
        $this->stackIsNotNull();
        foreach ($this->stack as $file => $groups) {
            $fileParts = self::splitFileName($file);
            $this->writeFile($fileParts, $groups);
        }
    }

    public function open()
    {
        $files = scandir(__DIR__);
        foreach ($files as $file) {
            if($file == '.' || $file == '..'){ continue; };
            $fileParts = self::splitFileName($file);
            if(self::isSrtTemplate($fileParts)){
                $this->readFile($file);
            };
        };
        return $this;
    }

    public function showGroups()
    {
        var_dump($this->groups);
        return $this;
    }

    public function showOut()
    {
        var_dump($this->stack);
        return $this;
    }

    public function createStack()
    {
        $this->groupsIsNotNull();
        foreach ($this->groups as $group) {
            $this->stack[$group->file][] = $group;
        }
        return $this;
    }

    protected function groupsIsNotNull()
    {
        if(!isset($this->groups)){
            throw new \ErrorException('Groups is NULL');
        }
    }

    protected function stackIsNotNull()
    {
        if(!isset($this->stack)){
            throw new \ErrorException('Stack is NULL');
        }
    }

    private function writeFile($file, $groups)
    {
        $heandle = fopen($file['filename'].'.srt', 'w');
        $count = count($groups);
        foreach ($groups as $key => $group) {
            if(($key+1) != $count){
                $description = self::createDescription($key, $group);
            }else{
                $description = self::createLastDescription($key, $group);
            };
            fwrite($heandle, $description);
        };
        fclose($heandle);
    }

    private function readFile($file)
    {
        $handle = fopen($file, "r");
        $str_before = '';
        $group = null;
        while(!feof($handle))
        {
            $str = fgets($handle);
            $str = trim($str);

            if(!empty($str) && empty($str_before)){
                $group = new Group();
                $group->file = $file;
                $group->time = $str;
            };

            if(!empty($str) && !empty($str_before) && isset($group)){
                $group->description = $str;
                $this->groups[] = $group;
                unset($group);
            };
            $str_before = $str;
        };
        fclose($handle);
    }

    public static function splitFileName($file)
    {
        return pathinfo($file);
    }

    public static function isSrtTemplate($file)
    {
        return isset($file['extension']) && $file['extension'] == 'srt-template';
    }

    public static function createDescription($key, $group)
    {
        return self::createLastDescription($key, $group).PHP_EOL.PHP_EOL;
    }

    public static function createLastDescription($key, $group)
    {
        return ($key+1).PHP_EOL.
            $group->time.PHP_EOL.
            $group->description;
    }
}