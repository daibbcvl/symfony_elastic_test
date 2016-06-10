<?php

namespace AppBundle\Controller;

use CustomBookBundle\CustomBookBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Elastica\Query\QueryString;
use Doctrine\ORM\Query;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\QueryStringQuery;
use ONGR\ElasticsearchBundle\Result\Result;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Query\TermsQuery;
use ONGR\ElasticsearchDSL\Query\RangeQuery;



use CustomBookBundle\Entity\User;
use AppBundle\Document\Content;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/book", name="book")
     * @Security("has_role('ROLE_USER')")
     */

    public function bookAction(Request $request)
    {
        $user = $this->getUser();

        //$user1 = new  \CustomBookBundle\Entity\User;
        ///var_dump($user->getRoles());

        var_dump($user);
        echo 'login';
        die;
    }

    /**
     * @Route("/book1", name="book1")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function book1Action(Request $request)
    {
        $user = $this->getUser();
        var_dump($user);
        echo 'login';
        die;
    }


    /**
     * @Route("/book2", name="book2")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function book2Action(Request $request)
    {
        $user = $this->getUser();
        var_dump($user);
        echo 'login';
        die;
    }


    /**
     * @Route("/generateuser", name="generateuser")
     */
    public function generateAction(Request $request)
    {
        $username = 'user5';
        $password = '1235678';

        $em = $this->get('doctrine')->getManager();

        $user = new User();
        $user->setUsername($username);
        $user->setFirstname('Thanh Truc');
        $user->setLastname('Duong');
        $user->setEmail('test5@yahoo.com');
        $user->setRoles(array('ROLE_USER'));

        // encode the password
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
        $em->persist($user);
        $em->flush();

        die('finish');
    }


    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        // get finder object first for our index
        $finder = $this->container->get('fos_elastica.finder.search.user');


        $list = $finder->find("Phung");
        var_dump($list); die;

    }


    /**
     * @Route("/create_doc", name="create_doc")
     */
    public function createDocAction(Request $request)
    {
        $manager = $this->get('es.manager');
        $content = new Content();
        $content->id = 1; // Optional, if not set, elasticsearch will set a random.
        $content->title = 'Acme title Sample';
        $manager->persist($content);
        $manager->commit();

        die('created doc');

    }


    /**
     * @Route("/update_doc", name="update_doc")
     */
    public function updateDocAction(Request $request)
    {

        $manager = $this->get('es.manager');
        $manager = $this->get('es.manager');
        $content = $manager->find('AppBundle:Content', 5);
        $content->title = 'changed Acme title';
        $manager->persist($content);
        $manager->commit();

        die('updated doc');


    }


    /**
     * @Route("/find_doc", name="find_doc")
     */
    public function findDocAction(Request $request)
    {
//        $repo = $this->get('es.manager.default.content');
//
//        /** @var $content Content **/
//        $content = $repo->findBy(['title' => 'Acme']); // 5 is the document _uid in the elasticsearch.
//        var_dump($content);


//        $repo = $this->get('es.manager.default.content');
//        $search = $repo->createSearch();
//        $termQuery = new MatchAllQuery();
//        $results = $repo->execute($search, Result::RESULTS_OBJECT); // Result::RESULTS_OBJECT is the default value
//        echo $results->count() . "<br/>";
//
//        /** @var AppBundle:Content $document */
//        foreach ($results as $document) {
//            echo $document->title, $results->getDocumentSort();
//        }


        // search

//        $repo = $this->get('es.manager.default.city');
//        $search = $repo->createSearch();
//
//        $termQuery = new TermQuery('country', 'Lithuania');
//        $search->addQuery($termQuery);
//
//        $rangeQuery = new RangeQuery('population', ['from' => 10000]);
//        $search->addQuery($rangeQuery);
//
//        $results = $repo->execute($search);
//
//        var_dump($results);
//
//




//
//        $repo = $this->get('es.manager.default.content');
//        $search = $repo->createSearch();
//        $termQuery = new MatchAllQuery();
//        $search->addQuery($termQuery);
//
//        $results = $repo->execute($search, Result::RESULTS_OBJECT); // Result::RESULTS_OBJECT is the default value
//        echo $results->count() . "<br/>";
//
//        /** @var AppBundle:Content $document */
//        foreach ($results as $document) {
//            echo $document->title, $results->getDocumentSort() ." <br/>";
//        }


        $manager = $this->get("es.manager");
        $repository = $manager->getRepository('AppBundle:Content');

        $search = $repository->createSearch();

        $queryStringQuery = new QueryStringQuery("*my5", ["default_field"=>"title"]);
        $search->addQuery($queryStringQuery);


//        $rangeFilter = new RangeFilter('id', ['from' => 5, 'to' => 20]);
//        $search->addFilter($rangeFilter);

        $results = $repository->execute($search, Result::RESULTS_OBJECT);

        foreach ($results as $document) {
            echo $document->title, $results->getDocumentSort() ." <br/>";
        }

        var_dump($results);

        die();
    }

}
