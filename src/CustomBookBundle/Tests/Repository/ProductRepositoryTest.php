<?php


namespace CustomBookBundle\Tests\Repository;

use CustomBookBundle\Entity\Product;
use CustomBookBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;

use CustomBookBundle\Entity\Category;
use CustomBookBundle\Entity\Tag;



class ProductRepositoryTest extends WebTestCase
{
    private $em;
    private $application;
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->application = new Application(static::$kernel);
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $query = $this->em->createQuery('DELETE CustomBookBundle:Product p');
        $query->execute();
        $query = $this->em->createQuery('DELETE CustomBookBundle:Category c');
        $query->execute();
        $query = $this->em->createQuery('DELETE CustomBookBundle:Tag t');
        $query->execute();


        /***********************************/
        $category1 = new Category();
        $category1->setName('Category1');
        $category1->setDescription('Category 1');

        $category2 = new Category();
        $category2->setName('Category2');
        $category2->setDescription('Category 2');

        $this->em->persist($category1);
        $this->em->persist($category2);
        /***********************************/
        $tag1 = new Tag();
        $tag1->setTagName('tag1');
        $tag2 = new Tag();
        $tag2->setTagName('tag2');
        $tag3 = new Tag();
        $tag3->setTagName('tag3');

        $this->em->persist($tag1);
        $this->em->persist($tag2);
        $this->em->persist($tag3);
        /***********************************/
        for($i=0; $i <10; $i++)
        {
            $product = new Product();
            $product->setSku('PR000'.$i);
            $product->setProductName(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10));
            $product->setCategory(($i%2 ==0) ? $category1: $category2);
            $product->setPrice(rand(100,1000));
            $product->addTag(($i%2 ==0) ? $tag1 : $tag2);
            if($i %3 ==0){
                $product->addTag($tag3);
            }
            $this->em->persist($product);
        }

        /***********************************/
        $this->em->flush();
    }

    public function testProductCount()
    {
        
        $count = $this->em->getRepository('CustomBookBundle:Product')->countListProductSearch(array());
        $this->assertEquals($count,10);
    }
    
    public function testPagination()
    {
        // Category1: 5 products
        // 00, 02, 04, 06, 08
        // {page =2, perpage =2, category 1}  => 04,06

        $category1 = $this->em->getRepository('CustomBookBundle:Category')->findByName('Category1');
        $count = $this->em->getRepository('CustomBookBundle:Product')->countListProductSearch(array('category'=>$category1[0]->getId()));
        $this->assertEquals($count,5);

        $productList = $this->em->getRepository('CustomBookBundle:Product')->getListItems(array('category'=>$category1[0]->getId()),2,2);

        $this->assertEquals($productList[0]->getSku(),'PR0004');
        $this->assertEquals($productList[1]->getSku(),'PR0006');

    }
}