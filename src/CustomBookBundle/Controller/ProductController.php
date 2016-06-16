<?php

namespace CustomBookBundle\Controller;

use CustomBookBundle\CustomBookBundle;
use CustomBookBundle\Entity\Category;
use CustomBookBundle\Entity\Product;
use CustomBookBundle\Entity\Tag;

use CustomBookBundle\Form\ProductType;
use CustomBookBundle\Form\TagType;

use CustomBookBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\HttpFoundation\Session\Session;


class ProductController extends Controller
{


    public function indexAction(Request $request, $page)
    {
        $params = array();
        $session = $this->getRequest()->getSession();
        $ssProductCategory = $session->get('product.serach.category');


        $product = new Product();

        if($ssProductCategory != NULL)
        {
            $category  = $this->getDoctrine()
                ->getRepository('CustomBookBundle:Category')
                ->find($ssProductCategory);
            $product->setCategory($category);
        }


        //var_dump($category);

        $form = $this->createFormBuilder($product)
            ->add('category',
                'entity',
                array(
                    'placeholder' => 'Choose a category',
                    'class'=>'CustomBookBundle\Entity\Category',
                    'property'=>'name',
                    'query_builder' => function (\CustomBookBundle\Repository\CategoryRepository $repository)
                    {
                        return $repository->createQueryBuilder('c');
                    }
                )
            )
            ->add('search', SubmitType::class, array('label' => 'search'))->getForm();
        //$form->se

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product.serach.category', $product->getCategory()->getId());
        }

        $ssProductCategory = $session->get('product.serach.category');
        if($ssProductCategory != NULL)
        {
            $params['category']= $ssProductCategory;
        }
        $productRepo = $this->getDoctrine()->getRepository('CustomBookBundle:Product');
        $perPage = 4;

        $totalProduct =  $productRepo->countListProductSearch($params);
        $productList = $productRepo->getListItems($params,$perPage, $page);

        $lastPage = ceil($totalProduct / $perPage);
        $previousPage = $page > 1 ? $page - 1 : 1;
        $nextPage = $page < $lastPage ? $page + 1 : $lastPage;
        return $this->render('CustomBookBundle:Product:index.html.twig', array(
            'feeds' => $productList,
            'page'       => $page,
            'perPage' => ceil($totalProduct/3),
            'lastPage' => $lastPage,
            'previousPage' => $previousPage,
            'currentPage' => $page,
            'nextPage' => $nextPage,
            'totalFeeds' => $totalProduct,
            'form' => $form->createView()
        ));
    }



    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = new Product();

//        $tag  = $this->getDoctrine()
//            ->getRepository('CustomBookBundle:Tag')
//            ->find(1);
//
//        $product->addTag($tag);
//
//        $tag  = $this->getDoctrine()
//            ->getRepository('CustomBookBundle:Tag')
//            ->find(2);
//
//        $product->addTag($tag);

        $form = $this->createForm(new ProductType(), $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();
            $this->redirect('index');
        }
        return $this->render('CustomBookBundle:Category:add.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
