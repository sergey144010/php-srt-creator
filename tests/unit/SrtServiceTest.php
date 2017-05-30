<?php


use \sergey144010\phpSrtCreator\Group;
use \sergey144010\phpSrtCreator\SrtService;

class SrtServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testTemplateTrue()
    {
        $file = 'test.srt-template';
        $file = SrtService::splitFileName($file);
        $this->assertTrue(
            SrtService::isSrtTemplate($file)
        );
    }

    public function testTemplateFalse()
    {
        $file = 'test.srtBAG-template';
        $file = SrtService::splitFileName($file);
        $this->assertFalse(
            SrtService::isSrtTemplate($file)
        );
    }

    public function testDescriptionTrue()
    {
        $key = 1;
        $group = new Group();
        $group->time = '12345';
        $group->description = 'description';
        $string = '2'.PHP_EOL.'12345'.PHP_EOL.'description'.PHP_EOL.PHP_EOL;
        $this->assertEquals($string, SrtService::createDescription($key, $group));
    }

    public function testLastDescriptionTrue()
    {
        $key = 1;
        $group = new Group();
        $group->time = '12345';
        $group->description = 'description';
        $string = '2'.PHP_EOL.'12345'.PHP_EOL.'description';
        $this->assertEquals($string, SrtService::createDescription($key, $group));
    }


    public function testDescriptionFalse()
    {
        $key = 1;
        $group = new Group();
        $group->time = '12345';
        $group->description = 'description';
        $string = '3'.PHP_EOL.'12345'.PHP_EOL.'description'.PHP_EOL.PHP_EOL;
        $this->assertEquals($string, SrtService::createDescription($key, $group));
    }

    public function testLastDescriptionFalse()
    {
        $key = 1;
        $group = new Group();
        $group->time = '12345';
        $group->description = 'description';
        $string = '3'.PHP_EOL.'12345'.PHP_EOL.'description';
        $this->assertEquals($string, SrtService::createDescription($key, $group));
    }
}