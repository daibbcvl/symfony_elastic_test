<?php

namespace CustomBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use CustomBookBundle\Form\LocationType;
use CustomBookBundle\Entity\Category;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('category',
            'entity',
            array(
                'class'=>'CustomBookBundle\Entity\Category',
                'property'=>'name',
                'query_builder' => function (\CustomBookBundle\Repository\CategoryRepository $repository)
                {
                    return $repository->createQueryBuilder('c');
                }
            )
        )

            ->add('sku', TextType::class, array('label'=>'SKU'))
            //->add('lo', LocationType::class)
            ->add('product_name', TextType::class, array('label'=>'Product name'))
            ->add('price', TextType::class, array('label'=>'Price'))
            ->add('description', TextareaType::class, array('label'=>'Description'))

            ->add('tags',
                'entity',
                array(
                    'class'=>'CustomBookBundle\Entity\Tag',
                    'property'=>'tag_name',
                    'multiple' => true,
                    'expanded' => true
                ,
                    'query_builder' => function (\CustomBookBundle\Repository\TagRepository $repository)
                    {
                        return $repository->createQueryBuilder('t');
                    }
                )
            )
//            ->add('tags', CollectionType::class, array(
//                     'entry_type' => TagType::class
//                 ))

            ->add('save', SubmitType::class, array('label' => 'Save'));
    }

    public function getName()
    {
        return 'product';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CustomBookBundle\Entity\Product',
        ));
    }

}