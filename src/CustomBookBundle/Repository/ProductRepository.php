<?php

namespace CustomBookBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    /*
    * Get list items pagination
    */
    public function getListItems($params, $itemPerPage, $page)
    {
        if(isset($params['category']))
        {
            $query = $this->createQueryBuilder('p')
                ->join('p.category', 'c')
                ->addSelect('c')
                ->where('c.id= :category')
                ->setParameter('category', $params['category'])
                ->getQuery();
        }
        else
        {
            $query = $this->createQueryBuilder('p')
                ->join('p.category', 'c')
                ->addSelect('c')
                ->getQuery();
        }
       // echo $query->getSQLQuery();
        $query->setFirstResult(($page - 1) * $itemPerPage)
            ->setMaxResults($itemPerPage);
        return $query->getResult();
    }



    public function countListProductSearch($params = array())
    {
        if(isset($params['category']))
        {
            $query = $this->getEntityManager()
                ->createQuery(
                    'SELECT COUNT(p.id) as total FROM CustomBookBundle:Product p
                JOIN p.category c
                WHERE c.id= :category')
                ->setParameter('category', $params['category']);
        }
        else {
            $query = $this->getEntityManager()
                ->createQuery(
                    'SELECT COUNT(p.id) as total FROM CustomBookBundle:Product p
                JOIN p.category c');
        }

        return $query->getSingleScalarResult();
    }
}