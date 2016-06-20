<?php

namespace CustomBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;





class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CustomBookBundle:Default:index.html.twig');
    }



    /**
     * @Route("/abc")
     */
    public function restaurantAction()
    {
        die('aa');
        return $this->render('CustomBookBundle:Default:index.html.twig');
    }

    public function serviceAction($name)
    {

        //echo $name; die;
        //die('aaaaa');
//        $greeter = $this->get('custombook.greeter');
//
//
//        $mailer = $this->get('book.mailer');
//        $mailer->sendmail($name,'Hello ABC');
//        return new Response(
//            $greeter->greet($name)
//        );

//        $foo = $this->get('book.foo');
//        $products = $foo->bar();
//        var_dump($products);
//        die;
        //$mailer = $this->get('mailer');
        $newsletter = $this->get('book.newsletter_manager');
       // $newsletter->setMailer($mailer);
        $newsletter->bulk($name, 'Content text');
        die;
    }
}
