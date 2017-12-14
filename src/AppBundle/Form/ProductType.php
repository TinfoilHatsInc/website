<?php

namespace AppBundle\Form;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', IntegerType::class, [
                'label' => 'Price (in cents)'
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => is_null($builder->getData()->getImageName()),
                'allow_delete' => false,
                'download_link' => true,
                'label' => 'Product Image'
            ])
            ->add('iconFile', VichImageType::class, [
                'required' => is_null($builder->getData()->getIconName()),
                'allow_delete' => false,
                'download_link' => true,
                'label' => 'Product Icon'
            ])
            ->add('features', CollectionType::class, [
                'entry_type' => FeatureType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'allow_extra_fields' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_product';
    }
}
