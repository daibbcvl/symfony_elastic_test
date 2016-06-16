<?php

namespace CustomBookBundle\Controller;

use CustomBookBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends Controller
{


    public function indexAction()
    {
        return $this->render('CustomBookBundle:Category:index.html.twig');
    }



public function addAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $categoryEntity = new Category();
        $form = $this->createFormBuilder($categoryEntity)
            ->add('name')
            ->add('description', TextareaType::class, array('label'=>'Description'))
            ->add('save', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoryEntity);
            $em->flush();
            $this->redirect('index');
        }
        return $this->render('CustomBookBundle:Category:add.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
