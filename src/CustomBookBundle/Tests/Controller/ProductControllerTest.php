<?php

namespace CustomBookBundle\Tests\Controller;

use CustomBookBundle\Controller\ProductController;
use CustomBookBundle\CustomBookBundle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use Doctrine\ORM\EntityRepository;
use CustomBookBundle\Repository\ProductRepository;

class ProductControllerTest extends \PHPUnit_Framework_TestCase
{
//    public function testSample()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/product/sample');
//        $heading = $crawler->filter('h1')->eq(0)->text();
//        $this->assertEquals('Crawling Home Page', $heading);
//
//        $para1 = $crawler->filter('p')->first()->text();
//        $this->assertEquals("Here's our crawling home page.", $para1);
//    }

    public function testIndex()
    {
        $controller = new ProductController();

//        $form = $this
//            ->getMockBuilder('Symfony\Component\Form\Form')
//            ->setMethods(array('createView'))
//            ->getMock()
//        ;
//        $form
//            ->expects($this->once())
//            ->method('createView')
//        ;

//        $product = $this
//            ->getMockBuilder('CustomBookBundle\Entity\Product')
//            ->setMethods(array('addTag'))
//            ->getMock()
//        ;
//
//        $product
//            ->expects($this->once())
//            ->method('addTag')
//        ;
//
//        $controller->addAction(new Request);

    }

    public function testStub()
    {
        //$controller = new ProductController();


//        $repository = $this
//            ->getMockBuilder('Doctrine\ORM\EntityRepository')
//            ->disableOriginalConstructor()
//            ->setMethods(array('findOneByEmail'))
//            ->getMock();
//
//        $repository
//            ->expects($this->once())
//            ->method('findOneByEmail')
//            ->will($this->returnValue($queryExpectedValue));



        // Calling $stub->doSomething() will now return
        // 'foo'.
        //$this->assertEquals('foo', $controller->doSomething());
    }
}
