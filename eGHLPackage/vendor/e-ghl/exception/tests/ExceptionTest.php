<?php

namespace eGHL\tests;
use eGHL\Exception;

final class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsBase(){
        $parent = get_parent_class('eGHL\Exception');
        $this->assertEquals('Exception', $parent);
    }

    private function thrower(){
        throw new Exception('Exception Message goes here');
    }

    public function testException(){
        $caught = '';
        $expected = "eGHL\Exception: Exception Message goes here\n";
        try{
            $this->thrower();
        }
        catch(Exception $e){
            $caught = "$e";
        }

        $this->assertEquals($expected, $caught);
    }
}
?>