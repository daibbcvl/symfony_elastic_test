<?php

namespace AppBundle\Controller;

use AppBundle\Document\ContentMetaObject;
use AppBundle\Document\LocationMetaObject;
use CustomBookBundle\CustomBookBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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


use CustomBookBundle\Entity\Restaurant;
use CustomBookBundle\Form\RestaurantType;





use CustomBookBundle\Entity\User;
use AppBundle\Document\Content;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Null;

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
        $em = $this->get('doctrine')->getManager();
        $userList = array(
            'user1'=>array('ROLE_USER'),
            'user2'=>array('ROLE_USER'),
            'admin1'=>array('ROLE_USER','ROLE_ADMIN'),
            'admin2'=>array('ROLE_USER','ROLE_ADMIN'),
            'supper'=> array('ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN'));
        $password = '12345678';
        $factory = $this->get('security.encoder_factory');

        foreach ($userList as $username => $role)
        {
            $user = new User();
            $user->setUsername($username);
            $user->setFirstname('Test_'.$username);
            $user->setLastname('last_Test_'.$username);
            $user->setEmail($username.'@yahoo.com');
            $user->setRoles($role);
            $encoder = $factory->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($encodedPassword);
            $em->persist($user);
        }



        // encode the password



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

        $listTitle = array(
            'Acme title Sample',
            'Acme title Sample Test1',
            'Acme title Sample Test2',
            'Acme title Sample Test3',
            'Acme title Sample Test4',
            'Acme title Sample Test5',
            'Acme title Sample Test6'
        );

        $i=1;
        foreach ($listTitle as $title)
        {
            $content = new Content();
            $content->id = $i;
            $content->title = $title;
            $manager->persist($content);
            $i++;
        }

        $manager->commit();

        die('created doc');

    }


    /**
     * @Route("/doc_form", name="doc_form")
     */
    public function createDocFormAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $restaurant = new Restaurant();

        $form = $this->createForm(new RestaurantType(), $restaurant);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->get('es.manager');

            $rt = new \CustomBookBundle\Document\Restaurant();
            $rt->address = $restaurant->getAddress();
            $rt->name = $restaurant->getName();
            $content = new \CustomBookBundle\Document\LocationMetaObject();
            $content->lat = $restaurant->getLat();
            $content->lon= $restaurant->getLon();
            $rt->location[] = $content;






            $manager->persist($rt);

            $manager->commit();

            die('success');
            
        }
        return $this->render('CustomBookBundle:Restaurant:index.html.twig', array(
            'form' => $form->createView(),
        ));

    }


        /**
     * @Route("/update_doc", name="update_doc")
     */
    public function updateDocAction(Request $request)
    {

        $manager = $this->get('es.manager');
        $content = $manager->find('AppBundle:Content', 5);
        $content->title = 'changed Acme title';
        $manager->persist($content);
        $manager->commit();

        die('updated doc');


    }


    /**
     * @Route("/find_doc/{word}", name="find_doc")
     */
    public function findDocAction(Request $request, $word = NULL)
    {
        $manager = $this->get("es.manager");
        $repository = $manager->getRepository('AppBundle:Content');

        $search = $repository->createSearch();
        $queryStringQuery = new QueryStringQuery("*".$word."*", ["default_field"=>"title"]);
        $search->addQuery($queryStringQuery);

        $results = $repository->execute($search, Result::RESULTS_OBJECT);

        foreach ($results as $document) {
            echo $document->title, $results->getDocumentSort() ." <br/>";
        }
        die();
    }


    /**
     * @Route("/find_restaurant/{word}", name="find_res")
     */
    public function findRestaurantAction(Request $request, $word = NULL)
    {
        $manager = $this->get("es.manager.restaurant");
        $repository = $manager->getRepository('CustomBookBundle:Restaurant');

        $search = $repository->createSearch();
        $queryStringQuery = new QueryStringQuery("*".$word."*", ["default_field"=>"address"]);
        $search->addQuery($queryStringQuery);

//        $rangeQuery = new RangeQuery('location.lat', ['from' => 0.9]);
//        $search->addQuery($rangeQuery);
//        $rangeQuery = new RangeQuery('location.lon', ['to' => 0]);
//        $search->addQuery($rangeQuery);


//        $termQuery = new TermQuery('name', 'Caraven');
//        $search->addQuery($termQuery);

        $results = $repository->execute($search, Result::RESULTS_ARRAY);

        var_dump($results);

        foreach ($results as $document) {
            var_dump($document['name']. ", Lat: ". $document['location'][0]['lat']  .", Long: ". $document['location'][0]['lon'] );
            //echo $document->title, $results->getDocumentSort() ." <br/>";
        }
        die();
    }



    /**
     * @Route("/restaurant/add", name="rs_add")
     */
    public function addRestaurantAction(Request $request, $word = NULL)
    {
        // create a task and give it some dummy data for this example
        $restaurant = new \CustomBookBundle\Entity\Restaurant();

        $form = $this->createForm(new \CustomBookBundle\Form\RestaurantType(), $restaurant);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->get('es.manager.restaurant');

            $rt = new \CustomBookBundle\Document\Restaurant();
            $rt->address = $restaurant->getAddress();
            $rt->name = $restaurant->getName();
            $content = new \CustomBookBundle\Document\LocationMetaObject();
            $content->lat = $restaurant->getLat();
            $content->lon= $restaurant->getLon();
            $rt->location[] = $content;






            $manager->persist($rt);

            $manager->commit();

            die('success');

        }
        return $this->render('CustomBookBundle:Restaurant:index.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/restaurant/edit/{id}", name="rs_edit")
     */
    public function editRestaurantAction(Request $request, $id = NULL)
    {
        // create a task and give it some dummy data for this example

        if($id == Null){
            throw new \Exception('Something went wrong!');
        }
        $manager = $this->get("es.manager.restaurant");
        $repository = $manager->getRepository('CustomBookBundle:Restaurant');

        $search = $repository->createSearch();

        $restaurantObject = $repository->find($id);//,['name'=>'Quan 3 con cuu 1']);




        $restaurant = new \CustomBookBundle\Entity\Restaurant();

        //var_dump($a=$restaurantObject->location[0]->lat); die;

        $restaurant->setAddress($restaurantObject->address);
        $restaurant->setName($restaurantObject->name);
        $restaurant->setLat($restaurantObject->location[0]->lat);
        $restaurant->setLon($restaurantObject->location[0]->lon);

        $form = $this->createForm(new \CustomBookBundle\Form\RestaurantType(), $restaurant);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $rt = new \CustomBookBundle\Document\Restaurant();
            $rt->address = $restaurant->getAddress();
            $rt->name = $restaurant->getName();
            $content = new \CustomBookBundle\Document\LocationMetaObject();
            $content->lat = $restaurant->getLat();
            $content->lon= $restaurant->getLon();
            $rt->location[] = $content;
            $rt->location[] = $content;






            $manager->persist($rt);

            $manager->commit();

            die('success');

        }
        return $this->render('CustomBookBundle:Restaurant:edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }




}
