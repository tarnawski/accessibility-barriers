<?php

namespace ApiBundle\Form\Type;

use AccessibilityBarriersBundle\Entity\Notification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextType::class);
        $builder->add('latitude', TextType::class);
        $builder->add('longitude', TextType::class);
        $builder->add('category', EntityType::class, [
            'class' => 'AccessibilityBarriersBundle:Category'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
