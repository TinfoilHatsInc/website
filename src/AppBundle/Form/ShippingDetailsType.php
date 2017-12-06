<?php

namespace AppBundle\Form;

use AppBundle\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'required' => true
            ])
            ->add('houseNumber', TextType::class, [
                'required' => true
            ])
            ->add('zipcode', TextType::class, [
                'required' => true
            ])
            ->add('country', EntityType::class, [
                'class' => 'AppBundle:Country',
                'choice_label' => function ($country) {
                    return ucfirst($country->getName());
                }
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'allow_extra_fields' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_shipping_details_type';
    }
}
