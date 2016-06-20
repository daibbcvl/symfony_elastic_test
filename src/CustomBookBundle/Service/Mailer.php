<?php
/**
 * Created by PhpStorm.
 * User: phung
 * Date: 20/06/2016
 * Time: 11:45
 */
namespace  CustomBookBundle\Service;

class  Mailer
{
    private $transport;
    function  __construct($transport)
    {
        $this->transport = $transport;
    }
    function sendMail($receiver, $content)
    {
        echo $receiver. " ". $content;
    }
}