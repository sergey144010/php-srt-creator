<?php
/**
 * Created by PhpStorm.
 * User: Мария
 * Date: 31.05.2017
 * Time: 2:10
 */

namespace sergey144010\phpSrtCreator;

require 'Group.php';
require 'SrtService.php';

class Srt
{
    public function __construct()
    {
        (new SrtService())->open()->createStack()->write();
    }
}

new Srt();