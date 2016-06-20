<?php
/**
 * Created by PhpStorm.
 * User: phung
 * Date: 20/06/2016
 * Time: 13:36
 */
namespace  CustomBookBundle\Service;

class Foo
{
    private $em;


  public function __construct(\Doctrine\ORM\EntityManager $em)
  {
      $this->em = $em;
  }

  public function bar()
  {
      //Do the Database stuff

      //Your Query goes here

      $query = $this->em
          ->createQuery(
              'SELECT p FROM CustomBookBundle:Product p');

      $result = $query->getResult();
      return $result;
  }
}