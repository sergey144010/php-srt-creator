<?php
/**
 * Created by PhpStorm.
 * User: Мария
 * Date: 25.05.2017
 * Time: 23:41
 */

namespace sergey144010\phpSrtCreator;

class Group
{
    public $file;
    public $time;
    public $description;
}

class Srt
{
    protected $groups;
    protected $stack;

    public function write()
    {
        $this->stackIsNull();
        foreach ($this->stack as $fileName => $groups) {
            $path_parts = pathinfo($fileName);
            $heandle = fopen($path_parts['filename'].'.srt', 'w');
            $count = count($groups);
            foreach ($groups as $key => $group) {
                if(($key+1) != $count){
                    $description = ($key+1).PHP_EOL.
                        $group->time.PHP_EOL.
                        $group->description.PHP_EOL.PHP_EOL;
                }else{
                    $description = ($key+1).PHP_EOL.
                        $group->time.PHP_EOL.
                        $group->description;
                };
                fwrite($heandle, $description);
            };
            fclose($heandle);
        }
    }

    public function open()
    {
        $files = scandir(__DIR__);
        foreach ($files as $file) {
            if($file == '.' || $file == '..'){ continue; };
            $path_parts = pathinfo($file);
            if(isset($path_parts['extension']) && $path_parts['extension'] == 'srt-template'){
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
        $this->groupsIsNull();
        foreach ($this->groups as $group) {
            $this->stack[$group->file][] = $group;
        }
        return $this;
    }

    public function groupsIsNull()
    {
        if(!isset($this->groups)){
            throw new \ErrorException('Groups is NULL');
        }
    }

    public function stackIsNull()
    {
        if(!isset($this->stack)){
            throw new \ErrorException('Stack is NULL');
        }
    }
}

(new Srt())->open()->createStack()->write();