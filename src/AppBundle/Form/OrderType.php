<?php

namespace AppBundle\Form;

use AppBundle\Entity\Country;
use AppBundle\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('zipcode', TextType::class, [
                'required' => true
            ])
            ->add('address', TextType::class, [
                'required' => true
            ])
            ->add('country', EntityType::class, [
                'class' => 'AppBundle:Country',
                'choice_label' => function ($country) {
                    return ucfirst($country->getName());
                }
            ])
            ->add('complete', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_order_type';
    }
}
