<?php

namespace CustomBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;




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
}
