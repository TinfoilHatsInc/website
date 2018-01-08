<?php

namespace AppBundle\Form;

use AppBundle\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            if (null != $event->getData()) {
                if ($event->getData()->getImageName()) {
                    $event->getForm()->add('imageFile', VichImageType::class, [
                        'required' => false,
                        'allow_delete' => false,
                        'download_link' => true,
                        'label' => 'Feature Icon'
                    ]);
                } else {
                    $event->getForm()->add('imageFile', VichImageType::class, [
                        'required' => true,
                        'allow_delete' => false,
                        'download_link' => true,
                        'label' => 'Feature Icon'
                    ]);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Feature::class,
                'allow_extra_fields' => false
            ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_feature_type';
    }
}
