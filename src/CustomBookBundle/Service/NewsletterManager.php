<?php
/**
 * Created by PhpStorm.
 * User: phung
 * Date: 20/06/2016
 * Time: 14:12
 */
namespace  CustomBookBundle\Service;

use CustomBookBundle\CustomBookBundle;

class NewsletterManager
{

    private $mailer;
    function __construct()
    {

    }

    public function setMailer(\CustomBookBundle\Service\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    function bulk($receiver, $content)
    {
        echo "bulk <br/>";
        $this->mailer->sendMail($receiver, $content);
    }
}
