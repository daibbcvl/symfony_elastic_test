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
use CustomBookBundle\Form;

class RestaurantType extends AbstractType
{

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('name', TextType::class, array('label'=>'Restaurant name'))
        ->add('location', LocationType::class)
        ->add('address', TextareaType::class, array('label'=>'Restaurant Address'))
        ->add('save', SubmitType::class, array('label' => 'Save'));
}

public function getName()
{
    return 'restaurant';
}

public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'CustomBookBundle\Entity\Restaurant',
    ));
}

}