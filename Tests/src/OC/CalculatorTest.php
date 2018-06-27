<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 25/06/2018
 * Time: 14:13
 */

namespace OC;

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @test
     * @param $a
     * @param $b
     */
     public function AddNumber(){
         $calculator = new Calculator();
         $calculator = $calculator->add(7,3);
         $this->assertEquals(10, $calculator);
     }

     /**
     * @test
     * @param $a
     * @param $b
     */
     public function substractNumber(){
         $calculator = new Calculator();

         $result = $calculator->substract(10,5);
         $this->assertEquals(5, $result);
     }
}