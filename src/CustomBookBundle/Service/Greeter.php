<?php
/**
 * Created by PhpStorm.
 * User: phung
 * Date: 20/06/2016
 * Time: 11:12
 */
namespace  CustomBookBundle\Service;

class Greeter
{
    public function __construct($greeting)
    {
        $this->greeting = $greeting;
    }

    public function greet($name)
    {
        return $this->greeting . ' ' . $name;
    }
}